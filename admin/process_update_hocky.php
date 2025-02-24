<?php
// process_update_hocky.php

// Kết nối với cơ sở dữ liệu
require_once 'connect.php';

// Kiểm tra xem dữ liệu có được gửi qua POST không
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy các giá trị từ form
    $id = $_POST['id'];
    $ten_hocky = $_POST['ten_hocky'];
    $nam_hoc = $_POST['nam_hoc'];
    $trang_thai = $_POST['trang_thai'];

    // Cập nhật thông tin học kỳ trong cơ sở dữ liệu
    $sql = "UPDATE hocky SET ten_hocky = ?, nam_hoc = ?, trang_thai = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssi", $ten_hocky, $nam_hoc, $trang_thai, $id);

        if (mysqli_stmt_execute($stmt)) {
            // Nếu cập nhật thành công, chuyển hướng về trang danh sách học kỳ
            header('Location: admin_dashboard.php?page_layout=hocky');
            exit();
        } else {
            echo "Có lỗi trong quá trình cập nhật học kỳ!";
        }

    } 
} 

// Đóng kết nối
mysqli_close($conn);
?>
