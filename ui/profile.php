<?php
require_once __DIR__ . '/../backend/auth/auth_functions.php';

$message = '';
$messageType = '';
$profileFullName = '';
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ensureSessionStarted();
    $userId = (int)($_SESSION['user_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($action === 'update_full_name' && $userId > 0) {
        $result = updateUserFullName($userId, $_POST['full_name'] ?? '');
        $message = $result['message'];
        $messageType = $result['status'] === 'success' ? 'success' : 'error';
        if ($result['status'] === 'success') {
            $profileFullName = trim($_POST['full_name'] ?? '');
        }
    } else {
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
}

$profileUser = getCurrentUserProfile();
$profileFullName = $profileUser['full_name'] ?? $profileFullName;
$username = $profileUser['username'] ?? '';
$email = $profileUser['email'] ?? '';

include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../frontend/assets/css/style.css">
    <link rel="stylesheet" href="../frontend/assets/css/admin.css">
    <link rel="icon" href="../frontend/assets/image/logo/logo_placeholder.png">
</head>
<body>

<div class="fe2-bg-wrapper">
    <form action="" method="POST" class="profile-container">
        
        <div class="profile-sidebar">
        </div>
        
        <div class="profile-content">
            <h2>Thông tin tài khoản</h2>

            <form id="fullname-form" method="POST" style="margin-bottom: 20px;">
                <input type="hidden" name="action" value="update_full_name">
                <div class="form-group">
                    <label for="fullname">Họ và tên</label>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <input type="text" id="fullname" name="full_name" value="<?php echo htmlspecialchars($profileFullName, ENT_QUOTES, 'UTF-8'); ?>" readonly style="background-color: #ffffff; cursor: default; flex:1;">
                        <button type="button" id="edit-fullname-btn" class="btn-primary" style="width:auto; padding:8px 14px;">Chỉnh sửa</button>
                        <button type="submit" id="save-fullname-btn" class="btn-primary" style="display:none; width:auto; padding:8px 14px;">Lưu</button>
                        <button type="button" id="cancel-fullname-btn" class="btn-primary" style="display:none; width:auto; padding:8px 14px;">Hủy</button>
                    </div>
                </div>
            </form>

            <div class="form-group">
                <label for="email">Địa chỉ Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" readonly style="background-color: #ffffff; cursor: default;">
            </div>

            <form method="POST" style="margin-top: 20px;">
                <h2>Đổi mật khẩu</h2>
                <div class="form-group">
                    <label for="old_password">Mật khẩu hiện tại</label>
                    <input type="password" id="old_password" name="old_password" placeholder="Nhập mật khẩu hiện tại">
                </div>

                <div class="form-group">
                    <label for="new_password">Mật khẩu mới</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Nhập mật khẩu mới">
                </div>

                <button type="submit" class="btn-primary" style="width: auto; padding: 12px 24px;">Xác nhận</button>
            </form>

            <?php if (!empty($message)): ?>
                <div class="auth-<?php echo $messageType === 'success' ? 'success' : 'error'; ?>" style="background: <?php echo $messageType === 'success' ? '#e6ffeb' : '#ffe6e6'; ?>; border: 1px solid <?php echo $messageType === 'success' ? '#88d18f' : '#ffb3b3'; ?>; padding: 10px; margin: 10px 0; border-radius: 4px; color: <?php echo $messageType === 'success' ? '#1a641a' : '#800'; ?>;">
                    <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>            
        </div>
    </form>
</div>

<script>
    const fullNameInput = document.getElementById('fullname');
    const editBtn = document.getElementById('edit-fullname-btn');
    const saveBtn = document.getElementById('save-fullname-btn');
    const cancelBtn = document.getElementById('cancel-fullname-btn');
    const fullNameForm = document.getElementById('fullname-form');
    const originalValue = fullNameInput ? fullNameInput.value : '';

    function toggleFullNameEditing(isEditing) {
        if (!fullNameInput || !editBtn || !saveBtn || !cancelBtn) {
            return;
        }

        fullNameInput.readOnly = !isEditing;
        fullNameInput.style.cursor = isEditing ? 'text' : 'default';
        editBtn.style.display = isEditing ? 'none' : '';
        saveBtn.style.display = isEditing ? '' : 'none';
        cancelBtn.style.display = isEditing ? '' : 'none';

        if (isEditing) {
            fullNameInput.focus();
            fullNameInput.select();
        }
    }

    if (editBtn) {
        editBtn.addEventListener('click', function () {
            toggleFullNameEditing(true);
        });
    }

    if (cancelBtn) {
        cancelBtn.addEventListener('click', function () {
            if (fullNameInput) {
                fullNameInput.value = originalValue;
            }
            toggleFullNameEditing(false);
        });
    }

    if (saveBtn && fullNameForm) {
        saveBtn.addEventListener('click', function (event) {
            const trimmed = fullNameInput ? fullNameInput.value.trim() : '';
            if (!trimmed) {
                event.preventDefault();
                if (fullNameInput) {
                    fullNameInput.value = originalValue;
                }
                toggleFullNameEditing(false);
                return;
            }
            if (fullNameInput) {
                fullNameInput.value = trimmed;
            }
        });
    }

    if (fullNameInput) {
        fullNameInput.dataset.originalValue = originalValue;
    }

    const avatarInput = document.getElementById('avatar-upload');
    if (avatarInput) {
        avatarInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatar-preview');
                    if (preview) {
                        preview.src = e.target.result;
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    }
</script>
<?php include 'includes/footer.php'; ?>
</body>
</html>