<?php
session_start();
include '../../includes/config.php';
// check phải user k
if(!isset($_SESSION['user_id']) || $_SESSION['is_admin']) {
   header('location: ../../sessions/login.php');
   exit();
}

$user_id = $_SESSION['user_id'];
// lấy tt giỏ hàng
$cart_query = "SELECT c.*, p.name, p.price, p.stock, p.image 
              FROM cart c 
              JOIN products p ON c.product_id = p.id 
              WHERE c.user_id = $user_id";
$cart_result = mysqli_query($conn, $cart_query);
// tính tổng tiền
$total = 0;
$cart_items = [];
while($item = mysqli_fetch_assoc($cart_result)) {
   $total += $item['price'] * $item['quantity'];
   $cart_items[] = $item;
}
// xử lý đặt hàng
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);  
    if(empty($address) || empty($phone)) {
        $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin";
    } else {
        mysqli_begin_transaction($conn);
        try {
            // Kiểm tra lại số lượng tồn trước khi đặt hàng
            foreach($cart_items as $item) {
                $stock_check = mysqli_query($conn, "SELECT stock FROM products WHERE id = {$item['product_id']}");
                $current_stock = mysqli_fetch_assoc($stock_check)['stock'];
                
                if($current_stock < $item['quantity']) {
                    throw new Exception("Sản phẩm '{$item['name']}' không đủ số lượng trong kho");
                }
            }           
            // tính tổng tiền trước khi save vào db
            $total_amount = 0;
            foreach($cart_items as $item) {
                $total_amount += $item['price'] * $item['quantity'];
            }                     
            // tạo đơn mới với tổng tiền đã tính
            $order_query = "INSERT INTO orders (user_id, total_amount, address, phone) 
                          VALUES ($user_id, $total_amount, '$address', '$phone')";
            mysqli_query($conn, $order_query);
            $order_id = mysqli_insert_id($conn);                   
            // add detail đơn hàng và cập nhật số lượng tồn
            foreach($cart_items as $item) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];
                $price = $item['price'];                    
                // add detail đơn hàng
                mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) 
                                   VALUES ($order_id, $product_id, $quantity, $price)");               
                // update sl tồn kho
                mysqli_query($conn, "UPDATE products 
                                   SET stock = stock - $quantity 
                                   WHERE id = $product_id");
            }               
            // xoá hàng trong giỏ
            mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id");                      
            mysqli_commit($conn);
            $_SESSION['success'] = "Đặt hàng thành công!";
            header('location: /client/orders/orders.php');
            exit();           
        } catch(Exception $e) {
            mysqli_rollback($conn);
            $_SESSION['error'] = "Có lỗi xảy ra: " . $e->getMessage();
        }
    }
}
include '../../includes/header.php';

?>
<!DOCTYPE html>
<html>
<head>
   <title>Thanh toán</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
   <div class="container py-5">
       <h2>Thanh toán</h2>
       <p class="d-flex justify-content-end">
            <a href="/client/cart/cart.php" class="btn btn-outline-primary me-2">
                <i class="fas fa-arrow-left"></i> Quay lại trang trước
           </a>
       </p>
        <div class="row">
           <div class="col-md-8">
               <div class="card mb-4">
                   <div class="card-body">
                       <h5 class="card-title">Thông tin đơn hàng</h5>
                       <table class="table">
                           <thead>
                               <tr>
                                   <th>Sản phẩm</th>
                                   <th>Số lượng</th>
                                   <th>Đơn giá</th>
                                   <th>Thành tiền</th>
                               </tr>
                           </thead>
                           <tbody>
                               <?php foreach($cart_items as $item): ?>
                               <tr>
                                   <td>
                                       <img src="../../uploads/<?php echo $item['image']; ?>" 
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                       <?php echo $item['name']; ?>
                                   </td>
                                   <td><?php echo $item['quantity']; ?></td>
                                   <td><?php echo number_format($item['price']); ?>đ</td>
                                   <td><?php echo number_format($item['price'] * $item['quantity']); ?>đ</td>
                               </tr>
                               <?php endforeach; ?>
                           </tbody>
                           <tfoot>
                               <tr>
                                   <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                                   <td><strong><?php echo number_format($total); ?>đ</strong></td>
                               </tr>
                           </tfoot>
                       </table>
                   </div>
               </div>
           </div>           
           <div class="col-md-4">
               <div class="card">
                   <div class="card-body">
                       <h5 class="card-title">Thông tin giao hàng</h5>
                       <form method="POST">
                           <div class="mb-3">
                               <label class="form-label">Địa chỉ giao hàng</label>
                               <textarea name="address" class="form-control" required></textarea>
                           </div>
                           <div class="mb-3">
                               <label class="form-label">Số điện thoại</label>
                               <input type="text" name="phone" class="form-control" required>
                           </div>
                           <button type="submit" class="btn btn-primary w-100">Đặt hàng</button>
                       </form>
                   </div>
               </div>
           </div>
           
       </div>
   </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>