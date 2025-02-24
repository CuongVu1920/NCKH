<?php
include('connect.php');
session_start();

if (!isset($_SESSION['nguoidung']) || $_SESSION['nguoidung']['vaitro'] != 'giangvien') {
    echo json_encode(["status" => "error", "message" => "Bạn không có quyền truy cập!"]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['action'])) {
    $id = intval($_POST['id']);
    $action = $_POST['action'];
    $teacher_id = $_SESSION['nguoidung']['id'];

    // Lấy học kỳ hiện tại từ session hoặc cách khác
    $id_hocky = $_SESSION['id_hocky'];  // Ví dụ: học kỳ lấy từ session

    // Kiểm tra đề tài có tồn tại không
    $check_sql = "SELECT id_detai, id_sinhvien FROM chondetai WHERE id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if (!$row) {
        echo json_encode(["status" => "error", "message" => "Đề tài không tồn tại."]);
        exit();
    }

    $id_detai = $row['id_detai'];
    $id_sinhvien = $row['id_sinhvien'];

    if ($action === 'approve') {
        // Kiểm tra số lượng sinh viên đã được chấp nhận của giảng viên
        $check_count_sql = "SELECT COUNT(*) AS total FROM huongdan WHERE id_giangvien = ? AND id_hocky = ?";
        $stmt = $conn->prepare($check_count_sql);
        $stmt->bind_param("ii", $teacher_id, $id_hocky);
        $stmt->execute();
        $count_result = $stmt->get_result();
        $count_row = $count_result->fetch_assoc();
        $stmt->close();

        if ($count_row['total'] >= 5) {
            echo json_encode(["status" => "error", "message" => "Bạn chỉ có thể chấp nhận tối đa 5 sinh viên trong học kỳ này."]);
            exit();
        }

        // Cập nhật trạng thái của đề tài trong chondetai
        $update_sql = "UPDATE chondetai SET trang_thai = 'dong_y' WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Cập nhật trạng thái của đề tài trong detai_giangvien
        $update_detai_sql = "UPDATE detai_giangvien SET trang_thai = 'da_chon' WHERE id = ?";
        $stmt = $conn->prepare($update_detai_sql);
        $stmt->bind_param("i", $id_detai);
        $stmt->execute();
        $stmt->close();

        // Thêm vào bảng huongdan với id_hocky
        $insert_huongdan_sql = "INSERT INTO huongdan (id_giangvien, id_sinhvien, id_hocky) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_huongdan_sql);
        $stmt->bind_param("iii", $teacher_id, $id_sinhvien, $id_hocky);
        $stmt->execute();
        $stmt->close();

        echo json_encode(["status" => "success", "message" => "Đã chấp nhận đề tài."]);
    } elseif ($action === 'reject') {
        // Cập nhật trạng thái của đề tài trong chondetai
        $update_sql = "UPDATE chondetai SET trang_thai = 'tu_choi' WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        echo json_encode(["status" => "success", "message" => "Đã từ chối đề tài."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Hành động không hợp lệ."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Dữ liệu không hợp lệ."]);
}
?>
