<?php
    include('connect.php');

    $id_giangvien = $_SESSION['nguoidung']['id'];

    // Lấy danh sách sinh viên gửi nguyện vọng
    $sql = "SELECT huongdan.id, nguoidung.ma_so_nguoidung, nguoidung.ho_ten, nguoidung.email, nguoidung.so_dien_thoai, chuyennganh.ten_chuyennganh, huongdan.trang_thai 
            FROM huongdan 
            JOIN nguoidung ON huongdan.id_sinhvien = nguoidung.id 
            JOIN chuyennganh ON nguoidung.id_chuyennganh = chuyennganh.id
            WHERE huongdan.id_giangvien = ? AND huongdan.trang_thai = 'cho_duyet'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_giangvien);
    $stmt->execute();
    $result = $stmt->get_result();

    // Đếm số sinh viên đã được chấp nhận
    $sql_count = "SELECT COUNT(*) AS total FROM huongdan WHERE id_giangvien = ? AND trang_thai = 'dong_y'";
    $stmt_count = $conn->prepare($sql_count);
    $stmt_count->bind_param("i", $id_giangvien);
    $stmt_count->execute();
    $count_result = $stmt_count->get_result()->fetch_assoc();
    $total_accepted = $count_result['total'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Báo Giảng Viên</title>
    <link rel="stylesheet" href="../assests/css/reset.css">

    <link rel="stylesheet" href="../assest/css/teacher_non.css?v=<?php echo time(); ?>">
</head>
<style>
    .msv, .name, .major, .sdt, .hd {
        white-space: nowrap;
    }

    .act-btn {
        display: flex;
        gap: 20px;
    }
</style>
<body>
    <div class="container">
        <!-- Sidebar -->

        <!-- Nội dung chính -->
        <div class="content">
            <h2 class="content-title">Danh sách sinh viên gửi nguyện vọng</h2>
            <table class="request-table">
                <thead>
                    <tr>
                        <th><p class="msv">Mã sinh viên</p></th>
                        <th><p class="name">Họ và tên</p></th>
                        <th><div class="major">Chuyên ngành</div></th>
                        <th>Email</th>
                        <th><p class="sdt">Điện thoại</p></th>
                        <th><div class="hd">Hành động</div></th>
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
                        <td>
                            <div class="act-btn">
                                <button class="btn accept" data-id="<?php echo $row['id']; ?>" <?php echo ($total_accepted >= 5) ? 'disabled' : ''; ?>>Chấp nhận</button>
                                <button class="btn reject" data-id="<?php echo $row['id']; ?>">Từ chối</button>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.querySelectorAll('.btn.accept').forEach(button => {
            button.addEventListener('click', function () {
                updateStatus(this.dataset.id, 'dong_y', this);
            });
        });

        document.querySelectorAll('.btn.reject').forEach(button => {
            button.addEventListener('click', function () {
                updateStatus(this.dataset.id, 'tu_choi', this);
            });
        });

        function updateStatus(id, status, button) {
            fetch('update_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&status=${status}`
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    button.parentElement.parentElement.remove();
                } else if (data === 'limit_reached') {
                    alert('Bạn đã đạt giới hạn 5 sinh viên.');
                } else {
                    alert('Có lỗi xảy ra, vui lòng thử lại.');
                }
            });
        }
    </script>
</body>
</html>
