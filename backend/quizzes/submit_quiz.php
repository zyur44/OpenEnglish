<?php
require_once __DIR__ . '/../../connectdb/db.php';

header('Content-Type: application/json; charset=utf-8');

/**
 * Nộp bài khóa học và tính điểm
 * 
 * @param int $userId ID của học sinh
 * @param int $quizId ID của bài kiểm tra
 * @param array $answers Mảng các câu trả lời (key=question_id, value=answer_id)
 * @return array Kết quả với score, is_passed, điểm và phần trăm
 */
function submitQuiz($userId, $quizId, $answers) {
    global $pdo;
    
    $userId = (int)$userId;
    $quizId = (int)$quizId;
    
    if ($userId <= 0 || $quizId <= 0) {
        return [
            'success' => false,
            'message' => 'Thiếu userId hoặc quizId'
        ];
    }
    
    if (empty($answers) || !is_array($answers)) {
        return [
            'success' => false,
            'message' => 'Không có câu trả lời nào'
        ];
    }
    
    try {
        $correctCount = 0;
        $totalQuestions = count($answers);
        
        // Kiểm tra từng câu trả lời
        foreach ($answers as $questionId => $answerId) {
            $questionId = (int)$questionId;
            $answerId = (int)$answerId;
            
            // Kiểm tra xem câu trả lời có đúng không
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as count 
                FROM answers 
                WHERE id = ? AND question_id = ? AND is_correct = 1
            ");
            $stmt->execute([$answerId, $questionId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['count'] > 0) {
                $correctCount++;
            }
        }
        
        // Tính điểm
        $percentage = ($correctCount / $totalQuestions) * 100;
        $score = ($correctCount / $totalQuestions) * 10;
        $isPassed = $percentage >= 70 ? 1 : 0; // Đậu tiên >= 70%
        
        // Lưu bài làm vào đơ sở
        $saveStmt = $pdo->prepare("
            INSERT INTO user_quiz_attempts (user_id, quiz_id, score, is_passed, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $saveStmt->execute([$userId, $quizId, $score, $isPassed]);
        
        return [
            'success' => true,
            'message' => 'Nộp bài thành công',
            'data' => [
                'totalQuestions' => $totalQuestions,
                'correctAnswers' => $correctCount,
                'percentage' => round($percentage, 2),
                'score' => round($score, 2),
                'isPassed' => (bool)$isPassed,
                'message' => $isPassed ? 'Chúc mừng! Bạn đã hoàn thành bài kiểm tra.' : 'Bạn chưa đạt điểm yêu cầu. Hãy thử lại.'
            ]
        ];
        
    } catch (PDOException $e) {
        return [
            'success' => false,
            'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()
        ];
    }
}

/**
 * API Endpoint: Nộp bài quiz
 * POST: /backend/quizzes/submit_quiz.php
 * Dữ liệu gửi: { "userId": 1, "quizId": 2, "answers": { "1": 1, "2": 5, "3": 9 } }
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    
    $userId = $data['userId'] ?? 0;
    $quizId = $data['quizId'] ?? 0;
    $answers = $data['answers'] ?? [];
    
    // Cũng hỗ trợ cốch gửi cũ (POST form)
    if (!$userId && isset($_POST['userId'])) {
        $userId = $_POST['userId'];
        $answers = $_POST['answers'] ?? [];
        $quizId = $_POST['quizId'];
    }
    
    $result = submitQuiz($userId, $quizId, $answers);
    
    if (!$result['success']) {
        http_response_code(400);
    }
    
    echo json_encode($result);
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method không hợp lệ']);
}
?>
