<?php
// Lệnh include bám sát thư mục ui/includes/
include 'includes/header.php'; 
?>
<link rel="stylesheet" href="../frontend/assets/css/style.css?v=1.2">
<link rel="icon" href="../frontend/assets/image/logo/logo_placeholder.png">


<div class="fe2-bg-wrapper">
    <form action="../backend/progress/update_progress.php" method="POST" enctype="multipart/form-data" class="profile-container">
        
        <div class="profile-sidebar">
            <div class="avatar-wrapper">
                <img src="../frontend/asset/image/logo/logo_placeholder.png" alt="Avatar" class="avatar" id="avatar-preview">
                
                <label for="avatar-upload" class="btn-upload-avatar">📷 Đổi ảnh</label>
                <input type="file" id="avatar-upload" name="avatar_file" accept="image/*" style="display: none;">
            </div>

            <h3>Nguyễn Tuấn Anh</h3>
            <p>Học viên OpenEnglish</p>
        </div>
        
        <div class="profile-content">
            <h2>Thông tin tài khoản</h2>
            
            <div class="form-group">
                <label for="fullname">Họ và tên</label>
                <input type="text" id="fullname" name="fullname" value="Nguyễn Tuấn Anh">
            </div>

            <div class="form-group">
                <label for="email">Địa chỉ Email (Không thể sửa)</label>
                <input type="email" id="email" name="email" value="tuananh@gmail.com" readonly style="background-color: #e5e7eb; cursor: not-allowed;">
            </div>

            <div class="form-group">
                <label for="old_password">Mật khẩu hiện tại (Nếu muốn đổi)</label>
                <input type="password" id="old_password" name="old_password" placeholder="••••••••">
            </div>

            <div class="form-group">
                <label for="new_password">Mật khẩu mới</label>
                <input type="password" id="new_password" name="new_password" placeholder="Nhập mật khẩu mới">
            </div>

            <button type="submit" class="btn-primary" style="width: auto; padding: 12px 24px;">Lưu thay đổi</button>
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