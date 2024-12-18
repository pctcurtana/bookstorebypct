<?php
session_start();
// xóa cookie remember đn
if(isset($_COOKIE['remember_user'])) {
    setcookie('remember_user', '', time() - 3600, '/');
}
if(isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}
// xóa token trong db
if(isset($_SESSION['user_id'])) {
    include '../../includes/config.php';
    $user_id = $_SESSION['user_id'];
    if($_SESSION['is_admin']) {
        mysqli_query($conn, "UPDATE admin SET remember_token = NULL WHERE id = $user_id");
    } else {
        mysqli_query($conn, "UPDATE users SET remember_token = NULL WHERE id = $user_id");
    }
}
// xóa session
session_destroy();
// chuyển về trang login
header("Location: ../index.php");
exit();
?>