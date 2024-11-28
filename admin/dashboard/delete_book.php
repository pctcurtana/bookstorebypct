<?php
session_start();
include '../../includes/config.php';

// Kiểm tra đăng nhập và quyền admin
if(!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('location: ../login.php');
    exit();
}

// Kiểm tra có ID sách được truyền vào không
if(!isset($_GET['id'])) {
    $_SESSION['error'] = "Không tìm thấy sách cần xóa";
    header('location: /admin/dashboard/dashboard.php');
    exit();
}

$book_id = mysqli_real_escape_string($conn, $_GET['id']);

// Kiểm tra sách có tồn tại không
$query = "SELECT * FROM products WHERE id = '$book_id'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = "Không tìm thấy sách cần xóa";
    header('location: /admin/dashboard/dashboard.php');
    exit();
}

// Lấy thông tin ảnh để xóa file
$book = mysqli_fetch_assoc($result);
$image_path = '../../uploads/' . $book['image'];

// Thực hiện xóa sách
$delete_query = "DELETE FROM products WHERE id = '$book_id'";

if(mysqli_query($conn, $delete_query)) {
    // Xóa file ảnh nếu tồn tại
    if(file_exists($image_path)) {
        unlink($image_path);
    }
    $_SESSION['success'] = "Đã xóa sách thành công";
} else {
    $_SESSION['error'] = "Không thể xóa sách: " . mysqli_error($conn);
}

// Quay về trang dashboard
header('location: /admin/dashboard/dashboard.php');
exit();