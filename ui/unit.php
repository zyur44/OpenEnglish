<?php
// Trang xem video bài giảng, tài liệu và quiz

require __DIR__ . '/../connectdb/db.php';
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

if ($detailResult['success']) {
    $unit = $detailResult['data']['unit'] ?? null;
    $videos = $detailResult['data']['videos'] ?? [];
    $documents = $detailResult['data']['documents'] ?? [];
} else {
    $message = $detailResult['message'] ?? 'Không thể tải nội dung Unit.';
}
?>

<title>Unit</title>
<link rel="stylesheet" href="../frontend/assets/css/style.css">
<link rel="icon" href="../frontend/assets/image/logo/logo_placeholder.png">
    
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
                        <video controls style="width:100%; max-height:800px;">
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
            </div>
        </div>
    <?php else: ?>
        <div class="oe-card">
            <p>Không có dữ liệu unit để hiển thị.</p>
        </div>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
