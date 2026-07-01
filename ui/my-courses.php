<?php
// ===== Trang hiển thị danh sách khóa học của người dùng =====

if (!function_exists('getCourses')) {
    require_once __DIR__ . '/../backend/course/get_courses.php';
}

require_once __DIR__ . '/../backend/auth/auth_functions.php';
require_once __DIR__ . '/../backend/progress/update_progress.php';

ensureSessionStarted();
$sessionStatus = getSessionStatus();
$isLoggedIn = ($sessionStatus['status'] ?? 'error') === 'success';
$userId = $isLoggedIn ? (int)($sessionStatus['user_id'] ?? 0) : 0;

$result = getCourses();
$courses = [];
$message = '';
if ($result['success']) {
    $courses = $result['data'] ?? [];

    foreach ($courses as &$course) {
        $courseId = (int)($course['id'] ?? 0);
        $course['course_progress'] = 0;

        if ($isLoggedIn && $courseId > 0) {
            $progressResult = getCourseProgressDetails($userId, $courseId);
            if ($progressResult['status'] && !empty($progressResult['data']['courseProgress'])) {
                $course['course_progress'] = (float)$progressResult['data']['courseProgress'];
            }
        }
    }
    unset($course);
} else {
    $message = $result['message'] ?? 'Không thể tải danh sách khóa học.';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My course</title>
    <link rel="stylesheet" href="../frontend/assets/css/style.css">
    <link rel="icon" href="../frontend/assets/image/logo/logo_placeholder.png">
</head>
<body>
<?php require __DIR__ . '/includes/header.php'; ?>

<!-- ===== Nội dung khóa học của tôi ===== -->
<section class="oe-container">

    <!-- ===== Tiêu đề và mô tả trang ===== -->
    <h1>Khóa học của tôi</h1>

    <p>
        Theo dõi tiến trình học tập và tiếp tục các khóa học đang học.
    </p>

    <?php if (!empty($message)): ?>
        <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <!-- ===== Lưới hiển thị các khóa học ===== -->
    <div class="oe-grid">
        <?php if (!empty($courses)): ?>
            <?php foreach ($courses as $course): ?>
                <div class="oe-card oe-course-card">
                    <img src="<?php echo htmlspecialchars(
                        !empty($course['thumbnail_url'])
                            ? '../frontend/assets/image/course_thumbnail/' . basename($course['thumbnail_url'])
                            : '../frontend/assets/image/course_thumbnail/course_thumbnail_placeholder.webp',
                        ENT_QUOTES,
                        'UTF-8'
                    ); ?>" alt="<?php echo htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8'); ?>" style="width:100%; border-radius: 12px 12px 0 0; object-fit: cover;">
                    <div class="oe-course-card-content" style="padding: 20px; text-align:left;">
                        <h3><?php echo htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p><?php echo htmlspecialchars($course['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p><strong>Units:</strong> <?php echo (int)$course['total_units']; ?></p>
                        <a href="course.php?course_id=<?php echo (int)$course['id']; ?>" class="oe-btn">Xem khóa học</a>
                    </div>
                    <div class="oe-unit-card-footer">
                        <div class="oe-unit-progress-meta">
                            <span>Tiến độ</span>
                            <span><?php echo (int)max(0, min(100, (float)($course['course_progress'] ?? 0))); ?>%</span>
                        </div>
                        <div class="oe-progress-track" aria-label="Tiến độ khóa học">
                            <div class="oe-progress-fill" style="width: <?php echo (int)max(0, min(100, (float)($course['course_progress'] ?? 0))); ?>%;"></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="oe-card">
                <p>Chưa có khóa học nào để hiển thị.</p>
            </div>
        <?php endif; ?>
    </div>

</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
