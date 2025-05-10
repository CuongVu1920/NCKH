<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $progress_id = $_POST['progress'];

    $sql_doan = "SELECT id FROM doan WHERE id_sinhvien = ?";
    $stmt = $conn->prepare($sql_doan);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('Không tìm thấy đề tài!'); window.history.back();</script>";
        exit();
    }

    $doan_id = $result->fetch_assoc()['id'];

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file_name = basename($_FILES['file']['name']);
        $target_dir = __DIR__ . "/uploads/";
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf', 'docx'];

        if (!in_array($file_extension, $allowed_extensions)) {
            echo "<script>alert('Loại tệp không hợp lệ!'); window.history.back();</script>";
            exit();
        }

        if ($_FILES['file']['size'] > 10 * 1024 * 1024) {
            echo "<script>alert('Kích thước tệp quá lớn!'); window.history.back();</script>";
            exit();
        }

        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $unique_name = uniqid('', true) . "_" . $file_name;
        $full_path = $target_dir . $unique_name;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $full_path)) {
            // Đường dẫn URL để lưu vào DB (giảng viên có thể truy cập được)
            $web_path = "http://localhost/NCKH2/student/uploads/" . $unique_name;

            $sql_insert = "INSERT INTO bainop (id_doan, id_moc, duong_dan_file) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param("iis", $doan_id, $progress_id, $web_path);
            if ($stmt->execute()) {
                echo "<script>alert('Nộp bài thành công!'); window.location.href='student_dashboard.php?page_layout=mocTienDo';</script>";
            } else {
                echo "<script>alert('Lỗi khi lưu dữ liệu'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Lỗi khi tải lên file!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Vui lòng chọn file!'); window.history.back();</script>";
    }
}
?>
