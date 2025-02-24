<?php
include('connect.php');

// Kiểm tra nếu sinh viên đã đăng nhập
if (!isset($_SESSION['nguoidung']) || $_SESSION['nguoidung']['vaitro'] != 'sinhvien') {
    echo "<script>alert('Bạn không có quyền truy cập!'); window.location.href='student_dashboard.php';</script>";
    exit();
}

$student_id = $_SESSION['nguoidung']['id']; // ID sinh viên từ session

// Truy vấn giảng viên hướng dẫn của sinh viên
$sql_gv = "SELECT id_giangvien FROM huongdan WHERE id_sinhvien = ?";
$stmt_gv = $conn->prepare($sql_gv);
$stmt_gv->bind_param("i", $student_id);
$stmt_gv->execute();
$result_gv = $stmt_gv->get_result();
$row_gv = $result_gv->fetch_assoc();
$stmt_gv->close();

if (!$row_gv) {
    echo "<script>alert('Bạn chưa có giảng viên hướng dẫn!'); window.location.href='student_dashboard.php?page_layout=student_info';</script>";
    exit();
}

$giangvien_id = $row_gv['id_giangvien']; // ID giảng viên hướng dẫn

// Truy vấn danh sách đề tài của giảng viên hướng dẫn
$sql = "SELECT * FROM detai_giangvien WHERE id_giangvien = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $giangvien_id);
$stmt->execute();
$result = $stmt->get_result();

// Kiểm tra nếu sinh viên đã chọn đề tài trước đó
$sql_check = "SELECT id_detai FROM chondetai WHERE id_sinhvien = ? AND trang_thai = 'cho_duyet'";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $student_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$row_check = $result_check->fetch_assoc();
$selected_topic_id = $row_check ? $row_check['id_detai'] : null;
$stmt_check->close();

// Kiểm tra nếu sinh viên đã có đề tài hoặc đang chờ duyệt
if ($selected_topic_id) {
    echo "<script>alert('Bạn đã chọn đề tài và đang chờ duyệt!'); window.location.href='student_dashboard.php?page_layout=student_info';</script>";
    exit();
}

// Kiểm tra nếu sinh viên đã chọn đề tài khác
$sql_check_accepted = "SELECT id_detai FROM chondetai WHERE id_sinhvien = ? AND trang_thai = 'dong_y'";
$stmt_check_accepted = $conn->prepare($sql_check_accepted);
$stmt_check_accepted->bind_param("i", $student_id);
$stmt_check_accepted->execute();
$result_check_accepted = $stmt_check_accepted->get_result();
$row_check_accepted = $result_check_accepted->fetch_assoc();
$accepted_topic_id = $row_check_accepted ? $row_check_accepted['id_detai'] : null;
$stmt_check_accepted->close();

// Nếu sinh viên đã có đề tài được chấp nhận
if ($accepted_topic_id) {
    echo "<script>alert('Bạn đã có đề tài rồi!'); window.location.href='student_dashboard.php?page_layout=student_info';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Đề Tài</title>
    <link rel="stylesheet" href="../assests/css/reset.css">
    <link rel="stylesheet" href="../assests/css/topic.css">
</head>
<body>
    <div class="container">
        <div class="content">
            <h2 class="content-title">Danh sách đề tài của giảng viên hướng dẫn</h2>
            <table class="detai-table">
                <thead>
                    <tr>
                        <th>Mã Đề Tài</th>
                        <th>Tên Đề Tài</th>
                        <th>Mô Tả</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?php echo "DT" . str_pad($row['id'], 3, "0", STR_PAD_LEFT); ?></td>
                            <td><?php echo $row['ten_de_tai']; ?></td>
                            <td><?php echo $row['mo_ta']; ?></td>
                            <td>
                                <?php 
                                    if ($row['trang_thai'] == 'con_trong') {
                                        echo "Còn trống";
                                    } elseif ($row['trang_thai'] == 'dong_y') {
                                        echo "Đã chọn";
                                    }
                                ?>  
                            </td>
                            <td>
                                <?php if ($accepted_topic_id == $row['id']) : ?>
                                    <button class="btn pending" disabled>Đã có đề tài</button>
                                <?php elseif ($row['trang_thai'] == 'con_trong') : ?>
                                    <a href="choose_topic.php?id=<?php echo $row['id']; ?>" class="btn edit">Chọn</a>
                                <?php elseif ($row['trang_thai'] == 'da_chon' || $row['trang_thai'] == 'dong_y') : ?>
                                    <button class="btn disabled" disabled>Đã chọn</button>
                                <?php else : ?>
                                    <button class="btn disabled" disabled>Không khả dụng</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php mysqli_close($conn); ?>
