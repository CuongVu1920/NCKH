<?php
    include('connect.php');
    session_start();

    if (!isset($_SESSION['nguoidung']) || $_SESSION['nguoidung']['vaitro'] != 'sinhvien') {
        echo "<script>alert('Bạn không có quyền truy cập!'); window.location.href='student_dashboard.php';</script>";
        exit();
    }

    $student_id = $_SESSION['nguoidung']['id'];
    $topic_id = $_GET['id'];

    // Kiểm tra xem đề tài có tồn tại không
    $sql_gv = "SELECT id_giangvien FROM detai_giangvien WHERE id = '$topic_id' AND trang_thai = 'con_trong'";
    $result_gv = mysqli_query($conn, $sql_gv);
    $row_gv = mysqli_fetch_assoc($result_gv);

    if (!$row_gv) {
        echo "<script>alert('Đề tài không hợp lệ hoặc đã được chọn!'); window.location.href='student_dashboard.php?page_layout=list_topic';</script>";
        exit();
    }

    $giangvien_id = $row_gv['id_giangvien'];

    // Kiểm tra nếu sinh viên đã có đề tài được duyệt hoặc đang chờ duyệt
    $sql_check = "SELECT id FROM chondetai WHERE id_sinhvien = '$student_id' AND trang_thai NOT IN ('tu_choi')";
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
        echo "<script>alert('Bạn đã chọn đề tài, vui lòng chờ xét duyệt!'); window.location.href='student_dashboard.php?page_layout=list_topic';</script>";
        exit();
    }

    // Lưu vào bảng chondetai
    $sql_insert = "INSERT INTO chondetai (id_sinhvien, id_giangvien, id_detai, trang_thai) VALUES ('$student_id', '$giangvien_id', '$topic_id', 'cho_duyet')";
    if (mysqli_query($conn, $sql_insert)) {
        echo "<script>alert('Chọn đề tài thành công, vui lòng chờ xét duyệt!'); window.location.href='student_dashboard.php?page_layout=list_topic';</script>";
    } else {
        echo "<script>alert('Lỗi chọn đề tài!'); window.location.href='student_dashboard.php?page_layout=list_topic';</script>";
    }

    mysqli_close($conn);
?>
