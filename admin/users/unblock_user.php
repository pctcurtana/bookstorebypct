<?php
session_start();
include '../../includes/config.php';

// Kiểm tra đăng nhập và quyền admin
if(!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('location: /admin/sessions/login.php');
    exit();
}

// Kiểm tra id người dùng
if(!isset($_GET['id'])) {
    $_SESSION['error'] = "ID người dùng không hợp lệ";
    header('location: /admin/users/users.php');
    exit();
}

$user_id = intval($_GET['id']);

// Cập nhật trạng thái người dùng thành hoạt động (is_active = 1)
$query = "UPDATE users SET is_active = 1 WHERE id = $user_id AND is_admin = 0";

if(mysqli_query($conn, $query)) {
    $_SESSION['success'] = "Đã mở khóa người dùng thành công";
} else {
    $_SESSION['error'] = "Lỗi: " . mysqli_error($conn);
}

header('location: /admin/users/users.php');
exit();
?>