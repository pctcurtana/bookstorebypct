<?php
session_start();
include '../../includes/config.php';
// check đăg nhập và quyền ad
if(!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('location: ../sessions/login.php');
    exit();
}
// lấy t.kê
$total_books = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM products"))['count'];
$total_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders"))['count'];
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users"))['count'];
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as total FROM orders"))['total'];
// lấy ds sách mới nhất
$latest_books = mysqli_query($conn, "SELECT * FROM products ORDER BY created_at");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Quản lý sách</title>
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
                    <h2>Dashboard</h2>
                    <div>
                        <span>Xin chào, <?php echo $_SESSION['username']; ?></span>
                    </div>
                </div>
                <div id="alertMessage"></div>
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-card bg-primary text-white">
                            <h3><?php echo $total_books; ?></h3>
                            <p class="mb-0">Tổng số sách</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-success text-white">
                            <h3><?php echo $total_orders; ?></h3>
                            <p class="mb-0">Đơn hàng</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-warning text-white">
                            <h3><?php echo $total_users; ?></h3>
                            <p class="mb-0">Người dùng</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-info text-white">
                            <h3><?php echo number_format($total_revenue); ?>đ</h3>
                            <p class="mb-0">Doanh thu</p>
                        </div>
                    </div>
                </div>
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
                <div class="table-responsive">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4>Sách mới nhất</h4>
                        <a href="add_book.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm sách mới
                        </a>
                    </div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên sách</th>
                                <th>Giá</th>
                                <th>Ngày thêm</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($book = mysqli_fetch_assoc($latest_books)): ?>
                            <tr>
                                <td><?php echo $book['id']; ?></td>
                                <td><?php echo $book['name']; ?></td>
                                <td><?php echo number_format($book['price']); ?>đ</td>
                                <td><?php echo date('d/m/Y', strtotime($book['created_at'])); ?></td>
                                <td>
                                    <a href="edit_book.php?id=<?php echo $book['id']; ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="delete_book.php?id=<?php echo $book['id']; ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Bạn có chắc muốn xóa sách này?')">
                                        <i class="fas fa-trash"></i>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>