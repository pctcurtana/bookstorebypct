<?php
session_start();
include 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Store - Trang chủ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('assets/bookhome.jpg');
            background-size: cover;
            background-position: center;
            height: 500px;
            display: flex;
            align-items: center;
            color: white;
            margin-bottom: 2rem;
        }
        .product-card {
            height: 100%;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            box-shadow: 
            10px 10px 20px rgba(0, 0, 0, 0.2), 
            -10px -10px 20px rgba(255, 255, 255, 0.7), 
            0 5px 15px rgba(0, 0, 0, 0.1);
            /* overflow: hidden; */
            transition: transform 0.5s;
        }
        .product-card:hover {
            transform: scale(1.05);
         
        }
        .product-image-wrapper {
            position: relative;
            padding-top: 100%;
            overflow: hidden;
        }
        .product-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .card-body {
            padding: 1rem;
        }
        .card-title {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            height: 2.4rem;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        .button-group {
            margin-top: 1rem;
        }
        .feature-section {
            background-color: #f8f9fa;
            padding: 4rem 0;
            margin: 2rem 0;
        }
        .feature-icon {
            width: 60px;
            height: 60px;
            background-color: #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }
        .feature-icon i {
            font-size: 24px;
            color: #0d6efd;
        }
        footer {
            background-color: #212529;
            color: white;
            padding: 3rem 0 1rem;
            margin-top: 3rem;
        }
        .social-links a {
            color: white;
            margin-right: 1rem;
            font-size: 1.2rem;
        }
        .social-links a:hover {
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand text-white" href="#">
                <i class="fas fa-book-reader text-white"></i> Book Store
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                
                </ul>
                <div class="d-flex">
                    <a href="sessions/login.php" class="btn btn-outline-light me-2">Đăng nhập</a>
                    <a href="sessions/register.php" class="btn btn-outline-light">Đăng ký</a>
                </div>
            </div>
        </div>
    </nav>
    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Khám phá thế giới sách</h1>
                    <p class="lead mb-4">Hàng nghìn đầu sách chất lượng đang chờ đón bạn</p>
                    <a href="sessions/register.php" class="btn btn-primary btn-lg">Đăng ký ngay</a>
                </div>
            </div>
        </div>
    </section>
    <div class="container">
        <h2 class="mb-4">Sách nổi bật</h2>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php
            $query = "SELECT * FROM products ORDER BY created_at DESC LIMIT 8";
            $products = mysqli_query($conn, $query);
            while($product = mysqli_fetch_assoc($products)):
            ?>
            <div class="col">
                <div class="product-card">
                    <div class="product-image-wrapper">
                        <img src="uploads/<?php echo $product['image']; ?>" 
                             class="product-image" 
                             alt="<?php echo $product['name']; ?>">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $product['name']; ?></h5>
                        <p class="card-text text-danger fw-bold"><?php echo number_format($product['price']); ?>đ</p>
                        <div class="button-group">
                            <a href="sessions/login.php" class="btn btn-primary w-100 mb-2">
                                Xem chi tiết
                            </a>
                            <a href="sessions/login.php" class="btn btn-outline-primary w-100">
                                <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <section class="feature-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 text-center">
                    <div class="feature-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h5>Giao hàng miễn phí</h5>
                    <p class="text-muted">Cho đơn hàng từ 300.000đ</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="feature-icon">
                        <i class="fas fa-undo"></i>
                    </div>
                    <h5>Đổi trả dễ dàng</h5>
                    <p class="text-muted">30 ngày đổi trả miễn phí</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h5>Hỗ trợ 24/7</h5>
                    <p class="text-muted">Luôn sẵn sàng hỗ trợ bạn</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="feature-icon">
                        <i class="fa-solid fa-gift"></i>
                    </div>
                    <h5>Mua 1 tặng 1</h5>
                    <p class="text-muted">Tính tiền 2</p>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h5>Về Book Store</h5>
                    <p class="text-muted">Chúng tôi là nhà sách trực tuyến với hàng nghìn đầu sách chất lượng.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <h5>Liên kết nhanh</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Về chúng tôi</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Chính sách bảo mật</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Điều khoản sử dụng</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Liên hệ</h5>
                    <ul class="list-unstyled text-muted">
                        <li><i class="fas fa-map-marker-alt me-2"></i> 123 Đường Nguyễn Văn Linh, Quận Ninh Kiều, TP.CT</li>
                        <li><i class="fas fa-phone me-2"></i> (84) 857120003</li>
                        <li><i class="fas fa-envelope me-2"></i> phamthat2206@gmail.com</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center text-muted">
                <small>&copy; 2024 Book Store. All rights reserved.</small>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>