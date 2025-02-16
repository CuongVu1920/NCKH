<?php
include('connect.php'); // Kết nối database

// Kiểm tra nếu session chưa tồn tại
if (!isset($_SESSION['nguoidung']['id'])) {
    die("Bạn chưa đăng nhập!");
}

$teacher_id = $_SESSION['nguoidung']['id']; // Lấy ID giảng viên từ session

// Truy vấn lấy thông tin giảng viên
$sql = "SELECT nguoidung.*, chuyennganh.ten_chuyennganh 
        FROM nguoidung 
        LEFT JOIN chuyennganh ON nguoidung.id_chuyennganh = chuyennganh.id
        WHERE nguoidung.id = '$teacher_id'";

$result = mysqli_query($conn, $sql);
$teacher = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin giảng viên</title>
    <!-- Reset CSS -->
    <link rel="stylesheet" href="../assets/css/reset.css">

     <!-- Style CSS -->
     <link rel="stylesheet" href="../assest/css/student_info.css">
</head>
<body>
  <div class="container">
    <main class="content">
        <div class="teacher-info card">
            <h2 class="card-title">Thông tin giảng viên</h2>
            <div class="info-container">
                <div class="info">
                    <p class="info-item"><span class="info-label">Mã GV:</span> <?php echo $teacher['ma_so_nguoidung']; ?></p>
                    <p class="info-item"><span class="info-label">Họ và tên:</span> <?php echo $teacher['ho_ten']; ?></p>
                    <p class="info-item"><span class="info-label">Ngày sinh:</span> <?php echo date('d/m/Y', strtotime($teacher['ngay_sinh'])); ?></p>
                    <p class="info-item"><span class="info-label">Giới tính:</span> <?php echo $teacher['gioi_tinh']; ?></p>
                    <p class="info-item"><span class="info-label">Điện thoại:</span> <?php echo $teacher['so_dien_thoai']; ?></p>
                    <p class="info-item"><span class="info-label">Email:</span> <?php echo $teacher['email']; ?></p>
                    <p class="info-item"><span class="info-label">Địa chỉ:</span> <?php echo $teacher['dia_chi']; ?></p>
                    <p class="info-item"><span class="info-label">Chuyên ngành:</span> <?php echo $teacher['ten_chuyennganh']; ?></p>
                </div>
            </div>
        </div>    
    </main>
  </div>
</body>
</html>
