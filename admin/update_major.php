<?php
include('connect.php'); // Kết nối CSDL

// Kiểm tra nếu có ID được truyền vào
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Truy vấn dữ liệu chuyên ngành cần sửa
    $stmt = $conn->prepare("SELECT * FROM chuyennganh WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $major = $result->fetch_assoc();

    // Nếu không tìm thấy chuyên ngành, quay về trang danh sách
    if (!$major) {
        header("Location: admin_dashboard.php?page_layout=major");
        exit();
    }
}

// Xử lý khi form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ma_chuyennganh = trim($_POST['ma_chuyennganh']);
    $ten_chuyennganh = trim($_POST['ten_chuyennganh']);

    // Cập nhật chuyên ngành trong CSDL
    $stmt = $conn->prepare("UPDATE chuyennganh SET ma_chuyennganh = ?, ten_chuyennganh = ? WHERE id = ?");
    $stmt->bind_param("ssi", $ma_chuyennganh, $ten_chuyennganh, $id);

    if ($stmt->execute()) {
        // Chuyển hướng về danh sách chuyên ngành với thông báo thành công
        header("Location: admin_dashboard.php?page_layout=major&status=success");
        exit();
    } else {
        echo "<p style='color: red;'>Lỗi khi cập nhật chuyên ngành!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Chuyên Ngành</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
            width: 320px;
        }

        .form-title {
            margin-bottom: 15px;
            font-size: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .form-button:hover {
            background: #0056b3;
        }

        .message {
            margin-top: 10px;
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 class="form-title">Sửa Chuyên Ngành</h2>
        
        <?php if (!empty($message)) { ?>
            <p class="message"><?php echo $message; ?></p>
        <?php } ?>

        <form action="" method="post">
            <div class="form-group">
                <label for="ma_chuyennganh" class="form-label">Mã chuyên ngành:</label>
                <input type="text" id="ma_chuyennganh" name="ma_chuyennganh" class="form-input" value="<?php echo htmlspecialchars($major['ma_chuyennganh']); ?>" required>
            </div>

            <div class="form-group">
                <label for="ten_chuyennganh" class="form-label">Tên chuyên ngành:</label>
                <input type="text" id="ten_chuyennganh" name="ten_chuyennganh" class="form-input" value="<?php echo htmlspecialchars($major['ten_chuyennganh']); ?>" required>
            </div>

            <button type="submit" class="form-button">Lưu</button>
            <a href="admin_dashboard.php?page_layout=major" class="btn-cancel">Hủy</a>
        </form>
    </div>
</body>
</html>
