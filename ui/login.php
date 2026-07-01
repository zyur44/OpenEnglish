
<!-- ===== Trang đăng nhập người dùng ===== -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - OpenEnglish</title>
    <link rel="stylesheet" href="../frontend/assets/css/admin.css?v=1.2">
    <link rel="icon" href="../frontend/assets/image/logo/logo_placeholder.png">
</head>
<body>

<div class="fe2-bg-wrapper">
    <!-- ===== Khung đăng nhập ===== -->
    <div class="auth-box">
        <h2>OpenEnglish</h2>
        <p class="subtitle">Đăng nhập để tiếp tục học tập</p>
        <?php if (!empty($_GET['success'])): ?>
            <div class="auth-success" style="background:#e6ffeb;border:1px solid #88d18f;padding:10px;margin:10px 0;border-radius:4px;color:#1a641a;"><?php echo htmlspecialchars($_GET['success'], ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <?php if (!empty($_GET['error'])): ?>
            <div class="auth-error" style="background:#ffe6e6;border:1px solid #ffb3b3;padding:10px;margin:10px 0;border-radius:4px;color:#800;"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <!-- ===== Form đăng nhập ===== -->
        <form action="../backend/auth/login_process.php" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Nhập email của bạn" required>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
            </div>

            <button type="submit" class="btn-primary">Đăng Nhập</button>

            <div class="auth-switch">
                Chưa có tài khoản? <a href="register.php">Đăng ký</a>
            </div>
        </form>
    </div>
</div>

<script src="../frontend/asset/js/main.js"></script>
</body>
</html>