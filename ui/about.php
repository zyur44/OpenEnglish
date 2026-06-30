<?php
    require __DIR__ . '/includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link rel="stylesheet" href="../frontend/assets/css/style.css">
    <link rel="icon" href="../frontend/assets/image/logo/logo_placeholder.png">
</head>
<body>
    <!-- ================= BANNER TRANG ================= -->
    <section class="oe-page-banner">
        <div class="oe-container">
            <img src="../frontend/assets/image/logo/logo_placeholder.png">
            <h1>Giới thiệu về OpenEnglish</h1>
            <p>Học tiếng Anh online miễn phí &mdash; đơn giản, rõ ràng, hiệu quả.</p>
        </div>
    </section>

    <!-- ================= TỔNG QUAN ================= -->
    <section class="oe-section">
        <div class="oe-container oe-about-intro">
            <h2>OpenEnglish là gì?</h2>
            <p>
                OpenEnglish là nền tảng học tiếng Anh trực tuyến hoàn toàn miễn phí,
                ra đời với mong muốn giúp mọi người Việt Nam tiếp cận tiếng Anh một
                cách dễ dàng nhất &mdash; không tốn phí, không quảng cáo gây xao nhãng.
            </p>
            <p>
                Mỗi khóa học được chia thành nhiều Unit nhỏ, mỗi Unit gồm video bài
                giảng, tài liệu tham khảo và bài kiểm tra trắc nghiệm, giúp người học
                vừa tiếp thu kiến thức vừa tự đánh giá năng lực ngay sau khi học.
            </p>
        </div>
    </section>

    <!-- ================= TẦM NHÌN / SỨ MỆNH / GIÁ TRỊ ================= -->
    <section class="oe-section oe-section-alt">
        <div class="oe-container">
            <div class="oe-about-grid">
                <div class="oe-card oe-about-card">
                    <div class="oe-feature-icon">🎯</div>
                    <h3>Tầm nhìn</h3>
                    <p>Trở thành nền tảng học tiếng Anh online miễn phí được nhiều người Việt Nam tin dùng nhất.</p>
                </div>
                <div class="oe-card oe-about-card">
                    <div class="oe-feature-icon">🤝</div>
                    <h3>Sứ mệnh</h3>
                    <p>Xóa bỏ rào cản chi phí, mang kiến thức tiếng Anh chất lượng đến với tất cả mọi người.</p>
                </div>
                <div class="oe-card oe-about-card">
                    <div class="oe-feature-icon">⭐</div>
                    <h3>Giá trị cốt lõi</h3>
                    <p>Học thật, tiến bộ thật &mdash; thông qua theo dõi tiến trình minh bạch theo từng tài khoản.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= QUY TRÌNH HỌC ================= -->
    <section class="oe-section">
        <div class="oe-container">
            <div class="oe-section-head">
                <span class="oe-eyebrow">Cách thức hoạt động</span>
                <h2>Học cùng OpenEnglish chỉ với 3 bước</h2>
            </div>

            <div class="oe-steps-grid">
                <div class="oe-card oe-step-card">
                    <div class="oe-step-number">1</div>
                    <h3>Đăng ký tài khoản</h3>
                    <p>Tạo tài khoản miễn phí chỉ trong 1 phút để bắt đầu lưu tiến trình học tập.</p>
                </div>
                <div class="oe-card oe-step-card">
                    <div class="oe-step-number">2</div>
                    <h3>Chọn khóa học</h3>
                    <p>Lựa chọn khóa học phù hợp, học lần lượt qua từng Unit: video và tài liệu.</p>
                </div>
                <div class="oe-card oe-step-card">
                    <div class="oe-step-number">3</div>
                    <h3>Làm bài kiểm tra</h3>
                    <p>Hoàn thành bài trắc nghiệm sau mỗi Unit để đánh giá kết quả học tập.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= CALL TO ACTION ================= -->
    <section class="oe-cta">
        <div class="oe-container oe-cta-inner">
            <h2>Bắt đầu hành trình học tiếng Anh của bạn</h2>
            <p>Hoàn toàn miễn phí &mdash; không cần thẻ thanh toán.</p>
            <a href="/OpenEnglish/ui/register.php" class="oe-btn oe-btn-accent">Đăng ký ngay</a>
        </div>
    </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>