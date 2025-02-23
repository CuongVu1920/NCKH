<?php 
include('connect.php');

$sql_sv = "SELECT 
                sv.id, sv.ma_so_nguoidung, sv.ho_ten, 
                chuyennganh.ten_chuyennganh, 
                doan.ten_do_an AS ten_do_an,
                doan.trang_thai, 
                gv.ho_ten AS ten_giangvien
           FROM nguoidung sv
           LEFT JOIN chuyennganh ON sv.id_chuyennganh = chuyennganh.id
           INNER JOIN doan ON sv.id = doan.id_sinhvien
           LEFT JOIN nguoidung gv ON doan.id_giangvien = gv.id
           WHERE sv.vaitro = 'sinhvien' ";


$result_sv = mysqli_query($conn, $sql_sv);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Sinh Viên</title>
    <link rel="stylesheet" href="../assests/css/reset.css">
    <link rel="stylesheet" href="../assest/css/mocTienDo_list.css">
</head>
<body>
  <div class="container">
    <div class="content">
        <h2 class="content-title">Danh Sách Mốc Tiến Độ</h2>
        <table class="teacher-table">
            <thead>
                <tr>
                    <th>Mã Sinh Viên</th>
                    <th>Tên Sinh Viên</th>
                    <th>Tên đề tài</th>
                    <th>Giáo viên hướng dẫn</th>
                    <th>Chuyên ngành</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($result_sv)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['ma_so_nguoidung']) ?></td>
                    <td><p class="name-user"><?= htmlspecialchars($row['ho_ten']) ?></p></td>
                    <td><?= htmlspecialchars($row['ten_do_an']) ?></td>
                    <td><?= htmlspecialchars($row['ten_giangvien']) ?></td>
                    <td><?= htmlspecialchars($row['ten_chuyennganh']) ?></td>
                    <td><?= htmlspecialchars($row['trang_thai']) ?></td>
                    <td>
                        <div class="act-btn">
                            <a href="admin_dashboard.php?page_layout=mocTienDo&id=<?= $row['id'] ?>" class="edit-btn">Sửa</a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
  </div>
</body>
</html>
