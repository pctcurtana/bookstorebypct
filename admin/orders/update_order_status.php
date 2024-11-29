<?php
session_start();
include '../../includes/config.php';

// check đn và quyền ad
if(!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    echo json_encode(['status' => 'error', 'message' => 'Không có quyền truy cập']);
    exit();
}
if(isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);   
    $query = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $status, $order_id);   
    if(mysqli_stmt_execute($stmt)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
}