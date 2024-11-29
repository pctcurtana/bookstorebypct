<?php
session_start();
include '../../includes/config.php';

// check đn phải user k
if(!isset($_SESSION['user_id']) || $_SESSION['is_admin']) {
    header('location: ../../sessions/login.php');
    exit();
}

// kiểm tra có id sản phẩm được truyền vào k
if(!isset($_GET['id'])) {
    $_SESSION['error'] = "Không tìm thấy sản phẩm cần xóa";
    header('location: /client/cart/cart.php');
    exit();
}
$user_id = $_SESSION['user_id'];
$product_id = mysqli_real_escape_string($conn, $_GET['id']);
// xoá sp khỏi giỏ 
$delete_query = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
$stmt = mysqli_prepare($conn, $delete_query);
mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
if(mysqli_stmt_execute($stmt)) {
    $_SESSION['success'] = "Đã xóa sản phẩm khỏi giỏ hàng";
} else {
    $_SESSION['error'] = "Không thể xóa sản phẩm: " . mysqli_error($conn);
}
// trả về trang giỏ hàng
header('location: /client/cart/cart.php');
exit();