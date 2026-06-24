
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.querySelector('form[action="../backend/auth/register_process.php"]');
    
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            // Validate độ dài mật khẩu
            if (password.length < 6) {
                alert("Mật khẩu phải từ 6 ký tự trở lên!");
                e.preventDefault(); 
                return;
            }
            
            // Validate khớp mật khẩu
            if (password !== confirmPassword) {
                alert("Mật khẩu xác nhận không khớp!");
                e.preventDefault(); 
            }
        });
    }
});