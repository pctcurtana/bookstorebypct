<?php
session_start();
include '../../includes/config.php';
// check đn và quyền ad
if(!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('location: ../login.php');
    exit();
}
// lấy ds đơn với tt user
$query = "SELECT o.*, u.username, u.email 
          FROM orders o 
          JOIN users u ON o.user_id = u.id 
          ORDER BY o.created_at DESC";
$orders = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Quản lý đơn hàng - Admin</title>
    <link rel="icon" href="/assets/headicon.png " type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/alert.css">
    <script src="../../assets/alert.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../../includes/sidebar.php'; ?>
            <div class="col-md-9 col-lg-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Quản lý đơn hàng</h2>
                </div>
                <div id="alertMessage"></div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Khách hàng</th>
                                        <th>Email</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày đặt</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($order = mysqli_fetch_assoc($orders)): ?>
                                    <tr>
                                        <td>#<?php echo $order['id']; ?></td>
                                        <td><?php echo $order['username']; ?></td>
                                        <td><?php echo $order['email']; ?></td>
                                        <td><?php echo number_format($order['total_amount']); ?>đ</td>
                                        <td>
                                            <select class="form-select form-select-sm status-select" 
                                                    data-order-id="<?php echo $order['id']; ?>"
                                                    style="width: auto;">
                                                <option value="Chờ xử lý" <?php echo ($order['status'] == 'Chờ xử lý') ? 'selected' : ''; ?>>
                                                    Chờ xử lý
                                                </option>
                                                <option value="Đang giao" <?php echo ($order['status'] == 'Đang giao') ? 'selected' : ''; ?>>
                                                    Đang giao
                                                </option>
                                                <option value="Đã giao" <?php echo ($order['status'] == 'Đã giao') ? 'selected' : ''; ?>>
                                                    Đã giao
                                                </option>
                                                <option value="Đã hủy" <?php echo ($order['status'] == 'Đã hủy') ? 'selected' : ''; ?>>
                                                    Đã hủy
                                                </option>
                                            </select>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                        <td>
                                            <a href="/admin/orders/view_order.php?id=<?php echo $order['id']; ?>" 
                                               class="btn btn-sm btn-info text-white">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.status-select').forEach(select => {
    select.addEventListener('change', function() {
        const orderId = this.dataset.orderId;
        const status = this.value;       
        fetch('update_order_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `order_id=${orderId}&status=${status}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Response:', data); // Để debug
            if(data.status === 'success') {
                showAlert('Cập nhật trạng thái thành công!', 'success', 'alertMessage');
            } else {
                showAlert(data.message || 'Có lỗi xảy ra', 'danger', 'alertMessage');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Có lỗi xảy ra khi cập nhật trạng thái', 'danger', 'alertMessage');
        });
    });
});
</script>
</body>
</html>