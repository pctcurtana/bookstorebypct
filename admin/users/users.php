<?php
session_start();
include '../../includes/config.php';
// check đn và quyền ad
if(!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('location: login.php');
    exit();
}
// lấy ds user từ bảng user
$query = "SELECT * FROM users WHERE is_admin = 0 ORDER BY created_at DESC";
$users = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
   <title>Quản lý người dùng - Admin</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
   <link rel="stylesheet" href="../../assets/alert.css">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
   <script src="../../assets/alert.js"></script>
</head>
<body>      
   <div class="container-fluid">
       <div class="row">
           <?php include '../../includes/sidebar.php'; ?>
           <div class="col-md-9 col-lg-10 main-content">
               <div class="d-flex justify-content-between align-items-center mb-4">
                   <h2>Quản lý người dùng</h2>
               </div>
               <div id="alertMessage"></div>
               <?php if(isset($_SESSION['success'])): ?> 
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showAlert('<?php echo str_replace("'", "\\'", $_SESSION['success']); ?>', 'success');
                    });
                </script>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            <?php if(isset($_SESSION['error'])): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showAlert('<?php echo str_replace("'", "\\'", $_SESSION['error']); ?>', 'danger');
                    });
                </script>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
               <div class="table-responsive">
                   <table class="table table-hover">
                       <thead>
                           <tr>
                               <th>ID</th>
                               <th>Tên đăng nhập</th>
                               <th>Email</th>
                               <th>Ngày đăng ký</th>
                               <th>Trạng thái</th>
                               <th>Thao tác</th>
                           </tr>
                       </thead>
                       <tbody>
                           <?php while($user = mysqli_fetch_assoc($users)): ?>
                           <tr>
                               <td><?php echo $user['id']; ?></td>
                               <td><?php echo $user['username']; ?></td>
                               <td><?php echo $user['email']; ?></td>
                               <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                               <td>
                                   <?php if($user['is_active']): ?>
                                       <span class="badge bg-success">Hoạt động</span>
                                   <?php else: ?>
                                       <span class="badge bg-danger">Đã khóa</span>
                                   <?php endif; ?>
                               </td>
                               <td>
                                   <?php if($user['is_active']): ?>
                                       <a href="block_user.php?id=<?php echo $user['id']; ?>" 
                                          class="btn btn-sm btn-danger"
                                          onclick="return confirm('Bạn có chắc muốn khóa người dùng này?')">
                                           <i class="fas fa-ban"></i> Khóa
                                       </a>
                                   <?php else: ?>
                                       <a href="unblock_user.php?id=<?php echo $user['id']; ?>" 
                                          class="btn btn-sm btn-success"
                                          onclick="return confirm('Bạn có chắc muốn mở khóa người dùng này?')">
                                           <i class="fas fa-unlock"></i> Mở khóa
                                       </a>
                                   <?php endif; ?>
                               </td>
                           </tr>
                           <?php endwhile; ?>
                       </tbody>
                   </table>
               </div>
           </div>
       </div>
   </div>
</body>
</html>