    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $isLoggedIn = isset($_SESSION['user_id']);
    $displayName = $isLoggedIn ? ($_SESSION['full_name'] ?? 'Tài khoản') : null;
    $isAdmin = $isLoggedIn && isset($_SESSION['role']) && strtolower((string)$_SESSION['role']) === 'admin';
    ?>

    <header>
        <nav>
            <ul>
                <li><a href="home.php"><img src="../frontend/assets/image/logo/logo_placeholder.png" width="75px"></a></li>
                <li><a href="home.php">Trang chủ</a></li>
                <li><a href="about.php">Giới thiệu</a></li>
                <li><a href="my-courses.php">Khóa học</a></li>
                <?php if ($isAdmin): ?>
                    <li><a href="admin-dashboard.php">Quản lý tài khoản</a></li>
                <?php endif; ?>
                <?php if ($isLoggedIn): ?>
                    <li class="nav-user">
                        <a href="#" class="nav-username"><?php echo htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); ?></a>
                        <ul class="nav-dropdown">
                            <li><a href="profile.php">Profile</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="login.php">Đăng nhập</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
