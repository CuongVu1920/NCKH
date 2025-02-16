<?php
include('connect.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM detai_giangvien WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Xóa đề tài thành công!'); window.location.href='teacher_dashboard.php?page_layout=topic';</script>";
    } else {
        echo "<script>alert('Lỗi khi xóa đề tài!'); window.location.href='teacher_dashboard.php?page_layout=topic';</script>";
    }
} else {
    echo "<script>alert('Không tìm thấy ID đề tài!'); window.location.href='teacher_dashboard.php?page_layout=topic';</script>";
}

mysqli_close($conn);
?>
