<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .product-card {
            border: none;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        
        /* Style cho ảnh sản phẩm */
        .product-image-wrapper {
            position: relative;
            width: 100%;
            padding-top: 133%; /* Tỷ lệ 3:4 */
            overflow: hidden;
        }
        
        .product-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        
        /* Style cho phần nội dung */
        .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 1rem;
        }
        
        .card-title {
            font-size: 1rem;
            font-weight: 500;
            height: 2.4em;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            margin-bottom: 0.5rem;
        }
        
        .card-text {
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .button-group {
            margin-top: auto;
        }
        
        .btn:last-child {
            margin-bottom: 0;
        }

        .cart-item-image {
            width: 100px;
            height: 150px;
            object-fit: cover;
        }
        .quantity-input {
            width: 70px;
        }
    </style>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/client/home/home.php">
            <i class="fa-solid fa-store"></i> Cửa hàng sách
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/client/home/home.php">
                        <i class="fa-solid fa-house"></i> Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/client/cart/cart.php">
                            <i class="fas fa-shopping-cart"></i> Giỏ hàng
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/client/orders/orders.php">
                        <i class="fa-solid fa-cube"></i> Đơn hàng của tôi
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="text-white me-3">Xin chào, <?php echo $_SESSION['username']; ?></span>
                    <a href="../../sessions/logout.php" class="btn btn-outline-light">Đăng xuất</a>
                </div>
            </div>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>