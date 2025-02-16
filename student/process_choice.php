<?php
include('connect.php');
session_start();

if (!isset($_SESSION['nguoidung']['id'])) {
    die("Bạn cần đăng nhập để thực hiện thao tác này.");
}
$student_id = $_SESSION['nguoidung']['id'];


// Kiểm tra nếu request là POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy danh sách giảng viên được chọn
    $selected_teachers = isset($_POST['teachers']) ? $_POST['teachers'] : [];

    // Kiểm tra số lượng nguyện vọng (tối đa 3)
    if (count($selected_teachers) > 3) {
        die("Bạn chỉ được chọn tối đa 3 giảng viên.");
    }
    
    // Xóa các lựa chọn cũ của sinh viên để tránh bị trùng
    $delete_sql = "DELETE FROM huongdan WHERE id_sinhvien = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    
    // Thêm mới các giảng viên đã chọn
    foreach ($selected_teachers as $teacher_id) {
        $insert_sql = "INSERT INTO huongdan (id_sinhvien, id_giangvien, trang_thai) VALUES (?, ?, 'cho_duyet')";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ii", $student_id, $teacher_id);
        $stmt->execute();
    }

    // Chuyển hướng về student_dashboard.php?page_layout=choice_teacher
    header("Location: student_dashboard.php?page_layout=choice_teacher&status=success");
    exit();
} else {
    echo "Yêu cầu không hợp lệ!";
}
?>
