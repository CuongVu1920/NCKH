<?php
session_start();
include('admin/connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM nguoidung WHERE email = ? AND trangthai = 'HoatDong'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if ($password===$user['matkhau']) {
            $_SESSION['nguoidung'] = [
                'id' => $user['id'],
                'ho_ten' => $user['ho_ten'],
                'email' => $user['email'],
                'vaitro' => $user['vaitro']
            ];
            // Điều hướng theo vai trò
            switch ($user['vaitro']) {
                case 'quantri':
                    header('Location: admin/admin_dashboard.php');
                    break;
                case 'sinhvien':
                    header('Location: student/student_dashboard.php');
                    break;
                case 'giangvien':
                    header('Location: teacher/teacher_dashboard.php');
                    break;
            }
            exit();
        } else {
            $error = "Mật khẩu không chính xác!";
        }
    } else {
        $error = "Email không tồn tại hoặc tài khoản bị khóa!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<style>
    .container {
        width: 800px;
    }

    .img-login {
        display: inline-block;
        border-radius: 10px;
        margin-right: 20px;
    }

</style>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-lg d-flex flex-row" class="container">
        <!-- Cột hình ảnh -->
        <div class="col-md-6 d-flex align-items-center justify-content-center">
            <img src="./assest/img/login-img.jpg" alt="Login Image" class="img-login" style="max-width: 100%; height: auto;">
        </div>
        
        <!-- Cột form đăng nhập -->
        <div class="col-md-6 p-3" style="padding-left: 20px;">
            <h3 class="text-center">Đăng Nhập</h3>
            <?php if (!empty($error)) echo "<p class='text-danger text-center'>$error</p>"; ?>
            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Đăng Nhập</button>
            </form>
            <p class="mt-2 send-email" ><a href="send_mail.php">Quên Mật Khẩu</a></p>
        </div>
    </div>
</body>
</html>
