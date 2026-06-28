<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản - OpenEnglish</title>
    <link rel="stylesheet" href="../frontend/assets/css/admin.css?v=1.2">
</head>
<body>

<div class="fe2-bg-wrapper">
    <div class="auth-box" style="max-width: 480px;">
        <h2>OpenEnglish</h2>
        <p class="subtitle">Tạo tài khoản học tập miễn phí ngay</p>

        <form action="../backend/auth/register_process.php" method="POST">
            <div class="form-group">
                <label for="fullname">Họ và tên</label>
                <input type="text" id="fullname" name="fullname" placeholder="Nhập họ và tên của bạn" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Ví dụ: nva@gmail.com" required>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="Tối thiểu 6 ký tự" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
            </div>

            <button type="submit" class="btn-primary">Đăng ký ngay</button>

            <div class="auth-switch">
                Đã có tài khoản? <a href="login.php">Đăng nhập</a>
            </div>
        </form>
    </div>
</div>

<script src="../frontend/asset/js/auth.js"></script>
</body>
</html>