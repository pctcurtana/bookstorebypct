<?php
session_start();
include '../../includes/config.php';
// check đn, quyền ad
if(!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('location: ../login.php');
    exit();
}
// check có id sách đc truyền vào k
if(!isset($_GET['id'])) {
    $_SESSION['error'] = "Không tìm thấy sách cần xóa";
    header('location: /admin/dashboard/dashboard.php');
    exit();
}
$book_id = mysqli_real_escape_string($conn, $_GET['id']);
// check sách tồn tại k
$query = "SELECT * FROM products WHERE id = '$book_id'";
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = "Không tìm thấy sách cần xóa";
    header('location: /admin/dashboard/dashboard.php');
    exit();
}
// lấy tt ảnh để xoá file
$book = mysqli_fetch_assoc($result);
$image_path = '../../uploads/' . $book['image'];
// xoá sách
$delete_query = "DELETE FROM products WHERE id = '$book_id'";
if(mysqli_query($conn, $delete_query)) {
    // xoá f ảnh nếu còn
    if(file_exists($image_path)) {
        unlink($image_path);
    }
    $_SESSION['success'] = "Đã xóa sách thành công";
} else {
    $_SESSION['error'] = "Không thể xóa sách: " . mysqli_error($conn);
}
// trả về dashboard
header('location: /admin/dashboard/dashboard.php');
exit();