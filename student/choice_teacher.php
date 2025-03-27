<?php
include('connect.php');

// Kiểm tra đăng nhập
if (!isset($_SESSION['nguoidung'])) {
    header("Location: login.php");
    exit();
}

$id_sinhvien = $_SESSION['nguoidung']['id'];

// 1. LẤY CHUYÊN NGÀNH CỦA SINH VIÊN TRỰC TIẾP TỪ DATABASE
$sql_sinhvien = "SELECT id_chuyennganh FROM nguoidung WHERE id = ?";
$stmt_sinhvien = $conn->prepare($sql_sinhvien);
$stmt_sinhvien->bind_param("i", $id_sinhvien);
$stmt_sinhvien->execute();
$result_sinhvien = $stmt_sinhvien->get_result();

if ($result_sinhvien->num_rows === 0) {
    die("Không tìm thấy thông tin sinh viên");
}

$row_sinhvien = $result_sinhvien->fetch_assoc();
$id_chuyennganh = $row_sinhvien['id_chuyennganh'];


// 2. KIỂM TRA SINH VIÊN ĐÃ CÓ GIẢNG VIÊN HƯỚNG DẪN CHƯA
$sql_check = "SELECT id FROM huongdan WHERE id_sinhvien = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $id_sinhvien);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    echo "<script>alert('Bạn đã có giảng viên hướng dẫn'); window.location.href = 'student_dashboard.php?page_layout=student_info';</script>";
    exit();
}

// 4. LẤY DANH SÁCH GIẢNG VIÊN CÙNG CHUYÊN NGÀNH (SỬ DỤNG $id_chuyennganh ĐÃ LẤY Ở TRÊN)
$sql = "SELECT nguoidung.id, nguoidung.ma_so_nguoidung, nguoidung.ho_ten, 
               nguoidung.email, nguoidung.so_dien_thoai, chuyennganh.ten_chuyennganh 
        FROM nguoidung 
        LEFT JOIN chuyennganh ON nguoidung.id_chuyennganh = chuyennganh.id
        WHERE nguoidung.vaitro = 'giangvien' 
        AND nguoidung.id_chuyennganh = ?"; 

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_chuyennganh);
$stmt->execute();
$result = $stmt->get_result();
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