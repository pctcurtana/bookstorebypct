<?php
session_start();
include '../../includes/config.php';

// Kiểm tra đăng nhập và quyền admin
if(!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('location: ../login.php');
    exit();
}

$errors = array();
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $price = floatval($_POST['price']);
    $author = mysqli_real_escape_string($conn, trim($_POST['author']));
    $publisher = mysqli_real_escape_string($conn, trim($_POST['publisher']));
    $category = mysqli_real_escape_string($conn, trim($_POST['category']));
    $stock = intval($_POST['stock']);

    // Validate
    if(empty($name)) {
        $errors['name'] = "Vui lòng nhập tên sách";
    }
    if(empty($price)) {
        $errors['price'] = "Vui lòng nhập giá sách";
    }
    if(empty($author)) {
        $errors['author'] = "Vui lòng nhập tên tác giả";
    }
    if($_FILES['image']['error'] == 4) {
        $errors['image'] = "Vui lòng chọn ảnh sách";
    }

    // Xử lý upload ảnh
    if(empty($errors)) {
        $file = $_FILES['image'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_type = $file['type'];
        
        // Kiểm tra loại file
        $allowed_types = array('image/jpeg', 'image/png', 'image/gif');
        if(!in_array($file_type, $allowed_types)) {
            $errors['image'] = "Chỉ chấp nhận file ảnh (JPG, PNG, GIF)";
        } else {
            // Tạo tên file mới để tránh trùng lặp
            $new_file_name = uniqid() . '-' . $file_name;
            $upload_path = '../../uploads/' . $new_file_name;
            
            // Upload file
            if(move_uploaded_file($file_tmp, $upload_path)) {
                // Thêm sách vào database
                $query = "INSERT INTO products (name, description, price, image, author, publisher, category, stock) 
                         VALUES ('$name', '$description', $price, '$new_file_name', '$author', '$publisher', '$category', $stock)";
                
                if(mysqli_query($conn, $query)) {
                    $success = "Thêm sách thành công!";
                    // Reset form
                    $_POST = array();
                } else {
                    $errors['db'] = "Lỗi: " . mysqli_error($conn);
                    // Xóa file ảnh nếu thêm DB thất bại
                    unlink($upload_path);
                }
            } else {
                $errors['image'] = "Không thể upload ảnh";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thêm Sách Mới - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/alert.css">
    <script src="../../assets/alert.js"></script>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">Thêm Sách Mới</h3>
                        <a href="/admin/dashboard/dashboard.php" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                    <div class="card-body">
                        <div id="alertMessage"></div>
                        <?php if($success): ?> 
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    showAlert('<?php echo $success; ?>', 'success');
                                });
                            </script>
                        <?php endif; ?>
                        
                        <?php if(isset($errors['db'])): ?>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    showAlert('<?php echo $errors['db']; ?>', 'danger');
                                });
                            </script>
                        <?php endif; ?>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Tên sách *</label>
                                <input type="text" name="name" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" 
                                       value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>">
                                <?php if(isset($errors['name'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea name="description" class="form-control" rows="4"><?php echo isset($_POST['description']) ? $_POST['description'] : ''; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Giá bán *</label>
                                <input type="number" name="price" class="form-control <?php echo isset($errors['price']) ? 'is-invalid' : ''; ?>"
                                       value="<?php echo isset($_POST['price']) ? $_POST['price'] : ''; ?>">
                                <?php if(isset($errors['price'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['price']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ảnh sách *</label>
                                <input type="file" name="image" class="form-control <?php echo isset($errors['image']) ? 'is-invalid' : ''; ?>">
                                <?php if(isset($errors['image'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['image']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tác giả *</label>
                                <input type="text" name="author" class="form-control <?php echo isset($errors['author']) ? 'is-invalid' : ''; ?>"
                                       value="<?php echo isset($_POST['author']) ? $_POST['author'] : ''; ?>">
                                <?php if(isset($errors['author'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['author']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nhà xuất bản</label>
                                <input type="text" name="publisher" class="form-control"
                                       value="<?php echo isset($_POST['publisher']) ? $_POST['publisher'] : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Thể loại</label>
                                <select name="category" class="form-control">
                                    <option value="Văn học">Văn học</option>
                                    <option value="Kinh tế">Kinh tế</option>
                                    <option value="Kỹ năng sống">Kỹ năng sống</option>
                                    <option value="Tâm lý">Tâm lý</option>
                                    <option value="Thiếu nhi">Thiếu nhi</option>
                                    <option value="Giáo khoa">Giáo khoa</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Số lượng trong kho</label>
                                <input type="number" name="stock" class="form-control" value="0">
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Thêm sách</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>