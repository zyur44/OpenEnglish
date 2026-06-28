<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - OpenEnglish</title>
    <link rel="stylesheet" href="../frontend/assets/css/admin.css?v=1.2">
</head>
<body>

<div class="fe2-bg-wrapper">
    <div class="auth-box">
        <h2>OpenEnglish</h2>
        <p class="subtitle">Đăng nhập để tiếp tục học tập</p>

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

<script src="../frontend/asset/js/auth.js"></script>
</body>
</html>