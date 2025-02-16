<?php
include 'connect.php';

// Lấy danh sách sinh viên kèm chuyên ngành
$sql = "SELECT nguoidung.*, chuyennganh.ten_chuyennganh 
        FROM nguoidung 
        LEFT JOIN chuyennganh ON nguoidung.id_chuyennganh = chuyennganh.id
        WHERE nguoidung.vaitro = 'sinhvien'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Sinh Viên</title>
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assest/css/teacher_list.css">
    <script>
        function confirmDelete(id) {
            if (confirm("Bạn có chắc muốn xóa sinh viên này không?")) {
                window.location.href = "delete_student.php?id=" + id;
            }
        }
    </script>
</head>
<body>
  <div class="container">
    <div class="content">
        <h2 class="content-title">Danh Sách Sinh Viên</h2>
        <table class="teacher-table">
            <thead>
                <tr>
                    <th>Mã Sinh Viên</th>
                    <th>Họ và Tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Ngày sinh</th>
                    <th>Giới tính</th>
                    <th>Địa chỉ</th>
                    <th>Chuyên ngành</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['ma_so_nguoidung']) ?></td>
                    <td><p class="name-user"><?= htmlspecialchars($row['ho_ten']) ?></p></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['so_dien_thoai']) ?></td>
                    <td><p class="date-user"><?= htmlspecialchars($row['ngay_sinh']) ?></p></td>
                    <td><?= htmlspecialchars($row['gioi_tinh']) ?></td>
                    <td><p class="ad-user"><?= htmlspecialchars($row['dia_chi']) ?></p></td>
                    <td><p class="major-user"><?= htmlspecialchars($row['ten_chuyennganh'] ?? 'Chưa có') ?></p></td>
                    <td>
                        <div class="act-btn">
                            <a href="admin_dashboard.php?page_layout=update_student&id=<?= $row['id'] ?>" class="edit-btn">Sửa</a>
                            <a href="admin_dashboard.php?page_layout=delete_user_process&id=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Bạn có chắc muốn xóa sinh viên này không?');">Xóa</a>
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

<?php
$conn->close();
?>
