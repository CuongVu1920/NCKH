<?php
session_start();
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'];
    $id_giangvien = $_SESSION['nguoidung']['id'];

    // Kiểm tra số lượng sinh viên đã được chấp nhận
    if ($status === 'dong_y') {
        $sql_check = "SELECT COUNT(*) AS total FROM huongdan WHERE id_giangvien = ? AND trang_thai = 'dong_y'";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("i", $id_giangvien);
        $stmt_check->execute();
        $count_result = $stmt_check->get_result()->fetch_assoc();
        $total_accepted = $count_result['total'];

        if ($total_accepted >= 5) {
            echo "limit_reached"; // Giảng viên đã đạt giới hạn 5 sinh viên
            exit();
        }
    }

    // Cập nhật trạng thái nguyện vọng
    $sql = "UPDATE huongdan SET trang_thai = ? WHERE id = ? AND id_giangvien = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $status, $id, $id_giangvien);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "invalid_request";
}
?>
