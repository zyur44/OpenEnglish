<?php
require_once __DIR__ . '/../connectdb/db.php';
require_once __DIR__ . '/../backend/auth/auth_functions.php';

ensureSessionStarted();
$sessionStatus = getSessionStatus();
$accessDenied = false;

if ($sessionStatus['status'] !== 'success') {
    header('Location: login.php');
    exit;
}

if (!$sessionStatus['is_admin']) {
    $accessDenied = true;
    $users = [];
} else {
    try {
        $stmt = $pdo->prepare(
            "SELECT u.id, u.full_name, u.email, u.role_id, r.name AS role_name
             FROM users u
             LEFT JOIN roles r ON u.role_id = r.id
             ORDER BY u.id ASC"
        );
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $users = [];
        $errorMessage = 'Lỗi tải danh sách người dùng: ' . $e->getMessage();
    }
}
?>
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
        </nav>

        <div class="admin-content">
            <?php if ($accessDenied): ?>
                <div class="access-denied-box">
                    <h2>Truy cập bị từ chối</h2>
                    <p>Bạn không có quyền truy cập vào trang quản trị này.</p>
                    <p><a href="home.php">Quay về Trang chủ</a></p>
                </div>
            <?php else: ?>
                <div class="admin-header-actions">
                    <h3>Bảng dữ liệu thành viên</h3>
                    <button type="button" class="btn-sm btn-success" id="openCreateUserModal">+ Thêm thành viên mới</button>
                </div>

                <div id="createUserModal" class="modal-overlay" aria-hidden="true">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Thêm thành viên mới</h3>
                            <button type="button" class="modal-close" id="closeCreateUserModal">×</button>
                        </div>
                        <div class="modal-body">
                            <form id="createUserForm">
                                <div class="form-group">
                                    <label for="newFullName">Họ và Tên</label>
                                    <input type="text" id="newFullName" name="full_name" required>
                                    <div class="form-error" id="errorFullName"></div>
                                </div>
                                <div class="form-group">
                                    <label for="newEmail">Email</label>
                                    <input type="email" id="newEmail" name="email" required>
                                    <div class="form-error" id="errorEmail"></div>
                                </div>
                                <div class="form-group">
                                    <label for="newRole">Vai trò</label>
                                    <select id="newRole" name="role" required>
                                        <option value="2">Student</option>
                                        <option value="1">Admin</option>
                                    </select>
                                    <div class="form-error" id="errorRole"></div>
                                </div>
                                <div class="form-group">
                                    <label for="newPassword">Mật khẩu</label>
                                    <input type="password" id="newPassword" name="password" required minlength="8">
                                    <div class="form-error" id="errorPassword"></div>
                                </div>
                                <div class="modal-error-message" id="modalGeneralError"></div>
                                <div class="modal-actions">
                                    <button type="button" class="btn-sm btn-secondary" id="cancelCreateUser">Hủy</button>
                                    <button type="submit" class="btn-sm btn-primary">Xác nhận</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div id="editUserModal" class="modal-overlay" aria-hidden="true">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Chỉnh sửa người dùng</h3>
                            <button type="button" class="modal-close" id="closeEditUserModal">×</button>
                        </div>
                        <div class="modal-body">
                            <form id="editUserForm">
                                <input type="hidden" id="editUserId" name="id">
                                <div class="form-group">
                                    <label for="editFullName">Họ và Tên</label>
                                    <input type="text" id="editFullName" name="full_name" required>
                                    <div class="form-error" id="editErrorFullName"></div>
                                </div>
                                <div class="form-group">
                                    <label for="editEmail">Email</label>
                                    <input type="email" id="editEmail" name="email" required>
                                    <div class="form-error" id="editErrorEmail"></div>
                                </div>
                                <div class="form-group">
                                    <label for="editRole">Vai trò</label>
                                    <select id="editRole" name="role_id" required>
                                        <option value="2">Student</option>
                                        <option value="1">Admin</option>
                                    </select>
                                    <div class="form-error" id="editErrorRole"></div>
                                </div>
                                <div class="form-group">
                                    <label for="editPassword">Mật khẩu</label>
                                    <input type="password" id="editPassword" name="password" minlength="8">
                                    <small class="input-help">Để trống nếu không đổi mật khẩu.</small>
                                    <div class="form-error" id="editErrorPassword"></div>
                                </div>
                                <div class="modal-error-message" id="editModalGeneralError"></div>
                                <div class="modal-actions">
                                    <button type="button" class="btn-sm btn-secondary" id="cancelEditUser">Hủy</button>
                                    <button type="submit" class="btn-sm btn-primary">Lưu</button>
                                </div>
                            </form>
                        </div>
                    </div>
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
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo htmlspecialchars($user['role_name'] ?: 'Không xác định'); ?></td>
                                        <td style="text-align: center;">
                                            <a href="#" class="btn-sm edit-user" data-user-id="<?php echo htmlspecialchars($user['id']); ?>" data-full-name="<?php echo htmlspecialchars($user['full_name'], ENT_QUOTES, 'UTF-8'); ?>" data-email="<?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?>" data-role-id="<?php echo htmlspecialchars($user['role_id']); ?>">Sửa</a>
                                            <a href="#" class="btn-sm btn-danger delete-user" data-user-id="<?php echo htmlspecialchars($user['id']); ?>">Xóa</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="empty-state-cell">Không có người dùng nào trong hệ thống.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (!empty($errorMessage)): ?>
                    <p style="color: red; margin-top: 10px;"><?php echo htmlspecialchars($errorMessage); ?></p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('createUserModal');
    const openButton = document.getElementById('openCreateUserModal');
    const closeButton = document.getElementById('closeCreateUserModal');
    const cancelButton = document.getElementById('cancelCreateUser');
    const form = document.getElementById('createUserForm');
    const generalError = document.getElementById('modalGeneralError');

    function openModal() {
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
    }

    function closeModal() {
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
        form.reset();
        generalError.textContent = '';
        document.getElementById('errorFullName').textContent = '';
        document.getElementById('errorEmail').textContent = '';
        document.getElementById('errorPassword').textContent = '';
    }

    openButton.addEventListener('click', openModal);
    closeButton.addEventListener('click', closeModal);
    cancelButton.addEventListener('click', closeModal);
    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        generalError.textContent = '';
        document.getElementById('errorFullName').textContent = '';
        document.getElementById('errorEmail').textContent = '';
        document.getElementById('errorPassword').textContent = '';

        const payload = {
            full_name: form.full_name.value.trim(),
            email: form.email.value.trim(),
            role: form.role.value,
            password: form.password.value
        };

        fetch('../backend/admin/create_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) {
                if (data.errors) {
                    document.getElementById('errorFullName').textContent = data.errors.full_name || '';
                    document.getElementById('errorEmail').textContent = data.errors.email || '';
                    document.getElementById('errorPassword').textContent = data.errors.password || '';
                } else {
                    generalError.textContent = data.message || 'Đã có lỗi xảy ra';
                }
                throw new Error('Validation failed');
            }
            return data;
        })
        .then(result => {
            if (result.success) {
                window.location.reload();
            } else {
                generalError.textContent = result.message || 'Đã có lỗi xảy ra';
            }
        })
        .catch(() => {
            if (!generalError.textContent) {
                generalError.textContent = 'Không thể tạo người dùng. Vui lòng thử lại.';
            }
        });
    });

    const editModal = document.getElementById('editUserModal');
    const editOpenButtons = document.querySelectorAll('.edit-user');
    const closeEditButton = document.getElementById('closeEditUserModal');
    const cancelEditButton = document.getElementById('cancelEditUser');
    const editForm = document.getElementById('editUserForm');

    function openEditModal() {
        editModal.classList.add('active');
        editModal.setAttribute('aria-hidden', 'false');
    }

    function closeEditModal() {
        editModal.classList.remove('active');
        editModal.setAttribute('aria-hidden', 'true');
        editForm.reset();
        document.getElementById('editModalGeneralError').textContent = '';
        document.getElementById('editErrorFullName').textContent = '';
        document.getElementById('editErrorEmail').textContent = '';
        document.getElementById('editErrorRole').textContent = '';
        document.getElementById('editErrorPassword').textContent = '';
    }

    editOpenButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const userId = this.getAttribute('data-user-id');
            const fullName = this.getAttribute('data-full-name');
            const email = this.getAttribute('data-email');
            const roleId = this.getAttribute('data-role-id');

            document.getElementById('editUserId').value = userId;
            document.getElementById('editFullName').value = fullName;
            document.getElementById('editEmail').value = email;
            document.getElementById('editRole').value = roleId;
            document.getElementById('editPassword').value = '';

            openEditModal();
        });
    });

    closeEditButton.addEventListener('click', closeEditModal);
    cancelEditButton.addEventListener('click', closeEditModal);
    editModal.addEventListener('click', function (event) {
        if (event.target === editModal) {
            closeEditModal();
        }
    });

    editForm.addEventListener('submit', function (event) {
        event.preventDefault();
        document.getElementById('editErrorFullName').textContent = '';
        document.getElementById('editErrorEmail').textContent = '';
        document.getElementById('editErrorRole').textContent = '';
        document.getElementById('editErrorPassword').textContent = '';
        document.getElementById('editModalGeneralError').textContent = '';

        const payload = {
            id: document.getElementById('editUserId').value,
            full_name: document.getElementById('editFullName').value.trim(),
            email: document.getElementById('editEmail').value.trim(),
            role_id: document.getElementById('editRole').value
        };
        const passwordValue = document.getElementById('editPassword').value;
        if (passwordValue.trim() !== '') {
            payload.password = passwordValue;
        }

        fetch('../backend/admin/update_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) {
                if (data.errors) {
                    document.getElementById('editErrorFullName').textContent = data.errors.full_name || '';
                    document.getElementById('editErrorEmail').textContent = data.errors.email || '';
                    document.getElementById('editErrorRole').textContent = data.errors.role_id || '';
                    document.getElementById('editErrorPassword').textContent = data.errors.password || '';
                } else {
                    document.getElementById('editModalGeneralError').textContent = data.message || 'Đã có lỗi xảy ra';
                }
                throw new Error('Validation failed');
            }
            return data;
        })
        .then(result => {
            if (result.success) {
                window.location.reload();
            } else {
                document.getElementById('editModalGeneralError').textContent = result.message || 'Đã có lỗi xảy ra';
            }
        })
        .catch(() => {
            if (!document.getElementById('editModalGeneralError').textContent) {
                document.getElementById('editModalGeneralError').textContent = 'Không thể cập nhật người dùng. Vui lòng thử lại.';
            }
        });
    });

    document.querySelectorAll('.delete-user').forEach(function (deleteLink) {
        deleteLink.addEventListener('click', function (event) {
            event.preventDefault();
            const userId = this.getAttribute('data-user-id');
            const confirmed = window.confirm('Bạn có chắc muốn xóa người dùng này?');
            if (!confirmed) {
                return;
            }

            fetch('../backend/admin/delete_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: userId })
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Lỗi xóa người dùng');
                }
                return data;
            })
            .then(result => {
                if (result.success) {
                    window.location.reload();
                } else {
                    alert(result.message || 'Xóa không thành công');
                }
            })
            .catch(error => {
                alert(error.message || 'Không thể xóa người dùng. Vui lòng thử lại.');
            });
        });
    });
});
</script>
</body>
</html>