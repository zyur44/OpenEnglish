<?php
require __DIR__.'/../connectdb/db.php';
require __DIR__.'/includes/header.php';

$quizId = isset($_GET['quiz_id']) ? (int)$_GET['quiz_id'] : 0;
$unitId = isset($_GET['unit_id']) ? (int)$_GET['unit_id'] : 0;
$submissionMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submissionMessage = 'Bài làm của bạn đã được ghi nhận.';
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
<section class="oe-container">
    <div class="quiz-shell">
        <?php if ($unitId > 0): ?>
            <div style="margin-bottom: 16px;">
                <a href="unit.php?unit_id=<?php echo (int)$unitId; ?>" class="oe-btn oe-btn-secondary">← Quay lại Unit</a>
            </div>
        <?php endif; ?>

        <h1><?php echo htmlspecialchars($quizTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
        <p class="quiz-progress" id="quiz-progress">0/<?php echo $totalQuestions; ?></p>

        <?php if (!empty($submissionMessage)): ?>
            <div class="quiz-success">
                <?php echo htmlspecialchars($submissionMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <?php if ($totalQuestions > 0): ?>
            <form method="post" action="">
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
            <div class="quiz-empty">Hiện chưa có câu hỏi nào trong bài tập này.</div>
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