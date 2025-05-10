<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $student_id = $_POST['student_id'];
    $progress_id = $_POST['progress'];

    // Kiểm tra đề tài của sinh viên
    $sql_doan = "SELECT id FROM doan WHERE id_sinhvien = ?";
    $stmt = $conn->prepare($sql_doan);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('Không tìm thấy đề tài!'); window.history.back();</script>";
        exit();
    }

    $row = $result->fetch_assoc();
    $doan_id = $row['id'];

    $sql_check = "SELECT id FROM moctiendo WHERE id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $progress_id);  // progress_id là id_moc
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows === 0) {
        echo "<script>alert('Không tìm thấy id_moc trong bảng moctiendo!'); window.history.back();</script>";
        exit();
    }

    // Kiểm tra tệp được tải lên
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file_name = basename($_FILES['file']['name']);
        $target_dir = "uploads/";

        // Kiểm tra loại tệp
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf', 'docx'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if (!in_array($file_extension, $allowed_extensions)) {
            echo "<script>alert('Loại tệp không hợp lệ!'); window.history.back();</script>";
            exit();
        }

        // Tạo tên tệp duy nhất
        $target_file = $target_dir . uniqid('', true) . "_" . $file_name;

        // Tạo thư mục nếu chưa có
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        // Kiểm tra kích thước tệp
        if ($_FILES['file']['size'] > 10 * 1024 * 1024) { // Giới hạn 10MB
            echo "<script>alert('Kích thước tệp quá lớn!'); window.history.back();</script>";
            exit();
        }

        // Di chuyển tệp tải lên
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            // Thêm bản ghi vào bảng bainop
            $sql_insert = "INSERT INTO bainop (id_doan, id_moc, duong_dan_file) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param("iis", $doan_id, $progress_id, $target_file);

            if ($stmt->execute()) {
                echo "<script>alert('Nộp bài thành công!'); window.location.href='student_dashboard.php?page_layout=mocTienDo';</script>";
            } else {
                echo "<script>alert('Lỗi khi lưu thông tin nộp bài: " . $stmt->error . "'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Lỗi khi tải file lên!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Vui lòng chọn file để nộp!'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Phương thức gửi không hợp lệ!'); window.history.back();</script>";
}
?>