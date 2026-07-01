<?php
// ===== Trang chủ của hệ thống OpenEnglish =====
require __DIR__ . '/includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../frontend/assets/css/style.css">
    <link rel="icon" href="../frontend/assets/image/logo/logo_placeholder.png">
</head>
<body>

<!-- ===== Banner chào mừng ===== -->
<section class="oe-hero">
    <div class="oe-container">
    <img src="../frontend/assets/image/logo/logo_placeholder.png">

        <h1>OpenEnglish - Học Tiếng Anh Online Miễn Phí</h1>

        <p>
            Học mọi lúc, mọi nơi với video bài giảng,
            tài liệu PDF và bài kiểm tra trực tuyến.
        </p>

    </div>
</section>

<!-- ===== Phần giới thiệu về OpenEnglish ===== -->
<section class="oe-container">

    <h2>Giới thiệu OpenEnglish</h2>

    <p>
        OpenEnglish là nền tảng học tiếng Anh trực tuyến miễn phí,
        giúp người học tiếp cận kiến thức thông qua video bài giảng,
        tài liệu PDF và các bài kiểm tra trắc nghiệm.
    </p>

</section>

<!-- ===== Phần các tính năng nổi bật ===== -->
<section class="oe-container">

    <h2>Tính năng nổi bật</h2>

    <div class="oe-grid">

        <div class="oe-card">
            <h3>🎬 Video bài giảng</h3>
            <p>Học tiếng Anh qua các video trực quan, dễ hiểu.</p>
        </div>

        <div class="oe-card">
            <h3>📄 Tài liệu PDF</h3>
            <p>Tải tài liệu học tập để ôn luyện mọi lúc.</p>
        </div>

        <div class="oe-card">
            <h3>📝 Quiz trực tuyến</h3>
            <p>Kiểm tra kiến thức sau mỗi bài học.</p>
        </div>

    </div>

</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>