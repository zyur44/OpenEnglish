<?php
require __DIR__ . '/../connectdb/db.php';
require __DIR__ . '/includes/header.php';

if (!function_exists('getCourseDetail')) {
    require_once __DIR__ . '/../backend/course/get_course_detail.php';
}

$courseId = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
$detailResult = getCourseDetail($courseId);
$course = null;
$units = [];
$message = '';

if ($detailResult['success']) {
    $course = $detailResult['data']['course'] ?? null;
    $units = $detailResult['data']['units'] ?? [];
} else {
    $message = $detailResult['message'] ?? 'Không thể tải thông tin khóa học.';
}
?>

<title>Course</title>
<link rel="stylesheet" href="../frontend/assets/css/style.css">
<link rel="icon" href="../frontend/assets/image/logo/logo_placeholder.png">
    
<section class="oe-container">
    <?php if (!empty($message)): ?>
        <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php elseif ($course): ?>
        <div class="oe-course-banner">
            <h1><?php echo htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
            <p><?php echo htmlspecialchars($course['description'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>

        <h2>Danh sách Unit</h2>
        <div class="oe-unit-grid">
            <?php if (!empty($units)): ?>
                <?php foreach ($units as $unit): ?>
                    <a href="unit.php?unit_id=<?php echo (int)$unit['id']; ?>" style="text-decoration:none; color:inherit;">
                        <div class="oe-card oe-unit-card">
                            <div style="padding: 20px; text-align:left;">
                                <h3><?php echo htmlspecialchars($unit['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                <p>Unit số <?php echo (int)$unit['order_index']; ?></p>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="oe-card">
                    <p>Khóa học này chưa có unit nào.</p>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="oe-card">
            <p>Không có dữ liệu khóa học để hiển thị.</p>
        </div>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>