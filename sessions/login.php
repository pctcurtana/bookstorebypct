<?php
session_start();
include '../includes/config.php';

$errors = array();

// Kiểm tra cookie trước
if(isset($_COOKIE['remember_user']) && isset($_COOKIE['remember_token'])) {
    $user_id = $_COOKIE['remember_user'];
    $token = $_COOKIE['remember_token'];
    
    // Kiểm tra admin
    $admin_query = "SELECT * FROM admin WHERE id = '$user_id' AND remember_token = '$token'";
    $admin_result = mysqli_query($conn, $admin_query);
   
    if(mysqli_num_rows($admin_result) == 1) {
        $admin = mysqli_fetch_assoc($admin_result);
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['username'] = $admin['username'];
        $_SESSION['is_admin'] = true;
        header("Location: /admin/dashboard/dashboard.php");
        exit();
    }
    
    // Kiểm tra user thường
    $user_query = "SELECT * FROM users WHERE id = '$user_id' AND remember_token = '$token'";
    $user_result = mysqli_query($conn, $user_query);
    
    if(mysqli_num_rows($user_result) == 1) {
        $user = mysqli_fetch_assoc($user_result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = false;
        header("Location: /client/home/home.php");
        exit();
    }
}

// Xử lý đăng nhập form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);
    $remember = isset($_POST['remember']);

    // Validate
    if (empty($username)) {
        $errors['username'] = "Vui lòng nhập tên đăng nhập";
    }
    if (empty($password)) {
        $errors['password'] = "Vui lòng nhập mật khẩu";
    }

    if (empty($errors)) {
        $hashed_password = md5($password);
        
        // Kiểm tra admin
        $admin_query = "SELECT * FROM admin WHERE username = '$username' AND password = '$hashed_password'";
        $admin_result = mysqli_query($conn, $admin_query);
        
        if (mysqli_num_rows($admin_result) == 1) {
            $admin = mysqli_fetch_assoc($admin_result);
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['is_admin'] = true;

            // Xử lý ghi nhớ đăng nhập
            if($remember) {
                $token = md5(uniqid(rand(), true));
                $user_id = $admin['id'];
                
                // Lưu token vào database
                mysqli_query($conn, "UPDATE admin SET remember_token = '$token' WHERE id = $user_id");
                
                // Set cookie với thời hạn 30 ngày
                setcookie('remember_user', $user_id, time() + (86400 * 30), '/');
                setcookie('remember_token', $token, time() + (86400 * 30), '/');
            }

            header("Location: /admin/dashboard/dashboard.php");
            exit();
        } else {
            // Kiểm tra user thường
            $user_query = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
            $user_result = mysqli_query($conn, $user_query);
            
            
            if (mysqli_num_rows($user_result) == 1) {
                $user = mysqli_fetch_assoc($user_result);


                if($user['is_active'] == 0) {
                    $_SESSION['error'] = "Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.";
                    header('location: ../sessions/login.php');
                    exit();
                }
                  

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = false;

                // Xử lý ghi nhớ đăng nhập
                if($remember) {
                    $token = md5(uniqid(rand(), true));
                    $user_id = $user['id'];
                    
                    // Lưu token vào database
                    mysqli_query($conn, "UPDATE users SET remember_token = '$token' WHERE id = $user_id");
                    
                    // Set cookie với thời hạn 30 ngày
                    setcookie('remember_user', $user_id, time() + (86400 * 30), '/');
                    setcookie('remember_token', $token, time() + (86400 * 30), '/');
                }

                header("Location: /client/home/home.php");
                exit();
            }  else {
                $errors['login'] = "Tên đăng nhập hoặc mật khẩu không chính xác";
            } 
            
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/alert.css">
    <script src="../assets/alert.js"></script>
    <style>
        .card {
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: none;
            padding: 20px;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
        }
        .btn-primary {
            padding: 10px;
        }
    </style>
</head>
<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div id="alertMessage"></div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center mb-0">Đăng nhập</h3>
                    </div>
                    <div class="card-body p-4">
                    <script>
                    <?php if (isset($errors['login'])): ?>
                        showAlert('<?php echo $errors['login']; ?>', 'danger', 'alertMessage');
                    <?php endif; ?>                   
                    <?php if(isset($_SESSION['error'])): ?>
                        showAlert('<?php echo $_SESSION['error']; ?>', 'danger', 'alertMessage');
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['success'])): ?>
                        showAlert('<?php echo $_SESSION['success']; ?>', 'success', 'alertMessage');
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>
                    </script>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
                            <!-- Username field -->
                            <div class="mb-3">
                                <label for="username" class="form-label">Tên đăng nhập</label>
                                <input type="text" 
                                       class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>" 
                                       id="username" 
                                       name="username" 
                                       value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>"
                                       placeholder="Nhập tên đăng nhập">
                                <?php if (isset($errors['username'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['username']; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Password field -->
                            <div class="mb-4">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" 
                                       class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                       id="password" 
                                       name="password"
                                       placeholder="Nhập mật khẩu">
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Remember me checkbox -->
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                            </div>

                            <!-- Submit button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Đăng nhập</button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <p class="mb-2">Chưa có tài khoản? <a href="/sessions/register.php">Đăng ký ngay</a></p>
                            <p><a href="/sessions/register.php">Quên mật khẩu?</a></p>
                        </div>
                    </div>
                </div>
                <!-- Social login buttons -->
                <div class="text-center mt-4">
                    <p class="text-muted">Hoặc đăng nhập với</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-outline-primary">
                            <i class="fab fa-facebook"></i> Facebook
                        </button>
                        <button class="btn btn-outline-danger">
                            <i class="fab fa-google"></i> Google
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></div>
</body>
</html>