<?php
include('connect.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Kiểm tra nếu sinh viên đã chọn giảng viên trước đó
$id_sinhvien = $_SESSION['nguoidung']['id']; // Lấy id sinh viên từ session

$sql_check = "SELECT id FROM huongdan WHERE id_sinhvien = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("i", $id_sinhvien);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Nếu đã chọn giảng viên, hiển thị thông báo
    echo "<script>alert('Bạn đã có giảng viên hướng dẫn'); window.location.href = 'student_dashboard.php?page_layout=student_info';</script>";
    exit();
}
$sql = "SELECT nguoidung.id, nguoidung.ma_so_nguoidung, nguoidung.ho_ten, nguoidung.email, nguoidung.so_dien_thoai, chuyennganh.ten_chuyennganh 
            FROM nguoidung 
            LEFT JOIN chuyennganh ON nguoidung.id_chuyennganh = chuyennganh.id
            WHERE nguoidung.vaitro = 'giangvien'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chọn Giảng Viên Hướng Dẫn</title>
    <!-- Reset CSS -->
    <link rel="stylesheet" href="../assests/css/reset.css" />

    <!-- Style CSS -->
    <link rel="stylesheet" href="../assest/css/choice_teacher.css">
</head>

<body>
    <div class="container">
        <!-- Sidebar -->


        <!-- Nội dung chính -->
        <div class="content">
            <h2 class="content-title">Danh sách giảng viên hướng dẫn</h2>
            <form action="process_choice.php" method="POST">
                <table class="teacher-table">
                    <thead>
                        <tr>
                            <th>Mã GV</th>
                            <th>Họ và tên</th>
                            <th>Chuyên ngành</th>
                            <th>Email</th>
                            <th>Điện thoại</th>
                            <th>Chọn</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $row['ma_so_nguoidung']; ?></td>
                                <td><?php echo $row['ho_ten']; ?></td>
                                <td><?php echo $row['ten_chuyennganh'] ? $row['ten_chuyennganh'] : 'Chưa có'; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['so_dien_thoai']; ?></td>
                                <td><input type="checkbox" name="teachers[]" value="<?php echo $row['id']; ?>" class="teacher-checkbox"></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <button class="btn-submit" type="submit">Gửi nguyện vọng</button>
            </form>

            <!-- Hiển thị thông báo nếu gửi thành công -->
            <?php if (isset($_GET['status']) && $_GET['status'] == "success") : ?>
                <p style="color: green;">Nguyện vọng của bạn đã được gửi thành công!</p>
            <?php endif; ?>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const checkboxes = document.querySelectorAll(".teacher-checkbox");
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener("change", function() {
                    let checkedCount = document.querySelectorAll(".teacher-checkbox:checked").length;
                    if (checkedCount > 3) {
                        this.checked = false;
                        alert("Bạn chỉ có thể chọn tối đa 3 giảng viên!");
                    }
                });
            });
        });
    </script>
</body>

</html>