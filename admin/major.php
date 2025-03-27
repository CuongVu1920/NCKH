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
    <link rel="stylesheet" href="../assest/css/major.css?v=<?php echo time(); ?>">
    <style>

        .container .content table th, 
        .container .content table td {
            border: none;
            border-bottom: 1px solid #BBDEFB; /* Viền xanh nhạt */
            padding: 12px;
            text-align: left;
            font-size: 16px;
        }
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
            background-color: #42A5F5;
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

        .content-add_student {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .content-add_student .add_user {
            height: 40px;
            width: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #42A5F5;
            color: #000;
            border-radius: 15px;
            font-size: 14px;
            color: #fff;
        }
    </style>
</head>
<body>
   <div class="container">
     <!-- Sidebar -->

    <!-- Nội dung chính -->
    <div class="content">

    <div class="content-add_student">
                <h2 class="content-title">Chọn Chuyên Ngành</h2>
                <a href="admin_dashboard.php?page_layout=add_major" class="add_user"><i style="margin-right: 5px;" class="bi bi-plus-square"></i> Thêm Chuyên Nghành</a>
            </div>


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
                            <a href='admin_dashboard.php?page_layout=update_major&id=" . $row['id'] . "' class='action-btn edit-btn'><i class='bi bi-pencil-square'></i></a>
                            <a href='admin_dashboard.php?page_layout=process_delete-major&id=" . $row['id'] . "' class='action-btn delete-btn' onclick='return confirm(\"Bạn có chắc chắn muốn xóa?\")'><i class='bi bi-trash-fill'></i></a>
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
