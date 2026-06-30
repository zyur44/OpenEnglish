<?php
require_once __DIR__ . '/../backend/auth/auth_functions.php';
include 'includes/header.php';

$profileUser = getCurrentUserProfile();
$profileFullName = $profileUser['full_name'] ?? '';
$username = $profileUser['username'] ?? '';
$email = $profileUser['email'] ?? '';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = (int)($_SESSION['user_id'] ?? 0);
    $currentPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';

    if ($userId > 0) {
        $result = changeUserPassword($userId, $currentPassword, $newPassword);
        $message = $result['message'];
        $messageType = $result['status'] === 'success' ? 'success' : 'error';
    } else {
        $message = 'Vui lòng đăng nhập trước';
        $messageType = 'error';
    }
}
?>
<link rel="stylesheet" href="../frontend/assets/css/style.css">
<link rel="stylesheet" href="../frontend/assets/css/admin.css">
<link rel="icon" href="../frontend/assets/image/logo/logo_placeholder.png">


<div class="fe2-bg-wrapper">
    <form action="" method="POST" class="profile-container">
        
        <div class="profile-sidebar">
        </div>
        
        <div class="profile-content">
            <h2>Thông tin tài khoản</h2>
            
            <div class="form-group">
                <label for="fullname">Họ và tên</label>
                <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($profileFullName, ENT_QUOTES, 'UTF-8'); ?>" readonly style="background-color: #ffffff; cursor: default;">
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>" readonly style="background-color: #ffffff; cursor: default;">
            </div>

            <div class="form-group">
                <label for="email">Địa chỉ Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" readonly style="background-color: #ffffff; cursor: default;">
            </div>

            <h2>Đổi mật khẩu</h2>
            <div class="form-group">
                <label for="old_password">Mật khẩu hiện tại</label>
                <input type="password" id="old_password" name="old_password" placeholder="Nhập mật khẩu hiện tại">
            </div>

            <div class="form-group">
                <label for="new_password">Mật khẩu mới</label>
                <input type="password" id="new_password" name="new_password" placeholder="Nhập mật khẩu mới">
            </div>

            <button type="submit" class="btn-primary" style="width: auto; padding: 12px 24px;">Lưu thay đổi</button>

            <?php if (!empty($message)): ?>
                <div class="auth-<?php echo $messageType === 'success' ? 'success' : 'error'; ?>" style="background: <?php echo $messageType === 'success' ? '#e6ffeb' : '#ffe6e6'; ?>; border: 1px solid <?php echo $messageType === 'success' ? '#88d18f' : '#ffb3b3'; ?>; padding: 10px; margin: 10px 0; border-radius: 4px; color: <?php echo $messageType === 'success' ? '#1a641a' : '#800'; ?>;">
                    <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>            
        </div>
    </form>
</div>

<script>
    document.getElementById('avatar-upload').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
<?php include 'includes/footer.php'; ?>