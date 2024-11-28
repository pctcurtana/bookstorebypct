<?php
// Lấy thông tin kết nối từ các biến môi trường
define('DB_HOST', getenv('DB_HOST'));  // Thông tin host từ biến môi trường
define('DB_USER', getenv('DB_USER'));  // Tên người dùng cơ sở dữ liệu
define('DB_PASS', getenv('DB_PASS'));  // Mật khẩu cơ sở dữ liệu
define('DB_NAME', getenv('DB_NAME'));  // Tên cơ sở dữ liệu
define('DB_PORT', getenv('DB_PORT'));  // Cổng kết nối (nếu có)

define('SSL_CERT', getenv('SSL_CERT'));  // Đường dẫn tới file chứng chỉ SSL của Aiven

// Kết nối tới cơ sở dữ liệu MySQL với SSL
$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, SSL_CERT, NULL, NULL);
mysqli_real_connect($conn, DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

// Kiểm tra kết nối
if (mysqli_connect_errno()) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

echo "Kết nối thành công!";
?>
