<?php
session_start();
include '../../includes/config.php';

// Kiểm tra đăng nhập và quyền admin
if(!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('location: ../login.php');
    exit();
}

if(!isset($_GET['id'])) {
    header('location: /admin/orders/orders.php');
    exit();
}

$order_id = mysqli_real_escape_string($conn, $_GET['id']);

// Lấy thông tin đơn hàng
$query = "SELECT o.*, u.username, u.email 
          FROM orders o 
          JOIN users u ON o.user_id = u.id 
          WHERE o.id = '$order_id'";
$result = mysqli_query($conn, $query);
$order = mysqli_fetch_assoc($result);

if(!$order) {
    header('location: /admin/orders/orders.php');
    exit();
}

// Lấy chi tiết đơn hàng
$query = "SELECT oi.*, p.name, p.image 
          FROM order_items oi 
          JOIN products p ON oi.product_id = p.id 
          WHERE oi.order_id = '$order_id'";
$items = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chi tiết đơn hàng - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Chi tiết đơn hàng #<?php echo $order['id']; ?></h2>
            <a href="/admin/orders/orders.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thông tin đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Mã đơn hàng:</strong> #<?php echo $order['id']; ?></p>
                        <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
                        <p><strong>Trạng thái:</strong> <?php echo $order['status']; ?></p>
                        <p><strong>Tổng tiền:</strong> <?php echo number_format($order['total_amount']); ?>đ</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thông tin khách hàng</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Tên khách hàng:</strong> <?php echo $order['username']; ?></p>
                        <p><strong>Email:</strong> <?php echo $order['email']; ?></p>
                        <p><strong>Địa chỉ:</strong> <?php echo $order['address']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Chi tiết sản phẩm</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($item = mysqli_fetch_assoc($items)): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="../../uploads/<?php echo $item['image']; ?>" 
                                             alt="<?php echo $item['name']; ?>"
                                             style="width: 50px; height: 75px; object-fit: cover;"
                                             class="me-3">
                                        <?php echo $item['name']; ?>
                                    </div>
                                </td>
                                <td><?php echo number_format($item['price']); ?>đ</td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo number_format($item['price'] * $item['quantity']); ?>đ</td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>