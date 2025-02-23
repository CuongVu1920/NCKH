<?php
include('connect.php');

$student_id = $_SESSION['nguoidung']['id'];

$sql_topic = "SELECT ten_do_an FROM doan 
              WHERE id_sinhvien = '$student_id'";
$result_topic = mysqli_query($conn, $sql_topic);
$topic_name = mysqli_fetch_assoc($result_topic)['ten_do_an'];
$sql_check_topic = "SELECT id FROM doan WHERE id_sinhvien = '$student_id'";
$result_check_topic = mysqli_query($conn, $sql_check_topic);

if (mysqli_num_rows($result_check_topic) == 0) { 
    echo "<script>alert('Bạn chưa có đề tài nào được duyệt!'); window.location.href='student_dashboard.php?page_layout=student_info';</script>";
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

    <link rel="stylesheet" href="../assest/css/moctiendo.css">
</head>
<body>
    <div class="container">
        
        <div class="content">

            <div class="heading">
                <h2 class="content-title">Mốc Tiến Độ Đồ Án</h2>
                <h2 class="content-title"> Tên Đồ Án: <?php echo $topic_name ?> </h2>
            </div>
        <table class="progress-table">
            <thead>
                <tr>
                    <th>Mốc Tiến Độ</th>
                    <th>Link nộp bài</th>
                    <th>Điểm</th>
                    <th>Đánh Giá</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Báo cáo đề cương</td>
                    <td><form action="upload.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                <input type="hidden" name="progress" value="1">
                <input type="file" name="file" required>
            </form></td>
                    <td>-</td>
                    <td>Chưa đánh giá</td>
                    <td><a type="submit" class="btn submit">Nộp bài</a></td>
                </tr>
                <tr>
                    <td>Báo cáo thiết kế</td>
                    <td><form action="upload.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                <input type="hidden" name="progress" value="2">
                <input type="file" name="file" required>
            </form></td>
                    <td>-</td>
                    <td>Chưa đánh giá</td>
                    <td><a type="submit" class="btn submit">Nộp bài</a></td>
                </tr>
                <tr>
                    <td>Báo cáo thử nghiệm</td>
                    <td><form action="upload.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                <input type="hidden" name="progress" value="3">
                <input type="file" name="file" required>
            </form></td>
                    <td>-</td>
                    <td>Chưa đánh giá</td>
                    <td><a type="submit" class="btn submit">Nộp bài</a></td>
                </tr>
                <tr>
                    <td>Báo cáo tổng kết</td>
                    <td><form action="upload.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                <input type="hidden" name="progress" value="4">
                <input type="file" name="file" required>
            </form></td>
                    <td>-</td>
                    <td>Chưa đánh giá</td>
                    <td><a type="submit" class="btn submit">Nộp bài</a></td>
                </tr>
            </tbody>
        </table>
        </div>
    </div>
</body>
</html>

