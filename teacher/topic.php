<?php
include('connect.php'); 

    // Truy vấn danh sách đề tài từ cơ sở dữ liệu
    $id_giangvien = $_SESSION['nguoidung']['id']; // Lấy ID giảng viên từ session

    // Chỉ lấy danh sách đề tài của giảng viên hiện tại
    $sql = "SELECT * FROM detai_giangvien WHERE id_giangvien = '$id_giangvien'";
    $result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Đề Tài</title>
    <link rel="stylesheet" href="../assests/css/reset.css">
    <link rel="stylesheet" href="../assest/css/topic.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->

        <!-- Nội dung chính -->
        <div class="content">
            <h2 class="content-title">Danh sách đề tài</h2>
            <table class="detai-table">
                <thead>
                    <tr>
                        <th>Mã Đề Tài</th>
                        <th>Tên Đề Tài</th>
                        <th>Mô Tả</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>DT" . str_pad($row['id'], 3, '0', STR_PAD_LEFT) . "</td>";
                            echo "<td>" . $row['ten_de_tai'] . "</td>";
                            echo "<td>" . $row['mo_ta'] . "</td>";
                            echo "<td>" . ($row['trang_thai'] == 'con_trong' ? 'Còn trống' : 'Đã chọn') . "</td>";
                            echo "<td>
                                    <a href='edit_topic.php?id=" . $row['id'] . "' class='btn edit'>Chỉnh sửa</a>
                                    <a href='delete_topic.php?id=" . $row['id'] . "' class='btn delete' onclick='return confirm(\"Bạn có chắc muốn xóa không?\")'>Xóa</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align: center;'>Không có đề tài nào.</td></tr>";
                    }   
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
mysqli_close($conn);
?>
