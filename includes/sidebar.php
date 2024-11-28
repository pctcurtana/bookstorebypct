<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    .sidebar {
        min-height: 100vh;
        background: #343a40;
        color: white;
        width: 210px;
        
    }
    .sidebar .nav-link {
        color: white;
        padding: 15px 20px;
    }
    .sidebar .nav-link:hover {
        background: #495057;
    }
    .sidebar .nav-link.active {
        background: #0d6efd;
    }
    .main-content {
        padding: 20px;
    }
    .stat-card {
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .table-responsive {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
</style>
   <div class="col-md-3 col-lg-2 px-0 sidebar">
        <div class="d-flex flex-column">
            <div class="text-center p-4">
                <h4>Admin Panel</h4>
            </div>
            <ul class="nav flex-column ">
            <li class="nav-item">
                <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>" href="/admin/dashboard/dashboard.php">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'orders.php') ? 'active' : ''; ?>" href="/admin/orders/orders.php">
                    <i class="fas fa-shopping-cart me-2"></i> Đơn hàng
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'users.php') ? 'active' : ''; ?>" href="/admin/users/users.php">
                    <i class="fas fa-users me-2"></i> Người dùng
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/sessions/logout.php">
                    <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                </a>
            </li>
        </ul>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>