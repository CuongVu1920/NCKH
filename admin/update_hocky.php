<!-- update_hocky.php -->
<?php
// Kết nối với database
require_once 'connect.php';

// Kiểm tra xem ID học kỳ có được truyền qua URL không
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Lấy thông tin học kỳ từ cơ sở dữ liệu
    $sql = "SELECT * FROM hocky WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $ten_hocky = $row['ten_hocky'];
        $nam_hoc = $row['nam_hoc'];
        $trang_thai = $row['trang_thai'];
    } 
    }

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập Nhật Học Kỳ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        form label {
            margin: 10px 0 5px;
        }

        form input, form select {
            padding: 10px;
            width: 100%;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cập Nhật Học Kỳ</h1>
        <form action="process_update_hocky.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>"> <!-- Truyền ID học kỳ ẩn -->

            <label for="ten_hocky">Tên Học Kỳ:</label>
            <input type="text" id="ten_hocky" name="ten_hocky" value="<?php echo $ten_hocky; ?>" required>
            
            <label for="nam_hoc">Năm Học:</label>
            <input type="text" id="nam_hoc" name="nam_hoc" value="<?php echo $nam_hoc; ?>" required>

            <label for="trang_thai">Trạng Thái:</label>
            <select name="trang_thai" id="trang_thai">
                <option value="Hoạt động" <?php if ($trang_thai == 'Hoạt động') echo 'selected'; ?>>Hoạt động</option>
                <option value="Kết thúc" <?php if ($trang_thai == 'Kết thúc') echo 'selected'; ?>>Kết thúc</option>
            </select>

            <button type="submit">Cập Nhật Học Kỳ</button>
        </form>
    </div>
</body>
</html>
