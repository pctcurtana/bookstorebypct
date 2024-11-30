<?php
session_start();
include '../../includes/config.php';
include '../../includes/header.php';


// check đn và quyền user
if(!isset($_SESSION['user_id']) || $_SESSION['is_admin']) {
    header('location: ../../sessions/login.php');
    exit();
}
$user_id = $_SESSION['user_id'];
// lấy sách trong cart
$query = "SELECT c.*, p.name, p.price, p.image, p.stock 
          FROM cart c 
          JOIN products p ON c.product_id = p.id 
          WHERE c.user_id = $user_id";
$cart_items = mysqli_query($conn, $query);
// tính tổng tiền
$total = 0;
if(isset($_GET['id']) && isset($_GET['quantity'])) {
    $product_id = mysqli_real_escape_string($conn, $_GET['id']);
    $quantity = mysqli_real_escape_string($conn, $_GET['quantity']);
    $user_id = $_SESSION['user_id'];
    
    // Kiểm tra tồn kho
    $stock_check = mysqli_query($conn, "SELECT stock FROM products WHERE id = '$product_id'");
    $stock = mysqli_fetch_assoc($stock_check)['stock'];
    
    if($quantity <= $stock && $quantity > 0) {
        $update_query = "UPDATE cart SET quantity = '$quantity' 
                        WHERE user_id = '$user_id' AND product_id = '$product_id'";
        if(mysqli_query($conn, $update_query)) {
            $_SESSION['success'] = "Đã cập nhật số lượng sản phẩm";
        } else {
            $_SESSION['error'] = "Không thể cập nhật số lượng";
        }
    } else {
        $_SESSION['error'] = "Số lượng không hợp lệ!";
    }
    
    header('Location: cart.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Giỏ hàng - Cửa hàng sách</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/alert.css">
    <script src="../../assets/alert.js"></script>
    <style>
        .cart-item-image {
            width: 100px;
            height: 150px;
            object-fit: cover;
        }
        .quantity-input {
            width: 70px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <h2 class="mb-4">Giỏ hàng của bạn</h2>
        <div id="alertMessage"></div>
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
            <?php if(isset($_SESSION['success'])): ?>
                showAlert(`<?php echo str_replace("'", "\\'", $_SESSION['success']); ?>`, 'success');
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
        });
        </script>

        <?php if(mysqli_num_rows($cart_items) > 0): ?>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Tổng</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($item = mysqli_fetch_assoc($cart_items)): 
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total += $subtotal;
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="../../uploads/<?php echo $item['image']; ?>" 
                                                 class="cart-item-image me-3" 
                                                 alt="<?php echo $item['name']; ?>">
                                            <div>
                                                <h6 class="mb-0"><?php echo $item['name']; ?></h6>
                                                <small class="text-muted">Còn <?php echo $item['stock']; ?> sản phẩm</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo number_format($item['price']); ?>đ</td>
                                    <td>
                                        <input type="number" 
                                               class="form-control quantity-input"
                                               value="<?php echo $item['quantity']; ?>"
                                               min="1"
                                               max="<?php echo $item['stock']; ?>"
                                               onchange="updateQuantity(<?php echo $item['product_id']; ?>, this.value)">
                                    </td>
                                    <td><?php echo number_format($subtotal); ?>đ</td>
                                    <td>
                                        <a href="/client/cart/delete_cart.php?id=<?php echo $item['product_id']; ?>" 
                                           class="btn btn-sm btn-danger p-4"
                                           onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                            <i class="fas fa-trash fa-xl"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                                    <td><strong><?php echo number_format($total); ?>đ</strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>                    
                    <div class="d-flex justify-content-end mt-3">
                        <a href="/client/home/home.php" class="btn btn-outline-primary me-2">
                            <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
                        </a>
                        <a href="/client/cart/checkout.php" class="btn btn-primary">
                            Thanh toán <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <h4>Giỏ hàng trống</h4>
                <p class="text-muted">Bạn chưa có sản phẩm nào trong giỏ hàng</p>
                <a href="/client/home/home.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateQuantity(productId, quantity) {
            window.location.href = `/client/cart/cart.php?id=${productId}&quantity=${quantity}`;
        }
    </script>
</body>
</html>