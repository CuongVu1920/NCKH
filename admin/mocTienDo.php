<?php
include('connect.php');

$student_id = intval($_GET['id']); // Lấy ID sinh viên từ URL


$sql_topic = "SELECT ten_do_an FROM doan WHERE id_sinhvien = '$student_id'";
$result_topic = mysqli_query($conn, $sql_topic);
$topic_name = mysqli_fetch_array($result_topic);

$sql_student = "SELECT ho_ten , ma_so_nguoidung FROM nguoidung WHERE id = '$student_id'";
$result_student = mysqli_query($conn,$sql_student);
$student =mysqli_fetch_array($result_student);

$sql_bainop = "SELECT duong_dan_file FROM bainop WHERE id_sinhvien = '$student_id'";
$result_bainop = mysqli_query($conn,$sql_bainop);
$bainop = mysqli_fetch_array($result_bainop);

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
                <h2 class="content-title"> Tên Đồ Án: <?php echo $topic_name['ten_do_an']?? 'chưa có đề tài' ?> </h2>
                <h2 class="content-title"><?php echo "$student[ho_ten] - $student[ma_so_nguoidung]"  ?></h2>
            </div>
        <table class="progress-table">
            <thead>
                <tr>
                    <th>Mốc Tiến Độ</th>
                    <th>File bài làm</th>
                    <th>Điểm</th>
                    <th>Đánh Giá</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Báo cáo đề cương</td>
                    <td><?php echo $bainop['duong_dan_link'] ?? 'Trống' ?></td>
                    <td>-</td>
                    <td>Chưa đánh giá</td>
                    <td><a type="submit" class="btn submit">Sửa</a></td>
                </tr>
                <tr>
                    <td>Báo cáo thiết kế</td>
                    <td><?php echo $bainop['duong_dan_link'] ?? 'Trống' ?></td>
                    <td>-</td>
                    <td>Chưa đánh giá</td>
                    <td><a type="submit" class="btn submit">Sửa</a></td>
                </tr>
                <tr>
                    <td>Báo cáo thử nghiệm</td>
                    <td><?php echo $bainop['duong_dan_link'] ?? 'Trống' ?></td>
                    <td>-</td>
                    <td>Chưa đánh giá</td>
                    <td><a type="submit" class="btn submit">Sửa</a></td>
                </tr>
                <tr>
                    <td>Báo cáo tổng kết</td>
                    <td><?php echo $bainop['duong_dan_link'] ?? 'Trống' ?></td>
                    <td>-</td>
                    <td>Chưa đánh giá</td>
                    <td><a type="submit" class="btn submit">Sửa</a></td>
                </tr>
            </tbody>
        </table>
        </div>
    </div>
</body>
</html>

