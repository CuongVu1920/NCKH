<?php
include('connect.php');

$id_giangvien = $_SESSION['nguoidung']['id'];

// Lấy danh sách sinh viên mà giảng viên đã hướng dẫn kèm theo học kỳ và năm học
$sql = "SELECT DISTINCT nguoidung.ma_so_nguoidung, nguoidung.ho_ten, nguoidung.email, nguoidung.so_dien_thoai,
               COALESCE(detai_giangvien.ten_de_tai, 'Chưa duyệt đề tài') AS ten_de_tai,
               hocky.ten_hocky, hocky.nam_hoc
        FROM huongdan 
        JOIN nguoidung ON huongdan.id_sinhvien = nguoidung.id 
        LEFT JOIN chondetai ON nguoidung.id = chondetai.id_sinhvien AND chondetai.trang_thai = 'dong_y'
        LEFT JOIN detai_giangvien ON chondetai.id_detai = detai_giangvien.id
        JOIN hocky ON huongdan.id_hocky = hocky.id
        WHERE huongdan.id_giangvien = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_giangvien);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sinh viên đã hướng dẫn</title>
    <link rel="stylesheet" href="../assests/css/reset.css">
</head>
<style>
     th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }
</style>

<body>
    <div class="container">
        <div class="content">
            <h2 class="content-title">Danh sách sinh viên đã được giảng viên hướng dẫn</h2>
            <table class="student-table" >
                <thead>
                    <tr>
                        <th>Mã sinh viên</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Đề tài đang thực hiện</th>
                        <th>Học kỳ</th>
                        <th>Năm học</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['ma_so_nguoidung']); ?></td>
                            <td><?php echo htmlspecialchars($row['ho_ten']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['so_dien_thoai']); ?></td>
                            <td><?php echo htmlspecialchars($row['ten_de_tai']); ?></td>
                            <td><?php echo htmlspecialchars($row['ten_hocky']); ?></td>
                            <td><?php echo htmlspecialchars($row['nam_hoc']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
mysqli_close($conn);
?>
