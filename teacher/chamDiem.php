<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = intval($_POST['student_id']);
    $progress_id = intval($_POST['progress_id']);
    $danhGia = trim($_POST['danhGia']);
    $ngay_cham = date("Y-m-d H:i:s");

    // Lấy id_doan từ sinh viên
    $sql = "SELECT id FROM doan WHERE id_sinhvien = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('Không tìm thấy đề tài!'); window.history.back();</script>";
        exit;
    }

    $row = $result->fetch_assoc();
    $id_doan = $row['id'];

    // Kiểm tra đã có điểm chưa
    $check_sql = "SELECT id FROM diemdoan WHERE id_doan = ? AND id_moc = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $id_doan, $progress_id);
    $stmt->execute();
    $result_check = $stmt->get_result();

    if ($result_check->num_rows > 0) {
        // Cập nhật
        $update_sql = "UPDATE diemdoan SET ghi_chu = ?, ngay_cham = ? WHERE id_doan = ? AND id_moc = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssii", $danhGia, $ngay_cham, $id_doan, $progress_id);
    } else {
        // Thêm mới
        $insert_sql = "INSERT INTO diemdoan (id_doan, id_moc, ghi_chu, ngay_cham) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("iiss", $id_doan, $progress_id, $danhGia, $ngay_cham);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Đánh giá thành công!'); window.location.href='teacher_dashboard.php?page_layout=mocTienDo&id=$student_id';</script>";
    } else {
        echo "<script>alert('Lỗi khi lưu !'); window.history.back();</script>";
    }

    exit;
}

// Nếu GET thì hiển thị form
$student_id = intval($_GET['id'] ?? 0);
$progress_id = intval($_GET['moc'] ?? 0);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Đề Tài</title>
    <link rel="stylesheet" href="../assests/css/reset.css">
    <link rel="stylesheet" href="../assest/css/chamDiem.css">
</head>

<body>
    <div class="container">
        <!-- Sidebar -->

        <!-- Nội dung chính -->
        <div class="content">
            <h2 class="content-title">Nhận xét</h2>

            <!-- Form thêm đề tài -->
            <form class="chamDiem-form" action="" method="POST">
                <input type="hidden" name="student_id" value="<?= $student_id ?>">
                <input type="hidden" name="progress_id" value="<?= $progress_id ?>">

                <label for="danhGia">Đánh giá:</label>
                <textarea id="danhGia" name="danhGia" rows="4" required></textarea>

                <button type="submit" class="btn submit">Nhập </button>
            </form>
        </div>
    </div>
</body>

</html>