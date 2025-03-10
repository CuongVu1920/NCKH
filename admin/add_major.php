<?php
include('connect.php'); // Kết nối database

// Xử lý khi form được gửi
$message = ""; // Biến để lưu thông báo

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ma_chuyennganh = mysqli_real_escape_string($conn, $_POST['ma_chuyennganh']);
    $ten_chuyennganh = mysqli_real_escape_string($conn, $_POST['ten_chuyennganh']);

    $sql = "INSERT INTO chuyennganh (ma_chuyennganh, ten_chuyennganh) VALUES ('$ma_chuyennganh', '$ten_chuyennganh')";
    
    if (mysqli_query($conn, $sql)) {
        $message = "Thêm chuyên ngành thành công!";
        header('location: admin_dashboard.php?page_layout=major');
    } else {
        $message = "Lỗi: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
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
            width: 1320px;
            max-width: calc(100% - 48px);
            margin-left: auto;
            margin-right: auto;
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


        .form-button_container {
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }
        .form-button {
            color: #fff;
            width: auto;
            height: 50px;
            padding: 10px 20px;
            border: none;
            background-color: #2F6AD9;
            border-radius: 5px;
            cursor: pointer;
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
        <h2 class="form-title">Thêm Chuyên Ngành</h2>
        
        <?php if (!empty($message)) { ?>
            <p class="message"><?php echo $message; ?></p>
        <?php } ?>

        <form action="add_major.php" method="post">
            <div class="form-group">
                <label for="ma_chuyennganh" class="form-label">Mã chuyên ngành:</label>
                <input type="text" id="ma_chuyennganh" name="ma_chuyennganh" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="ten_chuyennganh" class="form-label">Tên chuyên ngành:</label>
                <input type="text" id="ten_chuyennganh" name="ten_chuyennganh" class="form-input" required>
            </div>

            <div class="form-button_container">
                <button type="submit" class="form-button"><i class="bi bi-plus-square"></i>  Thêm</button>
            </div>
        </form>
    </div>
</body>
</html>
