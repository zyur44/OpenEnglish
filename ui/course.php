<?php
// ===== Nạp dữ liệu khóa học và trạng thái người dùng =====
require __DIR__ . '/../connectdb/db.php';
require_once __DIR__ . '/../backend/auth/auth_functions.php';
require __DIR__ . '/includes/header.php';

if (!function_exists('getCourseDetail')) {
    require_once __DIR__ . '/../backend/course/get_course_detail.php';
}

require_once __DIR__ . '/../backend/progress/update_progress.php';

ensureSessionStarted();
$sessionStatus = getSessionStatus();
$isLoggedIn = ($sessionStatus['status'] ?? 'error') === 'success';
$loginMessage = 'Bạn cần phải đăng nhập để có thể tiếp tục học.';
$loginRedirectUrl = 'login.php?error=' . urlencode($loginMessage);

$courseId = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
$detailResult = getCourseDetail($courseId);
$course = null;
$units = [];
$message = '';
$unitProgressMap = [];

if ($detailResult['success']) {
    $course = $detailResult['data']['course'] ?? null;
    $units = $detailResult['data']['units'] ?? [];

    if ($isLoggedIn && $courseId > 0) {
        $progressResult = getCourseProgressDetails((int)($sessionStatus['user_id'] ?? 0), $courseId);
        if ($progressResult['status'] && !empty($progressResult['data']['unitDetails'])) {
            foreach ($progressResult['data']['unitDetails'] as $progressDetail) {
                $unitProgressMap[(int)$progressDetail['unitId']] = (float)$progressDetail['progress'];
            }
        }
    }
} else {
    $message = $detailResult['message'] ?? 'Không thể tải thông tin khóa học.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course</title>
    <link rel="stylesheet" href="../frontend/assets/css/style.css">
    <link rel="icon" href="../frontend/assets/image/logo/logo_placeholder.png">
</head>
<body>
<!-- ===== Nội dung chi tiết khóa học ===== -->
<section class="oe-container">
    <?php if (!empty($message)): ?>
        <!-- ===== Thông báo lỗi khi không tải được khóa học ===== -->
        <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php elseif ($course): ?>
        <!-- ===== Banner thông tin khóa học ===== -->
        <div class="oe-course-banner">
            <h1><?php echo htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
            <p><?php echo htmlspecialchars($course['description'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>

        <?php $courseLink = 'my-courses.php'; ?>
        <!-- ===== Nút quay lại danh sách khóa học ===== -->
        <a href="<?php echo htmlspecialchars($courseLink, ENT_QUOTES, 'UTF-8'); ?>" class="oe-btn oe-btn-secondary">← Quay lại khóa học của tôi</a>
        <h2>Danh sách Unit</h2>
        <div class="oe-unit-grid">
            <?php if (!empty($units)): ?>
                <?php foreach ($units as $unit): ?>
                    <?php $unitUrl = 'unit.php?unit_id=' . (int)$unit['id']; ?>
                    <?php $unitProgress = $unitProgressMap[(int)$unit['id']] ?? 0; ?>
                    <?php $unitProgress = max(0, min(100, (float)$unitProgress)); ?>
                    <?php if ($isLoggedIn): ?>
                        <a href="<?php echo htmlspecialchars($unitUrl, ENT_QUOTES, 'UTF-8'); ?>" style="text-decoration:none; color:inherit;">
                            <div class="oe-card oe-unit-card">
                                <div class="oe-unit-card-content" style="padding: 20px; text-align:left;">
                                    <h3><?php echo htmlspecialchars($unit['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                    <p>Unit số <?php echo (int)$unit['order_index']; ?></p>
                                </div>
                                <div class="oe-unit-card-footer">
                                    <div class="oe-unit-progress-meta">
                                        <span>Tiến độ</span>
                                        <span><?php echo (int)$unitProgress; ?>%</span>
                                    </div>
                                    <div class="oe-progress-track" aria-label="Tiến độ unit">
                                        <div class="oe-progress-fill" style="width: <?php echo (int)$unitProgress; ?>%;"></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo htmlspecialchars($loginRedirectUrl, ENT_QUOTES, 'UTF-8'); ?>"
                           onclick="event.preventDefault(); alert('<?php echo htmlspecialchars($loginMessage, ENT_QUOTES, 'UTF-8'); ?>'); window.location.href = this.href;"
                           style="text-decoration:none; color:inherit;">
                            <div class="oe-card oe-unit-card">
                                <div class="oe-unit-card-content" style="padding: 20px; text-align:left;">
                                    <h3><?php echo htmlspecialchars($unit['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                    <p>Unit số <?php echo (int)$unit['order_index']; ?></p>
                                </div>
                                <div class="oe-unit-card-footer">
                                    <div class="oe-unit-progress-meta">
                                        <span>Tiến độ</span>
                                        <span><?php echo (int)$unitProgress; ?>%</span>
                                    </div>
                                    <div class="oe-progress-track" aria-label="Tiến độ unit">
                                        <div class="oe-progress-fill" style="width: <?php echo (int)$unitProgress; ?>%;"></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endif; ?>
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
</body>
</html>