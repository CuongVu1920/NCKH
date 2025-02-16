<?php 
include 'connect.php';

$id = $_GET['id'];

// Lấy vai trò (vaitro) của người dùng trước khi xóa
$sql_get_role = "SELECT vaitro FROM nguoidung WHERE id = '$id'";
$result = mysqli_query($conn, $sql_get_role);
$row = mysqli_fetch_assoc($result);

if ($row) {
    $role = $row['vaitro'];

    // Xóa người dùng
    $sql_delete = "DELETE FROM `nguoidung` WHERE id = '$id'";
    mysqli_query($conn, $sql_delete);

    // Chuyển hướng theo vai trò
    if ($role == "giangvien") {
        header("Location: admin_dashboard.php?page_layout=teacher_list");
    } elseif ($role == "sinhvien") {
        header("Location: admin_dashboard.php?page_layout=student_list");
    } 
} 

mysqli_close($conn);
?>
