<?php
session_start();
include '../../includes/config.php';
include '../../includes/header.php';
// check đn và phải user k
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin']) {
    header('location: ../../sessions/login.php');
    exit();
}
// ktra và lấy tt sách
if (!isset($_GET['id'])) {
    header('location: /client/home/home.php');
    exit();
}
$book_id = intval($_GET['id']);
$query = "SELECT * FROM products WHERE id = $book_id";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
    header('location: /client/home/home.php');
    exit();
}
$book = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($book['name']); ?> - Chi tiết sách</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/alert.css">
    <script src="../../assets/alert.js"></script>
    <style>
        .book-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .book-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .price {
            font-size: 1.5rem;
            color: #dc3545;
            font-weight: bold;
        }
        .stock-status {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.9rem;
            display: inline-block;
        }
        .in-stock {
            background: #d4edda;
            color: #155724;
        }
        .out-of-stock {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body class="bg-light">
    <div id="alertMessage"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php if (isset($_SESSION['success'])): ?>
                showAlert(`<?php echo str_replace("'", "\\'", $_SESSION['success']); ?>`, 'success');
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
        });
    </script>
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home.php">Trang chủ</a></li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars($book['name']); ?></li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-md-4 mb-4">
                <img src="../../uploads/<?php echo htmlspecialchars($book['image']); ?>" 
                     class="book-image" 
                     alt="<?php echo htmlspecialchars($book['name']); ?>">
            </div>
            <div class="col-md-8">
                <div class="book-info">
                    <h1 class="h2 mb-3"><?php echo htmlspecialchars($book['name']); ?></h1>
                    <div class="mb-3">
                        <span class="price"><?php echo number_format($book['price']); ?>đ</span>
                        <span class="stock-status <?php echo $book['stock'] > 0 ? 'in-stock' : 'out-of-stock'; ?> ms-3">
                            <?php echo $book['stock'] > 0 ? 'Còn hàng' : 'Hết hàng'; ?>
                        </span>
                    </div>
                    <div class="mb-4">
                        <p><strong>Tác giả:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                        <p><strong>Nhà xuất bản:</strong> <?php echo htmlspecialchars($book['publisher']); ?></p>
                        <p><strong>Thể loại:</strong> <?php echo htmlspecialchars($book['category']); ?></p>
                    </div>
                    <div class="mb-4">
                        <h5>Mô tả sách:</h5>
                        <p class="text-muted"><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
                    </div>
                    <?php if ($book['stock'] > 0): ?>
                        <div class="d-grid gap-2">
                            <a href="add_to_cart.php?id=<?php echo $book['id']; ?>" 
                               class="btn btn-primary">
                                <i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            Sản phẩm hiện đang hết hàng
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
