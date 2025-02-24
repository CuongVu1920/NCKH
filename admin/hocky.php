<?php
// display_hocky.php
require_once 'connect.php'; // Kết nối với database

// Lấy danh sách học kỳ
$sql = "SELECT * FROM hocky";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Học Kỳ</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .update-button {
            padding: 5px 10px;
            background-color: #f0ad4e;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .update-button:hover {
            background-color: #ec971f;
        }

        .disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Danh Sách Học Kỳ</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Học Kỳ</th>
                    <th>Năm Học</th>
                    <th>Trạng Thái</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['ten_hocky']; ?></td>
                        <td><?php echo $row['nam_hoc']; ?></td>
                        <td><?php echo $row['trang_thai']; ?></td>
                        <td>
                            <?php if ($row['trang_thai'] == 'Hoạt động'): ?>
                                <a href="admin_dashboard.php?page_layout=update_hocky&id=<?php echo $row['id']; ?>" class="update-button">Cập nhật</a>
                                <?php else: ?>
                                <button class="update-button disabled" disabled>Đã kết thúc</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
mysqli_close($conn);
?>
