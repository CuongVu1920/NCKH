<?php
include('connect.php'); // Kết nối database

$student_id = $_SESSION['nguoidung']['id']; // Lấy ID sinh viên từ session

// Truy vấn lấy thông tin sinh viên
$sql = "SELECT nguoidung.*, chuyennganh.ten_chuyennganh 
        FROM nguoidung 
        LEFT JOIN chuyennganh ON nguoidung.id_chuyennganh = chuyennganh.id
        WHERE nguoidung.id = '$student_id'";

$result = mysqli_query($conn, $sql);
$student = mysqli_fetch_assoc($result);

// Truy vấn lấy thông tin giảng viên hướng dẫn
$sql_advisor = "SELECT nguoidung.ho_ten, nguoidung.email, nguoidung.so_dien_thoai
                FROM huongdan 
                INNER JOIN nguoidung ON huongdan.id_giangvien = nguoidung.id
                WHERE huongdan.id_sinhvien = '$student_id'"; // Không cần điều kiện trạng thái
$result_advisor = mysqli_query($conn, $sql_advisor);
$advisor = mysqli_fetch_assoc($result_advisor);

// Truy vấn lấy thông tin đề tài của sinh viên
$sql_topic = "SELECT detai_giangvien.ten_de_tai, detai_giangvien.mo_ta FROM chondetai 
              LEFT JOIN detai_giangvien ON chondetai.id_detai = detai_giangvien.id
              WHERE chondetai.id_sinhvien = '$student_id' AND chondetai.trang_thai = 'dong_y'";
$result_topic = mysqli_query($conn, $sql_topic);
$topic = mysqli_fetch_assoc($result_topic);
$topic_name = $topic['ten_de_tai'] ?? 'Chưa có đề tài';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin sinh viên</title>
    <!-- Reset CSS -->
    <link rel="stylesheet" href="../assests/css/reset.css">

    <!-- Style CSS -->
    <link rel="stylesheet" href="../assest/css/student_info.css">
</head>
<body>
  <div class="container">
    <main class="content">
        <div class="student-info card">
            <h2 class="card-title">Thông tin sinh viên</h2>
            <div class="info-container">
                <div class="info">
                    <p class="info-item"><span class="info-label">Mã SV:</span> <?php echo $student['ma_so_nguoidung']; ?></p>
                    <p class="info-item"><span class="info-label">Họ và tên:</span> <?php echo $student['ho_ten']; ?></p>
                    <p class="info-item"><span class="info-label">Ngày sinh:</span> <?php echo date('d/m/Y', strtotime($student['ngay_sinh'])); ?></p>
                    <p class="info-item"><span class="info-label">Giới tính:</span> <?php echo $student['gioi_tinh']; ?></p>
                    <p class="info-item"><span class="info-label">Điện thoại:</span> <?php echo $student['so_dien_thoai']; ?></p>
                    <p class="info-item"><span class="info-label">Email:</span> <?php echo $student['email']; ?></p>
                    <p class="info-item"><span class="info-label">Địa chỉ:</span> <?php echo $student['dia_chi']; ?></p>
                    <p class="info-item"><span class="info-label">Chuyên ngành:</span> <?php echo $student['ten_chuyennganh']; ?></p>
                </div>
            </div>
        </div>

        <div class="course-info card">
            <h2 class="card-title">Đề tài thực hiện</h2>
            <p class="info-item"><span class="info-label">Tên đề tài: </span> <?php echo $topic_name; ?></p>
        </div>

        <div class="advisor-info card">
            <h2 class="card-title">Giảng viên hướng dẫn</h2>
            <?php if ($advisor): ?>
                <p class="info-item"><span class="info-label">Họ và tên:</span> <?php echo $advisor['ho_ten']; ?></p>
                <p class="info-item"><span class="info-label">Email:</span> <?php echo $advisor['email']; ?></p>
                <p class="info-item"><span class="info-label">Điện thoại:</span> <?php echo $advisor['so_dien_thoai']; ?></p>
            <?php else: ?>
                <p class="info-item"><span class="info-label">Chưa có giảng viên hướng dẫn</span></p>
            <?php endif; ?>
        </div>
    </main>
  </div>
</body>
</html>
