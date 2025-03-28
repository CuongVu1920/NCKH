<?php
include 'connect.php';

// Lấy danh sách giảng viên kèm chuyên ngành
$sql = "SELECT nguoidung.*, chuyennganh.ten_chuyennganh 
        FROM nguoidung 
        LEFT JOIN chuyennganh ON nguoidung.id_chuyennganh = chuyennganh.id
        WHERE nguoidung.vaitro = 'giangvien'";

$result = $conn->query($sql);

$result_per_page = 7;

$number_of_result = mysqli_num_rows($result);
$number_of_page = ceil($number_of_result/$result_per_page);
$page = isset($_GET['idpage']) ? (int)$_GET['idpage'] : 1;
if ($page < 1) {
$page = 1; // Đảm bảo page không nhỏ hơn 1
}
$this_page_first_result = ($page-1)*$result_per_page;

$sql = "SELECT nguoidung.*, chuyennganh.ten_chuyennganh  
        FROM nguoidung 
        LEFT JOIN chuyennganh ON nguoidung.id_chuyennganh = chuyennganh.id
        WHERE nguoidung.vaitro = 'giangvien'
        LIMIT $this_page_first_result, $result_per_page";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Giảng Viên</title>
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assest/css/teacher_list.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script>
        function confirmDelete(id) {
            if (confirm("Bạn có chắc muốn xóa giảng viên này không?")) {
                window.location.href = "delete_teacher.php?id=" + id;
            }
        }
    </script>
</head>

<body>
    <div class="container">
        <div class="content">

            <div class="content-add_student">
                 <h2 class="content-title">Danh Sách Giảng Viên</h2>
                <a href="admin_dashboard.php?page_layout=add_user" class="add_user"> <i class="bi bi-person-fill-add"></i> Thêm người dùng</a>
            </div>

        <table class="teacher-table">
            <thead>
                <tr>
                    <th>Mã Giảng Viên</th>
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
                        <td>
                            <p class="name-user"><?= htmlspecialchars($row['ho_ten']) ?></p>
                        </td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['so_dien_thoai']) ?></td>
                        <td>
                            <p class="date-user"><?= htmlspecialchars($row['ngay_sinh']) ?></p>
                        </td>
                        <td><?= htmlspecialchars($row['gioi_tinh']) ?></td>
                        <td>
                            <p class="ad-user"><?= htmlspecialchars($row['dia_chi']) ?></p>
                        </td>
                        <td>
                            <p class="major-user"><?= htmlspecialchars($row['ten_chuyennganh'] ?? 'Chưa có') ?></p>
                        </td>
                        <td>
                            <div class="act-btn">
                                <a href="admin_dashboard.php?page_layout=update_teacher&id=<?= $row['id'] ?>" class="edit-btn"><i class="bi bi-pencil-square"></i></a>
                                <a href="admin_dashboard.php?page_layout=delete_user_process&id=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Bạn có chắc muốn xóa giảng viên này không?');"><i class="bi bi-trash-fill"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    
    <?php
// Đảm bảo biến $current_page và $number_of_page không bị lỗi
$current_page = isset($_GET['idpage']) ? (int)$_GET['idpage'] : 1;
if ($current_page < 1) {
    $current_page = 1;
}
if ($current_page > $number_of_page) {
    $current_page = $number_of_page;
}
?>

<nav>
    <ul class="pagination justify-content-center">
        <?php if ($current_page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="admin_dashboard.php?page_layout=teacher_list&idpage=1">Đầu</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="admin_dashboard.php?page_layout=teacher_list&idpage=<?php echo ($current_page - 1); ?>">Trước</a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $number_of_page; $i++): ?>
            <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                <a class="page-link" href="admin_dashboard.php?page_layout=teacher_list&idpage=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($current_page < $number_of_page): ?>
            <li class="page-item">
                <a class="page-link" href="admin_dashboard.php?page_layout=teacher_list&idpage=<?php echo ($current_page + 1); ?>">Tiếp</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="admin_dashboard.php?page_layout=teacher_list&idpage=<?php echo $number_of_page; ?>">Cuối</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$conn->close();
?>