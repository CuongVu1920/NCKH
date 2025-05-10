<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = intval($_POST['student_id']);
    $progress_id = intval($_POST['progress_id']);
    $ngay_nop = date("Y-m-d H:i:s");

    // Lấy id đồ án
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

    // Xử lý file nếu có
    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $filename = time() . "_" . basename($_FILES['file']['name']);
        $filepath = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $filepath)) {
            // Kiểm tra đã có bài nộp chưa
            $check_bainop = "SELECT id FROM bainop WHERE id_doan = ? AND id_moc = ?";
            $stmt = $conn->prepare($check_bainop);
            $stmt->bind_param("ii", $id_doan, $progress_id);
            $stmt->execute();
            $result_file = $stmt->get_result();

            if ($result_file->num_rows > 0) {
                // Cập nhật file cũ
                $update_file = "UPDATE bainop SET duong_dan_file = ?, ngay_nop = ? WHERE id_doan = ? AND id_moc = ?";
                $stmt = $conn->prepare($update_file);
                $stmt->bind_param("ssii", $filepath, $ngay_nop, $id_doan, $progress_id);
            } else {
                // Thêm mới file
                $insert_file = "INSERT INTO bainop (id_doan, id_moc, duong_dan_file, ngay_nop) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_file);
                $stmt->bind_param("iiss", $id_doan, $progress_id, $filepath, $ngay_nop);
            }
            $stmt->execute();
            echo "<script>alert('Cập nhật file thành công!'); window.location.href='admin_dashboard.php?page_layout=mocTienDo&id=$student_id';</script>";
        } else {
            echo "<script>alert('Lỗi khi tải lên file!'); window.history.back();</script>";
        }
    }

    exit;
}

// Nếu GET, lấy thông tin file cũ
$student_id = intval($_GET['id'] ?? 0);
$progress_id = intval($_GET['moc'] ?? 0);

// Lấy id đồ án
$stmt = $conn->prepare("SELECT id FROM doan WHERE id_sinhvien = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$id_doan = $row['id'] ?? 0;

// Lấy file cũ nếu có
$old_file = '';
$stmt = $conn->prepare("SELECT duong_dan_file FROM bainop WHERE id_doan = ? AND id_moc = ?");
$stmt->bind_param("ii", $id_doan, $progress_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $old_file = $row['duong_dan_file'];
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa File Bài Làm</title>
    <style>
        /* Reset some defaults */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f9;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .content {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 600px;
        }

        .content-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .chamDiem-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .chamDiem-form label {
            font-weight: bold;
            color: #333;
            font-size: 16px;
        }

        .chamDiem-form input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f9f9f9;
            font-size: 16px;
            color: #333;
        }

        .chamDiem-form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .chamDiem-form button:hover {
            background-color: #45a049;
        }

        .file-info {
            font-size: 14px;
            color: #666;
            text-align: center;
        }

        .file-info a {
            color: #007BFF;
            text-decoration: none;
        }

        .file-info a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="content">
            <h2 class="content-title">Sửa File Bài Làm</h2>

            <form class="chamDiem-form" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="student_id" value="<?= $student_id ?>">
                <input type="hidden" name="progress_id" value="<?= $progress_id ?>">

                <?php if ($old_file): ?>
                    <div class="file-info">
                        <p>File hiện tại: <a href="<?= $old_file ?>" target="_blank">Xem file hiện tại</a></p>
                    </div>
                <?php endif; ?>

                <label for="file">Chọn file mới:</label>
                <input type="file" id="file" name="file" required>

                <button type="submit" class="btn submit">Lưu</button>
            </form>
        </div>
    </div>

</body>

</html>
