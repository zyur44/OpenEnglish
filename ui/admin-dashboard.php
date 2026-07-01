<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Quản trị - OpenEnglish</title>
    <link rel="stylesheet" href="../frontend/assets/css/admin.css?v=1.2">
    <link rel="icon" href="../frontend/assets/image/logo/logo_placeholder.png">
</head>
<body>

<div class="admin-container">
    <aside class="admin-sidebar">
        <div class="admin-brand">OpenEnglish Admin</div>
        <ul class="admin-menu">
            <li class="active"><a href="#">Quản lý thành viên</a></li>
            <li><a href="home.php">Quay về Trang chủ</a></li>
        </ul>
    </aside>

    <main class="admin-main">
        <nav class="admin-nav">
            <div class="nav-title">Danh sách người dùng hệ thống</div>
            <div class="nav-user">Xin chào, <strong>Admin</strong></div>
        </nav>

        <div class="admin-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3>Bảng dữ liệu thành viên</h3>
                <a href="../backend/admin/create_user.php" class="btn-sm btn-success">+ Thêm thành viên mới</a>
            </div>

            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Họ và Tên</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th style="text-align: center;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Nguyễn Tuấn Anh</td>
                            <td>tuananh@gmail.com</td>
                            <td>Học viên</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Trần Thị B</td>
                            <td>thib@gmail.com</td>
                            <td>Giáo viên</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

</body>
</html>