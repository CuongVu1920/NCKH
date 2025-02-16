<?php
include('admin/connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ho_ten = trim($_POST['ho_ten']);
    $email = trim($_POST['email']);
    $password = $_POST['password']; // Không mã hóa theo yêu cầu
    $so_dien_thoai = trim($_POST['so_dien_thoai']);
    $vaitro = "sinhvien"; // Mặc định đăng ký là sinh viên
    $ma_so_nguoidung = trim($_POST['ma_so_nguoidung']);
    $ngay_sinh = $_POST['ngay_sinh'];

    // Kiểm tra email đã tồn tại chưa
    $stmt = $conn->prepare("SELECT id FROM nguoidung WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Email này đã được đăng ký!";
    } else {
        $stmt = $conn->prepare("INSERT INTO nguoidung (ma_so_nguoidung, ho_ten, email, matkhau, ngay_sinh, so_dien_thoai, vaitro) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $ma_so_nguoidung, $ho_ten, $email, $password, $ngay_sinh, $so_dien_thoai, $vaitro);
        
        if ($stmt->execute()) {
            header("Location: login.php?success=1");
            exit();
        } else {
            $error = "Đăng ký thất bại, thử lại!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-lg" style="width: 350px;">
        <h3 class="text-center">Đăng Ký</h3>
        <?php if (!empty($error)) echo "<p class='text-danger text-center'>$error</p>"; ?>
        <form action="register.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Mã số người dùng</label>
                <input type="text" name="ma_so_nguoidung" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Họ và Tên</label>
                <input type="text" name="ho_ten" class="form-control" required>
            </div>
            <div class="mb-3">
    <label class="form-label">Ngày sinh</label>
    <input type="date" name="ngay_sinh" class="form-control" required>
</div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <input type="text" name="so_dien_thoai" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Đăng Ký</button>
        </form>
        <p class="text-center mt-2"><a href="login.html">Đã có tài khoản? Đăng nhập</a></p>
    </div>
</body>

</html>