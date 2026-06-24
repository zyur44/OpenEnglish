<?php
    //Trang quiz
$page_title = "Quiz";

require __DIR__.'/../connectdb/db.php';

$quiz_id = isset($_GET['quiz_id']) ? (int)$_GET['quiz_id'] : 1;

$stmt = $pdo->prepare("
    SELECT *
    FROM questions
    WHERE quiz_id = ?
");
$stmt->execute([$quiz_id]);

$questions = $stmt->fetchAll();

require __DIR__.'/includes/header.php';
?>

<section class="oe-container">

    <h1>Bài kiểm tra</h1>

    <form method="post"
          action="../backend/quizzes/submit_quiz.php">

        <?php foreach($questions as $index => $q): ?>

            <div class="oe-card">

                <h3>
                    Câu <?php echo $index + 1; ?>:
                    <?php echo htmlspecialchars($q['question_text']); ?>
                </h3>

                <?php
                $stmtAns = $pdo->prepare("
                    SELECT *
                    FROM answers
                    WHERE question_id = ?
                ");
                $stmtAns->execute([$q['id']]);
                $answers = $stmtAns->fetchAll();
                ?>

                <?php foreach($answers as $a): ?>

                    <p>
                        <label>
                            <input
                                type="radio"
                                name="question[<?php echo $q['id']; ?>]"
                                value="<?php echo $a['id']; ?>">

                            <?php echo htmlspecialchars($a['answer_text']); ?>
                        </label>
                    </p>

                <?php endforeach; ?>

            </div>

        <?php endforeach; ?>

        <button type="submit" class="oe-btn">
            Nộp bài
        </button>

    </form>

</section>

<?php require __DIR__.'/includes/footer.php'; ?>
