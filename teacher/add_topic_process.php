<?php
session_start();
include('connect.php');

// Kiểm tra nếu người dùng chưa đăng nhập hoặc không phải giảng viên
if (!isset($_SESSION['nguoidung']) || $_SESSION['nguoidung']['vaitro'] !== 'giangvien') {
    echo "<script>alert('Bạn không có quyền truy cập!'); window.location.href = '../login.html';</script>";
    exit();
}

$id_giangvien = $_SESSION['nguoidung']['id']; // Lấy ID của giảng viên từ session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten_detai = mysqli_real_escape_string($conn, $_POST['ten_detai']);
    $mo_ta = mysqli_real_escape_string($conn, $_POST['mo_ta']);

    // Thêm đề tài vào database với trạng thái mặc định là 'con_trong'
    $sql = "INSERT INTO detai_giangvien (id_giangvien, ten_de_tai, mo_ta, trang_thai) 
            VALUES ('$id_giangvien', '$ten_detai', '$mo_ta', 'con_trong')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Thêm đề tài thành công!'); window.location.href = 'teacher_dashboard.php?page_layout=topic';</script>";
    } else {
        echo "<script>alert('Có lỗi xảy ra! Vui lòng thử lại.'); window.history.back();</script>";
    }
}
?>
