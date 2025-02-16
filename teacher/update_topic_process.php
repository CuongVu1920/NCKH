<?php
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $ten_de_tai = mysqli_real_escape_string($conn, $_POST['ten_detai']);
    $mo_ta = mysqli_real_escape_string($conn, $_POST['mo_ta']);
    $trang_thai = mysqli_real_escape_string($conn, $_POST['trang_thai']);

    $sql = "UPDATE detai_giangvien SET ten_de_tai='$ten_de_tai', mo_ta='$mo_ta', trang_thai='$trang_thai' WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Cập nhật đề tài thành công!'); window.location.href='teacher_dashboard.php?page_layout=topic';</script>";
        exit();
    } else {
        echo "<script>alert('Lỗi khi cập nhật!'); window.location.href='teacher_dashboard.php?page_layout=edit_topic&id=$id';</script>";
    }
}

mysqli_close($conn);
?>
