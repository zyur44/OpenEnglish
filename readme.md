# OpenEnglish

* **Project:** Open English học online thông qua video bài giảng, tài liệu, bài tập hoặc test (Free 100%)
* **Group:** 11
* Dự án xây dựng trang web học tiếng Anh online thông qua video bài giảng, tài liệu và hệ thống bài tập trắc nghiệm thông minh. Dự án sử dụng ngôn ngữ **PHP thuần (PDO)** cho Backend, kết hợp với **HTML, CSS, JavaScript** truyền thống cho Frontend, chạy trên môi trường **XAMPP (Apache & MySQL)**.

* **Source code & team management:** GitHub
* **Diagrams (DFD/UML/DB schema):** https://app.diagrams.net/
* **IDE:** VS Code
* **Technology:** HTML, CSS,  JavaScript, PHP, MySQL,…
* **Slide:** Office 
* **Document:** Office

* **Thời gian thực hiện:** 3 tuần (21 ngày)
* **Mô hình quản lý:** Monorepo (Tất cả code tập trung tại một Repository)

---

## Yêu cầu chức năng theo Module

### 2.1. Module Xác thực & Phân quyền (Auth & Authorization)
* **FR-1.1 (Đăng ký tài khoản):** Cho phép người dùng mới tạo tài khoản bằng cách nhập Họ tên, Email hợp lệ và Mật khẩu. Dữ liệu sau khi kiểm tra không trùng lặp sẽ được lưu vào cơ sở dữ liệu.
* **FR-1.2 (Đăng nhập hệ thống):** Người dùng nhập Email và Mật khẩu. Hệ thống sử dụng cơ chế kiểm tra mã hóa (`password_verify`) để xác thực.
* **FR-1.3 (Quản lý Phiên đăng nhập):** Khi đăng nhập thành công, hệ thống phải khởi tạo `Session` để lưu giữ thông tin trạng thái: `user_id`, `user_name`, và `user_role` (1: Admin, 2: Student).
* **FR-1.4 (Chốt chặn bảo mật - Middleware):** Tất cả các API hoặc trang web nhạy cảm phải cấu hình kiểm tra điều kiện bảo mật. Từ chối và trả về mã lỗi thích hợp (`401 Chưa đăng nhập`, `403 Không có quyền`, `405 Sai phương thức HTTP`) nếu vi phạm.

### 2.2. Module Quản lý của Admin (Admin Dashboard)
* **FR-2.1 (Xem danh sách người dùng):** Giao diện Admin cho phép hiển thị danh sách tất cả các tài khoản đang tồn tại trong hệ thống.
* **FR-2.2 (Thêm tài khoản mới):** Admin có quyền tạo tài khoản trực tiếp cho thành viên khác thông qua Form nhập liệu (Họ tên, Email, Vai trò).
* **FR-2.3 (Tự động cấp mật khẩu tạm):** Khi Admin tạo tài khoản thành công, hệ thống phải tự sinh một chuỗi mật khẩu ngẫu nhiên gồm 10 ký tự, băm mật khẩu bảo mật và trả về JSON hiển thị cho Admin để cấp cho người dùng đăng nhập lần đầu.
* **FR-2.4 (Cập nhật / Xóa tài khoản):** Admin có quyền chỉnh sửa thông tin hoặc xóa bỏ tài khoản người dùng trực tiếp trên giao diện quản trị.

### 2.3. Module Khóa học & Nội dung (Courses & Content)
* **FR-3.1 (Hiển thị danh sách khóa học):** Hệ thống hiển thị danh sách các khóa học hiện có (Ví dụ: Tiếng Anh Giao Tiếp, Luyện thi IELTS) kèm Tiêu đề, Mô tả ngắn và Ảnh đại diện (Thumbnail).
* **FR-3.2 (Cấu trúc bài học - Units):** Khi nhấn vào một khóa học, hệ thống hiển thị danh sách các chương học (Units) theo đúng thứ tự thiết kế (`order_index`).
* **FR-3.3 (Trình phát bài giảng - Videos):** Trong mỗi Unit, hệ thống tích hợp trình phát video bài giảng. Video hiển thị đúng tiêu đề và hỗ trợ phát trực tiếp ổn định.
* **FR-3.4 (Tải tài liệu đính kèm - Documents):** Cho phép học sinh xem và tải về các tài liệu bổ trợ (PDF, Word) thuộc Unit tương ứng thông qua đường dẫn lưu trữ an toàn.

### 2.4. Module Bài tập Trắc nghiệm (Quizzes & Testing)
* **FR-4.1 (Tải đề bài tập):** Mỗi Unit tích hợp một bài trắc nghiệm tổng hợp. Khi học sinh truy cập, hệ thống tự động tải danh sách câu hỏi tương ứng trong Database.
* **FR-4.2 (Lựa chọn đáp án):** Hệ thống hiển thị câu hỏi kèm 4 lựa chọn (A, B, C, D). Học sinh chỉ được chọn duy nhất 1 đáp án cho mỗi câu hỏi.
* **FR-4.3 (Nộp bài & Chấm điểm tự động):** Khi học sinh bấm "Nộp bài", Backend thực hiện vòng lặp đối chiếu các đáp án đã chọn với trường `is_correct = 1` trong Database để tính điểm (theo thang điểm 10 hoặc hệ số 100).
* **FR-4.4 (Lưu lịch sử bài làm):** Hệ thống tự động ghi nhận lượt làm bài vào bảng `user_quiz_attempts` bao gồm: Điểm số, trạng thái Đạt hay Không Đạt (`is_passed = 1` nếu điểm $\ge$ 50% số điểm tối đa).

### 2.5. Module Tiến trình Học tập (Progress Tracking)
* **FR-5.1 (Ghi nhận trạng thái xem Video):** Khi học sinh xem hết thời lượng video bài giảng, hệ thống tự động gửi request ngầm (AJAX/Fetch) để cập nhật trạng thái `is_watched = 1` cho video đó.
* **FR-5.2 (Điều kiện hoàn thành một Unit):** Một chương học (Unit) chỉ được tính là hoàn thành **100%** khi và chỉ khi học sinh đã xem xong video (`is_watched = 1`) **VÀ** làm bài trắc nghiệm đạt điểm yêu cầu (`is_passed = 1`).
* **FR-5.3 (Tự động tính toán phần trăm tổng):** Ngay sau khi có sự kiện hoàn thành video hoặc nộp bài trắc nghiệm đạt, hệ thống tự động kích hoạt hàm logic tính toán lại:
  $$\text{Tiến trình Khóa học (\%)} = \left( \frac{\text{Số Unit đã hoàn thành}}{\text{Tổng số Unit của khóa học}} \right) \times 100$$
* **FR-5.4 (Cập nhật và hiển thị Progress Bar):** Giá trị phần trăm sau khi tính toán được cập nhật vào bảng `enrollments` và hiển thị trực quan dưới dạng thanh tiến trình (Progress Bar) tại giao diện "Khóa học của tôi" của học sinh.

---

## 📁 Cấu trúc Thư mục Dự án

Để không ảnh hưởng đến code của nhau, các thành viên tuân thủ nghiêm ngặt sơ đồ cây thư mục dưới đây:

```text
OpenEnglish/
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
├── ui/                           # CÁC TRANG GIAO DIỆN CHÍNH (Đuôi .php để đổ dữ liệu)
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

