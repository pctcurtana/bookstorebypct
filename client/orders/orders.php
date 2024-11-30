<?php
session_start();
include '../../includes/config.php';
include '../../includes/header.php';

// check đn
if(!isset($_SESSION['user_id']) || $_SESSION['is_admin']) {
   header('location: ../../sessions/login.php');
   exit();
}
$user_id = $_SESSION['user_id'];
// lấy ds đơn hàng
$query = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at ";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
   <title>Đơn hàng của tôi</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="../../assets/alert.css">
   <script src="../../assets/alert.js"></script>
</head>
<body>
   <div class="container py-4">
       <h2 class="mb-4">Đơn hàng của tôi</h2>
       <div id="alertMessage"></div>
       <script>
            document.addEventListener('DOMContentLoaded', function() {
            <?php if(isset($_SESSION['success'])): ?>
                showAlert(`<?php echo str_replace("'", "\\'", $_SESSION['success']); ?>`, 'success');
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
        });
        </script>
        <div class="card">
           <div class="card-body">
               <table class="table">
                   <thead>
                       <tr>
                           <th>Mã đơn hàng</th>
                           <th>Ngày đặt</th>
                           <th>Tổng tiền</th>
                           <th>Trạng thái</th>
                           <th>Địa chỉ</th>
                       </tr>
                   </thead>
                   <tbody>
                       <?php while($order = mysqli_fetch_assoc($result)): ?>
                       <tr>
                           <td>#<?php echo $order['id']; ?></td>
                           <td><?php echo $order['created_at']; ?></td>
                           <td><?php echo number_format($order['total_amount']); ?>đ</td>
                           <td><?php echo $order['status']; ?></td>
                           <td><?php echo $order['address']; ?></td>
                       </tr>
                       <?php endwhile; ?>
                   </tbody>
               </table>
           </div>
       </div>
        <div class="mt-4">
           <a href="/client/home/home.php" class="btn btn-primary">Quay lại trang chủ</a>
       </div>
   </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>  