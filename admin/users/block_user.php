<?php

session_start();
include '../../includes/config.php';

// check đn và quyền ad
if(!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('location: login.php');
    exit();
}
if(!isset($_GET['id'])) {
    $_SESSION['error'] = "ID người dùng không hợp lệ";
    header('location: /admin/users/users.php');
    exit();
}
$user_id = intval($_GET['id']);
$query = "UPDATE users SET is_active = 0 WHERE id = $user_id AND is_admin = 0";
if(mysqli_query($conn, $query)) {
    $_SESSION['success'] = "Đã khóa người dùng thành công";
} else {
    $_SESSION['error'] = "Lỗi: " . mysqli_error($conn);
}
header('location: /admin/users/users.php');
exit();

?>