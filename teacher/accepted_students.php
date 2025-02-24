<?php
include('connect.php');

$id_giangvien = $_SESSION['nguoidung']['id'];

// Lấy danh sách sinh viên đã được chấp nhận cùng với đề tài của họ trong học kỳ hiện tại
$sql = "SELECT DISTINCT nguoidung.ma_so_nguoidung, nguoidung.ho_ten, nguoidung.email, nguoidung.so_dien_thoai, 
                   chuyennganh.ten_chuyennganh, COALESCE(detai_giangvien.ten_de_tai, 'Chưa duyệt đề tài') AS ten_de_tai,
                   huongdan.id_sinhvien
            FROM huongdan 
            JOIN nguoidung ON huongdan.id_sinhvien = nguoidung.id 
            JOIN chuyennganh ON nguoidung.id_chuyennganh = chuyennganh.id
            LEFT JOIN chondetai ON nguoidung.id = chondetai.id_sinhvien AND chondetai.trang_thai = 'dong_y'
            LEFT JOIN detai_giangvien ON chondetai.id_detai = detai_giangvien.id
            JOIN hocky ON huongdan.id_hocky = hocky.id
            WHERE huongdan.id_giangvien = ? AND hocky.trang_thai = 'Hoạt động'"; // Lọc theo học kỳ "Hoạt động"

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
    <title>Danh sách sinh viên được chấp nhận</title>
    <link rel="stylesheet" href="../assests/css/reset.css">
    <link rel="stylesheet" href="../assest/css/teacher_non.css">
</head>
<style>
    /* Content */
    .content {
        width: 100%;
        background-color: #f9f9f9;
        border-radius: 8px;
    }

    /* Title */
    .content-title {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        color: #4a90e2;
        margin-bottom: 20px;
    }

    /* Table */
    .request-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .request-table th,
    .request-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    /* Header styling */
    .request-table th {
        background-color: #4a90e2;
        color: white;
        font-size: 16px;
    }

    /* Row styling */
    .request-table td {
        font-size: 14px;
        color: #333;
    }

    /* Hover effect */
    .request-table tr:hover {
        background-color: #f1f1f1;
    }

    /* Responsive design */
    @media (max-width: 768px) {

        .request-table th,
        .request-table td {
            padding: 8px 10px;
        }

        .content-title {
            font-size: 20px;
        }
    }

    .btn {
        display: inline-block;
        padding: 6px 12px;
        background-color: #4a90e2;
        color: white;
        border-radius: 4px;
        text-decoration: none;
    }

    .btn:hover {
        background-color: #357abd;
    }
</style>

<body>
    <div class="container">
        <div class="content">
            <h2 class="content-title">Danh sách sinh viên hướng dẫn</h2>
            <table class="request-table">
                <thead>
                    <tr>
                        <th>Mã sinh viên</th>
                        <th>Họ tên</th>
                        <th>Chuyên ngành</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Đề tài đang thực hiện</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['ma_so_nguoidung']); ?></td>
                            <td><?php echo htmlspecialchars($row['ho_ten']); ?></td>
                            <td><?php echo htmlspecialchars($row['ten_chuyennganh']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['so_dien_thoai']); ?></td>
                            <td><?php echo htmlspecialchars($row['ten_de_tai']); ?></td>
                            <td><a href="teacher_dashboard.php?page_layout=mocTienDo&id=<?php echo $row['id_sinhvien']?>"  class="btn submit">Xem</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>