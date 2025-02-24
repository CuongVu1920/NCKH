<?php
include('connect.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Lấy id sinh viên từ session
$id_sinhvien = $_SESSION['nguoidung']['id']; 

// Kiểm tra xem sinh viên đã có giảng viên hướng dẫn chưa
$sql_check = "SELECT id FROM huongdan WHERE id_sinhvien = $id_sinhvien";
$result_check = mysqli_query($conn, $sql_check);

if (mysqli_num_rows($result_check) > 0) {
    // Nếu đã có giảng viên, hiển thị thông báo
    echo "<script>alert('Bạn đã có giảng viên hướng dẫn'); window.location.href = 'student_dashboard.php?page_layout=student_info';</script>";
    exit();
}

// Lặp qua các nguyện vọng và gửi dữ liệu vào bảng nguyenvong
foreach ($_POST as $key => $value) {
    if (strpos($key, 'nguyen_vong_') === 0 && $value != '0') {
        $id_giangvien = str_replace('nguyen_vong_', '', $key);
        $muc_uu_tien = $value;
        
        // Chèn vào bảng nguyenvong
        $sql_insert = "INSERT INTO nguyenvong (id_sinhvien, id_giangvien, muc_uu_tien, trangthai) 
                       VALUES (?, ?, ?, 'Chờ duyệt')";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iis", $id_sinhvien, $id_giangvien, $muc_uu_tien);
        $stmt_insert->execute();
    }
}

header("Location: student_dashboard.php?page_layout=student_info&status=success");
exit();
?>
