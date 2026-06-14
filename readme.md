# 📘 English Learning Platform (E-Learning)

Dự án xây dựng trang web học tiếng Anh online thông qua video bài giảng, tài liệu và hệ thống bài tập trắc nghiệm thông minh. Dự án sử dụng ngôn ngữ **PHP thuần (PDO)** cho Backend, kết hợp với **HTML, CSS, JavaScript** truyền thống cho Frontend, chạy trên môi trường **XAMPP (Apache & MySQL)**.

* **Thời gian thực hiện:** 3 tuần (21 ngày)
* **Mô hình quản lý:** Monorepo (Tất cả code tập trung tại một Repository)

---

## 👥 Phân công công việc (Team Members)

Nhóm gồm 6 thành viên được chia tách module độc lập nhằm đảm bảo tốc độ code song song và không giẫm chân lên nhau:

* **Frontend 1 (FE 1):** Phụ trách giao diện các trang Người dùng & Học tập (`home.php`, `about.php`, `my-courses.php`, `learn-detail.php`, `quiz.php`).
* **Frontend 2 (FE 2):** Phụ trách giao diện các trang Xác thực & Quản lý (`login.php`, `register.php`, `profile.php`, `admin-dashboard.php`).
* **Backend 1 (BE 1):** Phụ trách Logic Xác thực (Đăng ký, Đăng nhập, phân quyền Session) + API/Logic CRUD tài khoản cho Admin.
* **Backend 2 (BE 2):** Phụ trách Logic nội dung Khóa học (Lấy danh sách khóa học, hiển thị danh sách Unit, bài giảng video, tài liệu đính kèm).
* **Backend 3 (BE 3):** Phụ trách Logic Bài tập trắc nghiệm (Tải câu hỏi, nhận đáp án trắc nghiệm 4 lựa chọn A, B, C, D).
* **Team Leader:** Phụ trách Logic lõi **Tính toán Tiến trình học tập (Progress Tracking)** + Kết nối hệ thống + Quản trị Database chung.

---

## 📁 Cấu trúc Thư mục Dự án

Để không ảnh hưởng đến code của nhau, các thành viên tuân thủ nghiêm ngặt sơ đồ cây thư mục dưới đây:

```text
english-learning-platform/
│
├── config/                       # Cấu hình hệ thống chung
│   └── db.php                    # File kết nối Database MySQL qua PDO
│
├── backend/                      # XỬ LÝ LOGIC PHP (Các BE làm việc ở đây)
│   ├── auth/                     # login_process.php, register_process.php
│   ├── admin/                    # create_user.php, delete_user.php, update_user.php
│   ├── courses/                  # get_courses.php
│   ├── quizzes/                  # submit_quiz.php
│   └── progress/                 # update_progress.php
│
├── frontend/                     # TÀI NGUYÊN TĨNH FRONTEND (Các FE làm việc ở đây)
│   ├── assets/
│   │   ├── css/                  # style.css, admin.css, course.css
│   │   └── js/                   # main.js, auth.js, quiz.js
│   └── uploads/                  # Nơi chứa tài liệu PDF, video bài giảng tải lên
│
├── views/                        # CÁC TRANG GIAO DIỆN CHÍNH (Đuôi .php để đổ dữ liệu)
│   ├── includes/                 # header.php, footer.php (Dùng chung)
│   ├── home.php                  # Trang chủ
│   ├── about.php                 # Trang giới thiệu ngắn gọn
│   ├── login.php / register.php  # Trang Đăng nhập / Đăng ký
│   ├── my-courses.php            # Trang khóa học của tôi
│   ├── learn-detail.php          # Trang xem video bài giảng, tài liệu
│   ├── quiz.php                  # Trang làm bài trắc nghiệm
│   ├── profile.php               # Trang cá nhân & lưu tiến trình
│   └── admin-dashboard.php       # Trang Admin CRUD tài khoản
│
├── docs/                         # Tài liệu đặc tả & Schema DB
│   └── database_schema.sql       # File thiết kế cấu trúc bảng MySQL
│
└── .gitignore                    # Chặn đẩy file rác lên Git

```
---

## 🚀 Hướng dẫn cài đặt & chạy dự án (XAMPP)

Tất cả các thành viên thực hiện các bước sau để thiết lập môi trường chạy code trên máy cục bộ (Localhost):

1. Sao chép dự án vào thư mục XAMPP
   * Mở Git Bash hoặc Terminal.
   * Di chuyển vào thư mục cài đặt của XAMPP (Windows mặc định là `C:\xampp\htdocs\`).
   * Thực hiện clone project về:

   ```bash
   cd C:/xampp/htdocs/
   git clone https://github.com/zyur44/OpenEnglish.git
   ```

2. Khởi tạo cơ sở dữ liệu (MySQL)
   * Mở XAMPP Control Panel và nhấn Start cho Apache và MySQL.
   * Mở trình duyệt và truy cập: `http://localhost/phpmyadmin/`.
   * Chọn tab SQL trên thanh công cụ.
   * Mở file `docs/database_schema.sql` trong thư mục dự án, sao chép toàn bộ nội dung và dán vào khung SQL của phpMyAdmin.
   * Nhấn Go để chạy.
   * Hệ thống sẽ tạo database và các bảng cần thiết.

3. Chạy thử giao diện
   * Mở trình duyệt và truy cập:

   `http://localhost/OpenEnglish/connectdb/db.php`

