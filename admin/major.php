<?php
include('connect.php'); // Kết nối CSDL
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chọn Chuyên Ngành</title>
    <!-- Reset CSS -->
    <link rel="stylesheet" href="../assests/css/reset.css">
    <!-- Style CSS -->
    <link rel="stylesheet" href="../assest/css/major.css">
    <style>
        .action-btn {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            text-align: center;
        }

        .edit-btn {
            background-color: #ffc107;
            color: black;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
        }

        .edit-btn:hover {
            background-color: #e0a800;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
   <div class="container">
     <!-- Sidebar -->

    <!-- Nội dung chính -->
    <div class="content">
        <h2 class="content-title">Chọn Chuyên Ngành</h2>

        <!-- Bảng danh sách chuyên ngành -->
        <table class="major-table">
            <thead>
                <tr>
                    <th>Mã chuyên ngành</th>
                    <th>Tên chuyên ngành</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM chuyennganh";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['ma_chuyennganh']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['ten_chuyennganh']) . "</td>";
                        echo "<td>
                            <a href='admin_dashboard.php?page_layout=update_major&id=" . $row['id'] . "' class='action-btn edit-btn'>Sửa</a>
                            <a href='admin_dashboard.php?page_layout=process_delete-major&id=" . $row['id'] . "' class='action-btn delete-btn' onclick='return confirm(\"Bạn có chắc chắn muốn xóa?\")'>Xóa</a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Không có dữ liệu</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
   </div>
</body>
</html>
