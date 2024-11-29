<?php
session_start();
include '../includes/config.php';
$errors = array();
$success = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // lấy data từ form
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    // check username
    if (empty($username)) {
        $errors['username'] = "Vui lòng nhập tên đăng nhập";
    } elseif (strlen($username) < 3) {
        $errors['username'] = "Tên đăng nhập phải có ít nhất 3 ký tự";
    } elseif (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        $errors['username'] = "Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới";
    }
    // ktra username đã có
    $check_username = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($check_username) > 0) {
        $errors['username'] = "Tên đăng nhập đã tồn tại";
    }
    // check email
    if (empty($email)) {
        $errors['email'] = "Vui lòng nhập email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email không hợp lệ";
    }
    // ktra email đã có
    $check_email = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check_email) > 0) {
        $errors['email'] = "Email đã được sử dụng";
    }
    // check password
    if (empty($password)) {
        $errors['password'] = "Vui lòng nhập mật khẩu";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Mật khẩu phải có ít nhất 6 ký tự";
    }
    // check xác nhận mk
    if (empty($confirm_password)) {
        $errors['confirm_password'] = "Vui lòng xác nhận mật khẩu";
    } elseif ($password != $confirm_password) {
        $errors['confirm_password'] = "Mật khẩu xác nhận không khớp";
    }
    // nếu k lỗi thì đằng kí
    if (empty($errors)) {
        $hashed_password = md5($password); 
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";      
        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ."; // Thay đổi này
            header('location: ../sessions/login.php');
            exit();
        } else {
            $errors['db'] = "Có lỗi xảy ra: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Đăng ký tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center mb-0">Đăng ký tài khoản</h3>
                    </div>
                    <div class="card-body">
                    <div id="alertMessage"></div>                  
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
                            <div class="mb-3">
                                <label for="username" class="form-label">Tên đăng nhập</label>
                                <input type="text" class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>" 
                                       id="username" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
                                <?php if (isset($errors['username'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['username']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                       id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                       id="password" name="password">
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                                <input type="password" class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>" 
                                       id="confirm_password" name="confirm_password">
                                <?php if (isset($errors['confirm_password'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['confirm_password']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Đăng ký</button>
                            </div>
                        </form>
                        <div class="text-center mt-3">
                            <p>Đã có tài khoản? <a href="../sessions/login.php">Đăng nhập ngay</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>