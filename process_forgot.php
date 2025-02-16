<?php
include('admin/connect.php');
require 'send_mail.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Kiểm tra email có tồn tại không
    $stmt = $conn->prepare("SELECT id FROM nguoidung WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $token = bin2hex(random_bytes(50)); // Tạo token ngẫu nhiên
        $stmt = $conn->prepare("UPDATE nguoidung SET reset_token = ?, reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        if (sendResetEmail($email, $token)) {
            echo "<script>
                    alert('đã gửi email reset password')
                </script>";
        } else {
            echo "<script>
                    alert('Lỗi gửi email!')
                </script>";
        }
    } else {
        echo "<script>
                alert('Email không tồn tại!')
            </script>";
    }
}
?>
