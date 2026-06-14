<?php
    // Cấu hình thông số kết nối MySQL của XAMPP mặc định
    $host = 'localhost';
    $db   = 'OpenEnglish'; // Tên database bạn tạo trong phpMyAdmin
    $user = 'root';                // Username mặc định của XAMPP là root
    $pass = '';                    // Password mặc định của XAMPP là rỗng
    $charset = 'utf8mb4';          // Đảm bảo hiển thị tiếng Việt chuẩn

    // Chuỗi DSN (Data Source Name)
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    // Cấu hình các tùy chọn cho PDO
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Đẩy lỗi ra dạng Exception để dễ debug
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Trả dữ liệu về dạng mảng dữ liệu (Associative Array)
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Tăng tính bảo mật bằng cách tắt chế độ giả lập prepare
    ];

    try {
        // Khởi tạo kết nối
        $pdo = new PDO($dsn, $user, $pass, $options);
        
        // Dòng này có thể mở ra để test lúc đầu, khi chạy thật thì nên comment lại
        echo "Kết nối cơ sở dữ liệu qua XAMPP thành công! <br>";
        echo "Giờ thì làm việc đi!";
        
    } catch (\PDOException $e) {
        // Nếu kết nối thất bại, dừng hệ thống và in ra lỗi
        die("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
    }
?>