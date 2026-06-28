<?php
// Trang màn hình chính

$page_title = "Open English - Trang chủ";

require __DIR__ . '/includes/header.php';
?>


<title>Home</title>
<link rel="stylesheet" href="../frontend/assets/css/style.css">
<link rel="icon" href="../frontend/assets/image/logo/logo_placeholder.png">

<!-- Banner -->
<section class="oe-hero">
    <div class="oe-container">

        <h1>Học Tiếng Anh Online Miễn Phí</h1>

        <p>
            Học mọi lúc, mọi nơi với video bài giảng,
            tài liệu PDF và bài kiểm tra trực tuyến.
        </p>

        <a href="my-courses.php" class="oe-btn">
            Bắt đầu học ngay
        </a>

    </div>
</section>

<!-- Giới thiệu -->
<section class="oe-container">

    <h2>Giới thiệu Open English</h2>

    <p>
        Open English là nền tảng học tiếng Anh trực tuyến miễn phí,
        giúp người học tiếp cận kiến thức thông qua video bài giảng,
        tài liệu PDF và các bài kiểm tra trắc nghiệm.
    </p>

</section>

<!-- Tính năng -->
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

<!-- Khóa học nổi bật -->
<section class="oe-container">

    <h2>Khóa học nổi bật</h2>

    <div class="oe-grid">

        <div class="oe-card">

            <h3>English Basic</h3>

            <p>
                Khóa học dành cho người mới bắt đầu học tiếng Anh.
            </p>

            <a href="course.php?course_id=1">
                Xem khóa học
            </a>

        </div>

        <div class="oe-card">

            <h3>English Communication</h3>

            <p>
                Phát triển kỹ năng giao tiếp tiếng Anh thực tế.
            </p>

            <a href="course.php?course_id=2">
                Xem khóa học
            </a>

        </div>

    </div>

</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
