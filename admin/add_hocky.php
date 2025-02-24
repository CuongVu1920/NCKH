<!-- add_hocky.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Học Kỳ</title>
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
        <h1>Thêm Học Kỳ</h1>
        <form action="process_add_hocky.php" method="POST">
            <label for="ten_hocky">Tên Học Kỳ:</label>
            <input type="text" id="ten_hocky" name="ten_hocky" required>
            
            <label for="nam_hoc">Năm Học:</label>
            <input type="text" id="nam_hoc" name="nam_hoc" required>

            <label for="trang_thai">Trạng Thái:</label>
            <select name="trang_thai" id="trang_thai">
                <option value="Hoạt động">Hoạt động</option>
                <option value="Kết thúc">Kết thúc</option>
            </select>

            <button type="submit">Thêm Học Kỳ</button>
        </form>
    </div>
</body>
</html>
