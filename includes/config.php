<link rel="icon" href="/assets/headicon.png " type="image/x-icon">

<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bookstore');
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
?> 
