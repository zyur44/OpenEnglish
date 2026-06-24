<?php
// Trang xem video bài giảng, tài liệu và quiz

$page_title = "Chi tiết Unit";

require __DIR__ . '/../connectdb/db.php';

/* Lấy unit_id từ URL */
$unit_id = isset($_GET['unit_id']) ? (int)$_GET['unit_id'] : 1;

/* Lấy thông tin Unit */
$stmt = $pdo->prepare("
    SELECT u.*, c.title AS course_title
    FROM units u
    JOIN courses c ON u.course_id = c.id
    WHERE u.id = ?
");
$stmt->execute([$unit_id]);

$unit = $stmt->fetch();

if (!$unit) {
    die("Không tìm thấy Unit!");
}

require __DIR__ . '/includes/header.php';
?>

<section class="oe-container">

    <h1>
        <?php echo htmlspecialchars($unit['course_title']); ?>
        -
        <?php echo htmlspecialchars($unit['title']); ?>
    </h1>

    <div class="oe-card">

        <h3>Video bài giảng</h3>

        <video width="100%" controls>
            <source src="../frontend/upload/video/unit1.mp4" type="video/mp4">
            Trình duyệt không hỗ trợ video.
        </video>

    </div>

    <div class="oe-card">

        <h3>Tài liệu PDF</h3>

        <a
            href="../frontend/upload/pdf/unit1.pdf"
            target="_blank"
            class="oe-btn"
        >
            Xem tài liệu
        </a>

    </div>

    <div class="oe-card">

        <h3>Bài kiểm tra</h3>

        <a href="quiz.php?quiz_id=1" class="oe-btn">
            Làm Quiz
        </a>

    </div>

</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
