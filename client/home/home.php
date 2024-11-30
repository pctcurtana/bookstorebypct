<?php
session_start();
include '../../includes/config.php';
include '../../includes/header.php';

// check đn và phải user k
if(!isset($_SESSION['user_id']) || $_SESSION['is_admin']) {
    header('location: ../../sessions/login.php');
    exit();
}
// lấy sách từ db
$query = "SELECT * FROM products ORDER BY created_at DESC";
$products = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Trang chủ - Cửa hàng sách</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/alert.css">
    <script src="../../assets/alert.js"></script>
    
</head>
<body>  
    <div class="container py-4">
        <div id="alertMessage"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        <?php if(isset($_SESSION['success'])): ?>
            showAlert(`<?php echo str_replace("'", "\\'", $_SESSION['success']); ?>`, 'success');
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
            showAlert(`<?php echo str_replace("'", "\\'", $_SESSION['error']); ?>`, 'danger');
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    });
    </script>
        <h2 class="mb-4">Sách mới nhất</h2>    
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php while($product = mysqli_fetch_assoc($products)): ?>
            <div class="col">
                <div class="product-card">
                    <div class="product-image-wrapper">
                        <img src="../../uploads/<?php echo $product['image']; ?>" 
                            class="product-image" 
                            alt="<?php echo $product['name']; ?>">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $product['name']; ?></h5>
                        <p class="card-text text-danger fw-bold"><?php echo number_format($product['price']); ?>đ</p>
                        <div class="button-group">
                            <a href="product_detail.php?id=<?php echo $product['id']; ?>" 
                            class="btn btn-primary w-100 mb-2">
                                Xem chi tiết
                            </a>
                            <a href="add_to_cart.php?id=<?php echo $product['id']; ?>" 
                            class="btn btn-outline-primary w-100">
                                <i class="fas fa-cart-plus w-1"></i> Thêm vào giỏ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>  
</body>
</html>

