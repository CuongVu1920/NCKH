<?php
include('connect.php');

$student_id = $_SESSION['nguoidung']['id'];

$sql_check_topic = "SELECT id,ten_do_an FROM doan WHERE id_sinhvien = ?";
$stmt_check_topic = $conn->prepare($sql_check_topic);
$stmt_check_topic->bind_param("i", $student_id);
$stmt_check_topic->execute();
$result_check_topic = $stmt_check_topic->get_result();

if (mysqli_num_rows($result_check_topic) == 0) {
    echo "<script>alert('Bạn chưa có đề tài nào được duyệt!'); window.location.href='student_dashboard.php?page_layout=student_info';</script>";
    exit();
}

$topic_data = mysqli_fetch_assoc($result_check_topic);

// Kiểm tra nếu có dữ liệu, và sau đó lấy thông tin cần thiết
if ($topic_data) {
    $topic_id = $topic_data['id'];
    $topic_name = $topic_data['ten_do_an'];
}

// Kiểm tra xem mốc tiến độ đã tồn tại trong bảng moctiendo chưa
$sql_check_moc = "SELECT id FROM moctiendo WHERE id_doan = ?";
$stmt_check_moc = $conn->prepare($sql_check_moc);
$stmt_check_moc->bind_param("i", $topic_id);
$stmt_check_moc->execute();
$result_check_moc = $stmt_check_moc->get_result();

if (mysqli_num_rows($result_check_moc) == 0) {
    // Thêm các mốc tiến độ vào bảng moctiendo
    $moc_tien_do = [
        'Báo cáo đề cương',
        'Báo cáo thiết kế',
        'Báo cáo thử nghiệm',
        'Báo cáo tổng kết'
    ];

    $sql_insert_moc = "INSERT INTO moctiendo (id_doan, ten_moc, han_chot) VALUES (?, ?, ?)";
    $stmt_insert_moc = $conn->prepare($sql_insert_moc);

    $today = date('Y-m-d');  // Lấy ngày hiện tại làm hạn chót (bạn có thể thay đổi giá trị này)

    // Lặp qua từng mốc tiến độ và thêm vào bảng moctiendo
    foreach ($moc_tien_do as $moc_name) {
        $stmt_insert_moc->bind_param("iss", $topic_id, $moc_name, $today);
        if (!$stmt_insert_moc->execute()) {
            echo "<script>alert('Lỗi khi thêm mốc tiến độ: " . $stmt_insert_moc->error . "'); window.history.back();</script>";
            exit();
        }
    }

}


?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Đề Tài</title>
    <link rel="stylesheet" href="../assests/css/reset.css">
    <link rel="stylesheet" href="../assest/css/moctiendo.css?v=<?php echo time(); ?>">
</head>

<body>
    <div class="container">
        <div class="content">
            <div class="heading">
                <h2 class="content-title">Mốc Tiến Độ Đồ Án</h2>
                <h2 class="content-title">Tên Đồ Án: <?php echo $topic_name ?> </h2>
            </div>

            <table class="progress-table">
                <thead>
                    <tr>
                        <th>Mốc Tiến Độ</th>
                        <th>Link nộp bài</th>
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
                        // Kiểm tra xem sinh viên đã nộp bài cho mốc này chưa
                        $sql_check_submission = "SELECT * FROM bainop WHERE id_doan = (SELECT id FROM doan WHERE id_sinhvien = ?) AND id_moc = ?";
                        $stmt = $conn->prepare($sql_check_submission);
                        $stmt->bind_param("ii", $student_id, $progress_id);
                        $stmt->execute();
                        $result_submission = $stmt->get_result();

                        $has_submitted = $result_submission->num_rows > 0;
                        $submission_status = $has_submitted ? "Đã nộp" : "Chưa nộp";
                        $disabled = $has_submitted ? 'disabled' : '';

                        $sql_check_score = "SELECT ghi_chu FROM diemdoan WHERE id_doan = ? AND id_moc = ?";
                        $stmt_score = $conn->prepare($sql_check_score);
                        $stmt_score->bind_param("ii", $topic_id, $progress_id);
                        $stmt_score->execute();
                        $result_score = $stmt_score->get_result();
                        $score_note = $result_score->num_rows > 0 ? $result_score->fetch_assoc()['ghi_chu'] : "Chưa đánh giá";

                        echo "<tr>
                                    <td>$moc_name</td>
                                    <td>";

                        if ($has_submitted) {
                            $file_info = $result_submission->fetch_assoc();
                            echo "<a href='" . $file_info['duong_dan_file'] . "' target='_blank'>Xem file</a>";
                        } else {
                            echo "<form action='upload.php' method='post' enctype='multipart/form-data'>
                                        <input type='hidden' name='student_id' value='$student_id'>
                                        <input type='hidden' name='progress' value='$progress_id'>
                                        <input type='file' name='file' required>
                                    </form>";
                        }

                        echo "</td>
                                <td>$score_note</td>
                                <td>$submission_status</td>
                                <td>";

                        if (!$has_submitted) {
                            echo "<a class='btn submit' href='javascript:void(0)' onclick='submitForm($progress_id)'>Nộp bài</a>";
                        }

                        echo "</td>
                                </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>

<script>
    function submitForm(progress_id) {
        const form = document.querySelector(`form input[name="progress"][value="${progress_id}"]`).closest("form");
        form.submit();
    }
</script>