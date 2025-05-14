<?php
include('connect.php');

$student_id = intval($_GET['id']); // Lấy ID sinh viên từ URL

$sql_topic = "SELECT ten_do_an, id FROM doan WHERE id_sinhvien = ?";
$stmt = $conn->prepare($sql_topic);
$stmt->bind_param("i", $student_id);  
$stmt->execute();
$result_topic = $stmt->get_result();
$topic = $result_topic->fetch_array();
$topic_name = $topic['ten_do_an'] ?? '';
$id_topic = $topic['id'] ?? '';

$sql_student = "SELECT ho_ten , ma_so_nguoidung FROM nguoidung WHERE id = ?";
$stmt_student = $conn->prepare($sql_student);
$stmt_student->bind_param("i", $student_id);
$stmt_student->execute();
$result_student = $stmt_student->get_result();
$student = $result_student->fetch_array();

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Đề Tài</title>
    <link rel="stylesheet" href="../assests/css/reset.css">

    <link rel="stylesheet" href="../assest/css/moctiendo.css">
</head>

<body>
    <div class="container">

        <div class="content">

            <div class="heading">
                <h2 class="content-title">Mốc Tiến Độ Đồ Án</h2>
                <h2 class="content-title"> Tên Đồ Án: <?php echo $topic_name ?? 'chưa có đề tài' ?> </h2>
                <h2 class="content-title"><?php echo "$student[ho_ten] - $student[ma_so_nguoidung]" ?></h2>
            </div>
            <table class="progress-table">
                <thead>
                    <tr>
                        <th>Mốc Tiến Độ</th>
                        <th>File bài làm</th>
                        <th>Đánh Giá</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $moc_tien_do = [
                        1 => 'Báo cáo đề cương',
                        2 => 'Báo cáo thiết kế',
                        3 => 'Báo cáo thử nghiệm',
                        4 => 'Báo cáo tổng kết'
                    ];

                    foreach ($moc_tien_do as $progress_id => $moc_name) {
                        $sql_check = "SELECT * FROM bainop WHERE id_doan = ? AND id_moc = ?";
                        $stmt = $conn->prepare($sql_check);
                        $stmt->bind_param("ii", $id_topic, $progress_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $has_submitted = $result->num_rows > 0;
                        $file_info = $has_submitted ? $result->fetch_assoc() : null;
                    
                        // Kiểm tra đã chấm điểm chưa
                        $sql_check_score = "SELECT ghi_chu FROM diemdoan WHERE id_doan = ? AND id_moc = ?";
                        $stmt_score = $conn->prepare($sql_check_score);
                        $stmt_score->bind_param("ii", $id_topic, $progress_id);
                        $stmt_score->execute();
                        $result_score = $stmt_score->get_result();
                        $is_scored = $result_score->num_rows > 0;
                        $score_note = $is_scored ? $result_score->fetch_assoc()['ghi_chu'] : "Chưa đánh giá";
                    
                        echo "<tr>
                            <td>$moc_name</td>
                            <td>";
                    
                        if ($has_submitted) {
                            echo "<a href='../student/" . $file_info['duong_dan_file'] . "' target='_blank'>Xem file</a>";
                        } else {
                            echo "Chưa có file";
                        }
                    
                        echo "</td>
                        <td>" . ($is_scored ? htmlspecialchars($score_note) : "Chưa đánh giá") . "</td>
                        <td>" . ($has_submitted ? "Đã nộp" : "Chưa nộp") . "</td>
                        <td>";

                        // Nếu chưa có file thì không hiển thị nút đánh giá
                        if ($has_submitted) {
                            echo "<a class='btn submit' href='admin_dashboard.php?page_layout=update_mocTienDo&id=$student_id&moc=$progress_id'>Sửa</a>";
                        } else {
                            echo "<span style='color: gray;'>Chưa nộp</span>";
                        }

                        echo "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>