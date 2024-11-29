<?php
session_start();
include '../../includes/config.php';
// check đn phải user k
if(!isset($_SESSION['user_id']) || $_SESSION['is_admin']) {
    header('location: ../../sessions/login.php');
    exit();
}
// ktra id sách
if(!isset($_GET['id'])) {
    $_SESSION['error'] = "Không tìm thấy sách";
    header('location: home.php');
    exit();
}
$book_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];
// check sách tồn tại và còn k
$query = "SELECT * FROM products WHERE id = $book_id AND stock > 0";
$result = mysqli_query($conn, $query);
if(!$result) {
    $_SESSION['error'] = "Lỗi: " . mysqli_error($conn);
    header('location: /client/home/home.php');
    exit();
}
if(mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = "Sách không tồn tại hoặc đã hết hàng";
    header('location: /client/home/home.php');
    exit();
}
$book = mysqli_fetch_assoc($result);
// check cart tồn tại chưa
$cart_query = "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $book_id";
$cart_result = mysqli_query($conn, $cart_query);
if(!$cart_result) {
    $_SESSION['error'] = "Lỗi: " . mysqli_error($conn);
    header('location: /client/home/home.php');
    exit();
}
if(mysqli_num_rows($cart_result) > 0) {
    // nếu cart có r thì tăng sl
    $cart_item = mysqli_fetch_assoc($cart_result);
    $new_quantity = $cart_item['quantity'] + 1;
    // ktra sl ko vượt quá kho
    if($new_quantity > $book['stock']) {
        $_SESSION['error'] = "Số lượng vượt quá hàng có sẵn";
        header('location: /client/cart/cart.php');
        exit();
    } 
    $update_query = "UPDATE cart SET quantity = $new_quantity 
                    WHERE user_id = $user_id AND product_id = $book_id";
    if(!mysqli_query($conn, $update_query)) {
        $_SESSION['error'] = "Lỗi cập nhật giỏ hàng: " . mysqli_error($conn);
        header('location: /client/cart/cart.php');
        exit();
    }
} else {
    // nếu chưa có thêm vào vỏ
    $insert_query = "INSERT INTO cart (user_id, product_id, quantity) 
                    VALUES ($user_id, $book_id, 1)";
    if(!mysqli_query($conn, $insert_query)) {
        $_SESSION['error'] = "Lỗi thêm vào giỏ hàng: " . mysqli_error($conn);
        header('location: /client/cart/cart.php');
        exit();
    }
}
$_SESSION['success'] = "Đã thêm sách vào giỏ hàng";
// ql trang trước
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/client/home/home.php';
header("location: $referer");
exit();