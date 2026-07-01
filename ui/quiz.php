<?php
// ===== Trang làm bài kiểm tra cho từng unit =====
require __DIR__.'/../connectdb/db.php';
require __DIR__.'/includes/header.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__.'/../backend/quizzes/submit_quiz.php';

$quizId = isset($_GET['quiz_id']) ? (int)$_GET['quiz_id'] : 0;
$unitId = isset($_GET['unit_id']) ? (int)$_GET['unit_id'] : 0;
$submissionMessage = '';
$submissionResult = null;
$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedAnswers = $_POST['question'] ?? [];

    if ($quizId > 0 && $userId > 0) {
        $submissionResult = submitQuiz($userId, $quizId, $submittedAnswers);
        $submissionMessage = $submissionResult['message'] ?? 'Bài làm của bạn đã được ghi nhận.';
    } else {
        $submissionResult = [
            'success' => false,
            'message' => 'Vui lòng đăng nhập để nộp bài.'
        ];
        $submissionMessage = $submissionResult['message'];
    }
}

if ($quizId <= 0 && $unitId > 0) {
    $quizLookupStmt = $pdo->prepare("SELECT id FROM quizzes WHERE unit_id = ? LIMIT 1");
    $quizLookupStmt->execute([$unitId]);
    $quizLookup = $quizLookupStmt->fetch(PDO::FETCH_ASSOC);
    $quizId = (int)($quizLookup['id'] ?? 0);
}

$quizStmt = $pdo->prepare("SELECT id, title, unit_id FROM quizzes WHERE id = ? LIMIT 1");
$quizStmt->execute([$quizId]);
$quiz = $quizStmt->fetch(PDO::FETCH_ASSOC);

if ($quiz && $unitId <= 0) {
    $unitId = (int)($quiz['unit_id'] ?? 0);
}

$questions = [];
if ($quiz) {
    $questionStmt = $pdo->prepare("SELECT id, question_text FROM questions WHERE quiz_id = ? ORDER BY id ASC");
    $questionStmt->execute([$quizId]);
    $questions = $questionStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($questions as &$question) {
        $answerStmt = $pdo->prepare("SELECT id, answer_text FROM answers WHERE question_id = ? ORDER BY id ASC");
        $answerStmt->execute([$question['id']]);
        $question['answers'] = $answerStmt->fetchAll(PDO::FETCH_ASSOC);
    }
    unset($question);
}

$totalQuestions = count($questions);
$quizTitle = $quiz['title'] ?? 'Bài tập';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../frontend/assets/css/style.css">
    <link rel="icon" href="../frontend/assets/image/logo/logo_placeholder.png">
</head>
<body>
<!-- ===== Nội dung bài kiểm tra ===== -->
<section class="oe-container">
    <div class="quiz-shell">
        <?php if ($unitId > 0): ?>
            <div style="margin-bottom: 16px;">
                <a href="unit.php?unit_id=<?php echo (int)$unitId; ?>" class="oe-btn oe-btn-secondary">← Quay lại Unit</a>
            </div>
        <?php endif; ?>

        <!-- ===== Tiêu đề và tiến độ bài làm ===== -->
        <h1><?php echo htmlspecialchars($quizTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
        <p class="quiz-progress" id="quiz-progress">0/<?php echo $totalQuestions; ?></p>

        <?php if (!empty($submissionMessage)): ?>
            <div class="quiz-success">
                <?php echo htmlspecialchars($submissionMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <?php if ($totalQuestions > 0 && empty($submissionResult) && empty($submissionMessage)): ?>
            <!-- ===== Form trả lời câu hỏi ===== -->
            <form method="post" action="">
                <input type="hidden" name="quiz_id" value="<?php echo (int)$quizId; ?>">
                <input type="hidden" name="userId" value="<?php echo (int)$userId; ?>">
                <?php foreach ($questions as $index => $question): ?>
                    <div class="quiz-question-card<?php echo $index === 0 ? ' is-active' : ''; ?>" data-index="<?php echo $index + 1; ?>">
                        <h3>Câu <?php echo $index + 1; ?>.</h3>
                        <p class="quiz-question-text"><?php echo htmlspecialchars($question['question_text'], ENT_QUOTES, 'UTF-8'); ?></p>

                        <div class="quiz-options">
                            <?php foreach ($question['answers'] as $answer): ?>
                                <label class="quiz-option">
                                    <input type="radio" name="question[<?php echo $question['id']; ?>]" value="<?php echo (int)$answer['id']; ?>">
                                    <span><?php echo htmlspecialchars($answer['answer_text'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="quiz-nav">
                    <button type="button" class="oe-btn oe-btn-secondary" id="prev-btn">← Trước</button>
                    <button type="button" class="oe-btn" id="next-btn">Tiếp theo →</button>
                </div>

                <div class="quiz-submit">
                    <button type="submit" class="oe-btn">Nộp bài</button>
                </div>
            </form>
        <?php else: ?>
            <div></div>
        <?php endif; ?>

        <?php if ($submissionResult): ?>
            <!-- ===== Khu vực hiển thị kết quả bài làm ===== -->
            <?php
                $resultMessage = $submissionResult['message'] ?? 'Đã hoàn thành bài làm.';
                $resultData = $submissionResult['data'] ?? [];
                $passFailMessage = $resultData['message'] ?? null;
                if (empty($passFailMessage) && isset($submissionResult['isPassed'])) {
                    $passFailMessage = $submissionResult['isPassed'] ? 'Chúc mừng! Bạn đã hoàn thành bài kiểm tra.' : 'Bạn chưa đạt điểm yêu cầu. Hãy thử lại.';
                }
            ?>
            <div class="quiz-result-card <?php echo $submissionResult['success'] ? 'quiz-result-success' : 'quiz-result-error'; ?>">
                <h3>Kết quả bài làm</h3>
                <p><?php echo htmlspecialchars($resultMessage, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php if (!empty($passFailMessage)): ?>
                    <p><strong><?php echo htmlspecialchars($passFailMessage, ENT_QUOTES, 'UTF-8'); ?></strong></p>
                <?php endif; ?>

                <?php if (!empty($resultData)): ?>
                    <ul>
                        <li><strong>Số câu hỏi:</strong> <?php echo (int)$resultData['totalQuestions']; ?></li>
                        <li><strong>Đúng:</strong> <?php echo (int)$resultData['correctAnswers']; ?></li>
                        <li><strong>Điểm:</strong> <?php echo htmlspecialchars((string)$resultData['score'], ENT_QUOTES, 'UTF-8'); ?></li>
                        <li><strong>Phần trăm:</strong> <?php echo htmlspecialchars((string)$resultData['percentage'] . '%', ENT_QUOTES, 'UTF-8'); ?></li>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
    const cards = Array.from(document.querySelectorAll('.quiz-question-card'));
    const progress = document.getElementById('quiz-progress');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');

    let currentIndex = 0;

    function updateView() {
        cards.forEach((card, index) => {
            card.classList.toggle('is-active', index === currentIndex);
        });

        if (progress) {
            progress.textContent = `${currentIndex + 1}/${cards.length}`;
        }

        prevBtn.disabled = currentIndex === 0;
        nextBtn.disabled = currentIndex === cards.length - 1;
    }

    if (cards.length > 0) {
        prevBtn.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex -= 1;
                updateView();
            }
        });

        nextBtn.addEventListener('click', () => {
            if (currentIndex < cards.length - 1) {
                currentIndex += 1;
                updateView();
            }
        });

        updateView();
    }
</script>

<?php require __DIR__.'/includes/footer.php'; ?>
</body>
</html>