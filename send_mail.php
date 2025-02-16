<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Nếu dùng Composer
// require 'PHPMailer/PHPMailer.php'; // Nếu tải thủ công

function sendResetEmail($email, $token)
{
    $mail = new PHPMailer(true);
    try {
        // Cấu hình SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // SMTP của Gmail
        $mail->SMTPAuth   = true;
        $mail->Username   = 'vucuong10a12cmb1920@gmail.com'; // Thay bằng email của bạn
        $mail->Password   = 'pssv rdhp xgkn yoau'; // Thay bằng mật khẩu ứng dụng
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        // Gửi email
        $mail->setFrom('vucuong10a12cmb1920@gmail.com', 'Hỗ trợ hệ thống');
        $mail->addAddress($email);

        // Nội dung email
        $mail->isHTML(true);
        $mail->Subject = 'Reset Password ';
        $mail->Body    = "Nhấn vào <a href='http://localhost/test1/reset_password.php?token=$token'>đây</a> để đặt lại mật khẩu.";
        $mail->AltBody = "Nhấn vào link sau để đặt lại mật khẩu: http://localhost/test1/reset_password.php?token=$token";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-lg" style="width: 350px;">
        <h3 class="text-center">Quên mật khẩu</h3>
        <form action="process_forgot.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Email của bạn</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <?php if (!empty($message)) echo $message; ?>
            <button type="submit" class="btn btn-primary w-100">Gửi yêu cầu</button>
        </form>
    </div>
</body>

</html>