<?php
include('connect.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Lấy id sinh viên từ session
$id_sinhvien = $_SESSION['nguoidung']['id']; 

// Lấy chuyên ngành của sinh viên
$sql_sinhvien = "SELECT id_chuyennganh FROM nguoidung WHERE id = $id_sinhvien";
$result_sinhvien = mysqli_query($conn, $sql_sinhvien);
$row_sinhvien = mysqli_fetch_assoc($result_sinhvien);
$id_chuyennganh = $row_sinhvien['id_chuyennganh'];

// Kiểm tra nếu sinh viên đã chọn giảng viên trước đó
$sql_check = "SELECT id FROM huongdan WHERE id_sinhvien = $id_sinhvien";
$result_check = mysqli_query($conn, $sql_check);

if (mysqli_num_rows($result_check) > 0) {
    // Nếu đã chọn giảng viên, hiển thị thông báo
    echo "<script>alert('Bạn đã có giảng viên hướng dẫn'); window.location.href = 'student_dashboard.php?page_layout=student_info';</script>";
    exit();
}

// Lấy học kỳ hiện tại
$sql_hocky = "SELECT id FROM hocky WHERE trang_thai = 'Hoạt động' LIMIT 1";
$result_hocky = mysqli_query($conn, $sql_hocky);
$row_hocky = mysqli_fetch_assoc($result_hocky);
$id_hocky = $row_hocky['id'];

// Lấy danh sách giảng viên trong cùng chuyên ngành và số lượng sinh viên đã chọn
$sql = "SELECT nguoidung.id, nguoidung.ma_so_nguoidung, nguoidung.ho_ten, nguoidung.email, nguoidung.so_dien_thoai, chuyennganh.ten_chuyennganh, 
            (SELECT COUNT(*) FROM huongdan WHERE huongdan.id_giangvien = nguoidung.id AND huongdan.id_hocky = $id_hocky) AS so_luong_sinhvien
        FROM nguoidung 
        LEFT JOIN chuyennganh ON nguoidung.id_chuyennganh = chuyennganh.id
        WHERE nguoidung.vaitro = 'giangvien' AND nguoidung.id_chuyennganh = $id_chuyennganh";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chọn Giảng Viên Hướng Dẫn</title>

    <link rel="stylesheet" href="../assests/css/reset.css" />
    <link rel="stylesheet" href="../assest/css/choice_teacher.css">
</head>
<body>
    <div class="container">
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
                            <th>Số lượng</th> <!-- Cột số lượng sinh viên -->
                            <th>Nguyện vọng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { 
                            $id_giangvien = $row['id'];
                            $so_luong_sinhvien = $row['so_luong_sinhvien'];
                            $is_full = $so_luong_sinhvien >= 5 ? 'disabled' : ''; // Kiểm tra nếu đã đủ sinh viên ?>
                            <tr>
                                <td><?php echo $row['ma_so_nguoidung']; ?></td>
                                <td><?php echo $row['ho_ten']; ?></td>
                                <td><?php echo $row['ten_chuyennganh']?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['so_dien_thoai']; ?></td>
                                <td><?php echo $so_luong_sinhvien; ?></td> <!-- Hiển thị số lượng sinh viên -->
                                <td>
                                    <select name="nguyen_vong_<?php echo $row['id']; ?>" class="teacher-select" data-full="<?php echo $is_full; ?>" <?php echo $is_full ? 'disabled' : ''; ?>>
                                        <option value="0">Chọn nguyện vọng</option>
                                        <option value="1">Nguyện vọng 1</option>
                                        <option value="2">Nguyện vọng 2</option>
                                        <option value="3">Nguyện vọng 3</option>
                                    </select>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <button class="btn-submit" type="submit">Gửi nguyện vọng</button>
            </form>
            <?php if (isset($_GET['status']) && $_GET['status'] == "success") : ?>
                <p style="color: green;">Nguyện vọng của bạn đã được gửi thành công!</p>
            <?php endif; ?>
        </div>
    </div>
</body>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const selects = document.querySelectorAll(".teacher-select");

    // Biến để theo dõi các nguyện vọng đã chọn
    let chosenOptions = { 1: null, 2: null, 3: null };

    selects.forEach(select => {
        select.addEventListener("change", function() {
            const selectedValue = parseInt(this.value);
            const selectedTeacherId = this.name.split('_')[2]; // Lấy ID giảng viên từ tên input

            if (selectedValue > 0) {
                // Nếu đã chọn nguyện vọng này, kiểm tra xem nguyện vọng đó đã được chọn cho giảng viên khác chưa
                if (chosenOptions[selectedValue] && chosenOptions[selectedValue] !== selectedTeacherId) {
                    alert(`Nguyện vọng ${selectedValue} đã được chọn cho giảng viên khác, không thể chọn lại.`);
                    this.value = "0"; // Hủy lựa chọn
                    return;
                }

                // Nếu người dùng muốn sửa nguyện vọng đã chọn, cho phép thay đổi
                if (chosenOptions[selectedValue] === selectedTeacherId) {
                    chosenOptions[selectedValue] = null; // Hủy lựa chọn trước đó của giảng viên
                }

                // Đánh dấu nguyện vọng đã chọn cho giảng viên này
                chosenOptions[selectedValue] = selectedTeacherId;
                alert(`Bạn đã chọn nguyện vọng ${selectedValue} cho giảng viên này!`);
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    const selects = document.querySelectorAll(".teacher-select");

    form.addEventListener("submit", function(event) {
        event.preventDefault(); // Ngăn chặn form submit mặc định

        // Khóa tất cả các select để không thể thay đổi nguyện vọng
        selects.forEach(select => {
            if (select.value !== "0") {
                select.disabled = true;
            }
        });

        alert("Nguyện vọng của bạn đã được gửi! Bạn không thể thay đổi nữa.");
        
        // Gửi form sau khi xử lý xong
        this.submit();
    });
});

</script>
</html>

<?php
mysqli_close($conn);
?>
