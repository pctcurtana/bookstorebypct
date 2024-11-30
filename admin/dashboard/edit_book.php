<?php
session_start();
include '../../includes/config.php';
// check đn và quyền ad
if(!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('location: ../login.php');
    exit();
}
$errors = array();
$success = '';
// lấy tt sách cần sửa
if(isset($_GET['id'])) {
    $book_id = intval($_GET['id']);
    $query = "SELECT * FROM products WHERE id = $book_id";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) == 0) {
        header('location: /admin/dashboard/dashboard.php');
        exit();
    }
    $book = mysqli_fetch_assoc($result);
} else {
    header('location: /admin/dashboard/dashboard.php');
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // lấy data từ form
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $price = floatval($_POST['price']);
    $author = mysqli_real_escape_string($conn, trim($_POST['author']));
    $publisher = mysqli_real_escape_string($conn, trim($_POST['publisher']));
    $category = mysqli_real_escape_string($conn, trim($_POST['category']));
    $stock = intval($_POST['stock']);
    // check
    if(empty($name)) $errors['name'] = "Vui lòng nhập tên sách";
    if(empty($price)) $errors['price'] = "Vui lòng nhập giá sách";
    if(empty($author)) $errors['author'] = "Vui lòng nhập tên tác giả";
    // xử lý up ảnh mới nếu có
    $image_update = "";
    if($_FILES['image']['error'] != 4) {
        $file = $_FILES['image'];
        $allowed_types = array('image/jpeg', 'image/png', 'image/gif');      
        if(!in_array($file['type'], $allowed_types)) {
            $errors['image'] = "Chỉ chấp nhận file ảnh (JPG, PNG, GIF)";
        } else {
            $new_file_name = uniqid() . '-' . $file['name'];
            $upload_path = '../../uploads/' . $new_file_name;          
            if(move_uploaded_file($file['tmp_name'], $upload_path)) {
                // xoá ảnh cũ
                if(file_exists('../../uploads/' . $book['image'])) {
                    unlink('../../uploads/' . $book['image']);
                }
                $image_update = ", image = '$new_file_name'";
            } else {
                $errors['image'] = "Không thể upload ảnh";
            }
        }
    }
    // update vào db
    if(empty($errors)) {
        $query = "UPDATE products SET 
                 name = '$name',
                 description = '$description',
                 price = $price,
                 author = '$author',
                 publisher = '$publisher',
                 category = '$category',
                 stock = $stock" . 
                 $image_update . 
                 " WHERE id = $book_id";
        if(mysqli_query($conn, $query)) {
            $success = "Cập nhật sách thành công!";
            // update lại tt sách
            $book = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id = $book_id"));
        } else {
            $errors['db'] = "Lỗi: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sửa Thông Tin Sách - Admin</title>
    <link rel="icon" href="/assets/headicon.png " type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/alert.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/alert.js"></script>
    <style>
        .current-image {
            max-width: 200px;
            margin: 10px 0;
        }
    </style>
</head>
<body>   
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">Sửa Thông Tin Sách</h3>
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
                            <!-- Form fields giống add_book.php nhưng đã điền sẵn giá trị -->
                            <div class="mb-3">
                                <label class="form-label">Tên sách *</label>
                                <input type="text" name="name" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" 
                                       value="<?php echo $book['name']; ?>">
                                <?php if(isset($errors['name'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea name="description" class="form-control" rows="4"><?php echo $book['description']; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Giá bán *</label>
                                <input type="number" name="price" class="form-control <?php echo isset($errors['price']) ? 'is-invalid' : ''; ?>"
                                       value="<?php echo $book['price']; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ảnh sách hiện tại</label>
                                <img src="../../uploads/<?php echo $book['image']; ?>" class="current-image">
                                <input type="file" name="image" class="form-control mt-2">
                                <small class="text-muted">Chỉ chọn ảnh nếu muốn thay đổi</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tác giả *</label>
                                <input type="text" name="author" class="form-control" value="<?php echo $book['author']; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nhà xuất bản</label>
                                <input type="text" name="publisher" class="form-control" value="<?php echo $book['publisher']; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Thể loại</label>
                                <select name="category" class="form-control">
                                    <?php
                                    $categories = array('Văn học', 'Kinh tế', 'Kỹ năng sống', 'Tâm lý', 'Thiếu nhi', 'Giáo khoa');
                                    foreach($categories as $cat):
                                    ?>
                                    <option value="<?php echo $cat; ?>" <?php echo ($book['category'] == $cat) ? 'selected' : ''; ?>>
                                        <?php echo $cat; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Số lượng trong kho</label>
                                <input type="number" name="stock" class="form-control" value="<?php echo $book['stock']; ?>">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Cập nhật sách</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>