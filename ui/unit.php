<?php
// Trang xem video bài giảng, tài liệu và quiz

require __DIR__ . '/../connectdb/db.php';
require_once __DIR__ . '/../backend/auth/auth_functions.php';

ensureSessionStarted();
$sessionStatus = getSessionStatus();
if (($sessionStatus['status'] ?? 'error') !== 'success') {
    header('Location: login.php?error=' . urlencode('Bạn cần đăng nhập để truy cập unit này.'));
    exit;
}

require __DIR__ . '/includes/header.php';

if (!function_exists('getUnitDetail')) {
    require_once __DIR__ . '/../backend/course/get_unit_detail.php';
}

$unitId = isset($_GET['unit_id']) ? (int)$_GET['unit_id'] : 0;
$detailResult = getUnitDetail($unitId);
$unit = null;
$videos = [];
$documents = [];
$message = '';
$quizId = 0;

if ($detailResult['success']) {
    $unit = $detailResult['data']['unit'] ?? null;
    $videos = $detailResult['data']['videos'] ?? [];
    $documents = $detailResult['data']['documents'] ?? [];

    if ($unitId > 0) {
        $quizStmt = $pdo->prepare("SELECT id FROM quizzes WHERE unit_id = ? LIMIT 1");
        $quizStmt->execute([$unitId]);
        $quizRow = $quizStmt->fetch(PDO::FETCH_ASSOC);
        $quizId = (int)($quizRow['id'] ?? 0);
    }
} else {
    $message = $detailResult['message'] ?? 'Không thể tải nội dung Unit.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unit</title>
    <link rel="stylesheet" href="../frontend/assets/css/style.css">
    <link rel="icon" href="../frontend/assets/image/logo/logo_placeholder.png">
</head>
<body>    
<section class="oe-container">
    <?php if (!empty($message)): ?>
        <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php elseif ($unit): ?>
        <h1><?php echo htmlspecialchars($unit['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
        <p><strong>Khóa học:</strong> <?php echo htmlspecialchars($unit['course_title'], ENT_QUOTES, 'UTF-8'); ?></p>

        <div class="oe-card">
            <div style="padding: 20px; text-align:left;">
                <?php if (!empty($videos)): ?>
                    <?php foreach ($videos as $video): ?>
                        <br>
                        <video
                            controls
                            style="width:100%; max-height:800px;"
                            class="oe-unit-video"
                            data-video-id="<?php echo (int)($video['id'] ?? 0); ?>"
                            data-user-id="<?php echo (int)($sessionStatus['user_id'] ?? 0); ?>"
                        >
                            <source src="<?php echo htmlspecialchars($video['video_url'], ENT_QUOTES, 'UTF-8'); ?>" type="video/mp4">
                            Trình duyệt của bạn không hỗ trợ video.
                        </video>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Chưa có video cho unit này.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="oe-card">
            <div style="padding: 20px; text-align:left;">
                <h3>Tài liệu</h3>
                <?php if (!empty($documents)): ?>
                    <?php foreach ($documents as $document): ?>
                        <br>
                        <iframe
                            src="<?php echo htmlspecialchars($document['file_url'], ENT_QUOTES, 'UTF-8'); ?>"
                            style="width:100%; min-height:700px; border:1px solid #ddd; border-radius:8px;"
                            title="PDF Viewer"
                        ></iframe>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Chưa có tài liệu PDF cho unit này.</p>
                <?php endif; ?>

                <div style="margin-top: 20px;">
                    <?php if ($unitId > 0 && $quizId > 0): ?>
                        <?php $quizLink = 'quiz.php?unit_id=' . (int)$unitId . '&quiz_id=' . (int)$quizId; ?>
                        <a href="<?php echo htmlspecialchars($quizLink, ENT_QUOTES, 'UTF-8'); ?>" class="oe-btn">Làm bài tập</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="oe-card">
            <p>Không có dữ liệu unit để hiển thị.</p>
        </div>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.oe-unit-video').forEach(function (video) {
        const videoId = video.getAttribute('data-video-id');
        const userId = video.getAttribute('data-user-id');

        if (!videoId || !userId) {
            return;
        }

        let watchedMarked = false;

        const markVideoWatched = function () {
            if (watchedMarked) {
                return;
            }

            watchedMarked = true;

            fetch('../backend/progress/update_progress.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    userId: Number(userId),
                    videoId: Number(videoId)
                })
            }).catch(function () {});
        };

        video.addEventListener('ended', markVideoWatched);
        video.addEventListener('timeupdate', function () {
            if (video.duration && video.currentTime >= video.duration - 1) {
                markVideoWatched();
            }
        });
    });
});
</script>
</body>
</html>