<?php
include('admin/connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Kiểm tra token hợp lệ
    $stmt = $conn->prepare("SELECT id FROM nguoidung WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id);
        $stmt->fetch();
        $stmt->close();

        // Cập nhật mật khẩu và xóa token
        $stmt = $conn->prepare("UPDATE nguoidung SET matkhau = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
        $stmt->bind_param("si", $password, $id);
        if ($stmt->execute()) {
            echo "Mật khẩu đã được cập nhật!";
        } else {
            echo "Lỗi cập nhật!";
        }
    } else {
        echo "Token không hợp lệ hoặc đã hết hạn!";
    }
}
?>
