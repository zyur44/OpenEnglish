<?php
// Trang khóa học

$page_title = "Khóa học";

require __DIR__ . '/../connectdb/db.php';

$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 1;

/* Lấy thông tin khóa học */
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch();

if (!$course) {
    die("Không tìm thấy khóa học!");
}

/* Lấy danh sách Unit */
$stmtUnit = $pdo->prepare("
    SELECT *
    FROM units
    WHERE course_id = ?
    ORDER BY order_index ASC
");
$stmtUnit->execute([$course_id]);
$units = $stmtUnit->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<title>Course</title>
<link rel="stylesheet" href="../frontend/assets/css/admin.css">
<link rel="icon" href="../frontend/assets/image/logo/logo_placeholder.png">
    
<section class="oe-container">

    <div class="oe-course-banner">

        <h1>
            <?php echo htmlspecialchars($course['title']); ?>
        </h1>

        <p>
            <?php echo htmlspecialchars($course['description']); ?>
        </p>

    </div>

    <h2>Danh sách Unit</h2>

    <?php if (!empty($units)): ?>

        <div class="oe-unit-grid">

            <?php foreach ($units as $unit): ?>

                <div class="oe-card">

                    <h3>
                        <?php echo htmlspecialchars($unit['title']); ?>
                    </h3>

                    <p>
                        Unit số <?php echo $unit['order_index']; ?>
                    </p>

                    <a
                        href="unit.php?unit_id=<?php echo $unit['id']; ?>"
                        class="oe-btn"
                    >
                        Vào học
                    </a>

                </div>

            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <div class="oe-card">
            <p>Chưa có Unit nào trong khóa học này.</p>
        </div>

    <?php endif; ?>

</section>

<?php require __DIR__ . '/includes/footer.php'; ?>