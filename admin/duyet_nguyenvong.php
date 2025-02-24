<?php
include('connect.php');

// Lấy danh sách nguyện vọng của sinh viên và nhóm theo sinh viên
$sql = "SELECT nguyenvong.id_sinhvien, nguoidung.ho_ten AS sinhvien, 
        MAX(CASE WHEN nguyenvong.muc_uu_tien = '1' THEN nguoidung_2.ho_ten ELSE NULL END) AS ngv_1,
        MAX(CASE WHEN nguyenvong.muc_uu_tien = '1' THEN nguoidung_2.id ELSE NULL END) AS id_ngv_1,
        MAX(CASE WHEN nguyenvong.muc_uu_tien = '2' THEN nguoidung_2.ho_ten ELSE NULL END) AS ngv_2,
        MAX(CASE WHEN nguyenvong.muc_uu_tien = '2' THEN nguoidung_2.id ELSE NULL END) AS id_ngv_2,
        MAX(CASE WHEN nguyenvong.muc_uu_tien = '3' THEN nguoidung_2.ho_ten ELSE NULL END) AS ngv_3,
        MAX(CASE WHEN nguyenvong.muc_uu_tien = '3' THEN nguoidung_2.id ELSE NULL END) AS id_ngv_3
        FROM nguyenvong 
        JOIN nguoidung ON nguyenvong.id_sinhvien = nguoidung.id
        JOIN nguoidung AS nguoidung_2 ON nguyenvong.id_giangvien = nguoidung_2.id
        WHERE nguyenvong.trangthai = 'Chờ duyệt'
        GROUP BY nguyenvong.id_sinhvien, nguoidung.ho_ten";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duyệt Nguyện Vọng</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }
        .container {
            padding: 20px;
            border-radius: 8px;
        }
        h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .request-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .request-table th, .request-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .request-table th {
            background-color: #f4f4f4;
        }
        .request-table tr:hover {
            background-color: #f1f1f1;
        }
        .approve-select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            width: 100%;
        }
        .btn-submit {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
        }
        .btn-submit:hover {
            background-color: #218838;
        }
        .student-name {
            font-weight: bold;
            background-color: #e9ecef;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Duyệt Nguyện Vọng của Sinh Viên</h2>
        <form action="process_duyet_nguyenvong.php" method="POST">
            <table class="request-table">
                <thead>
                    <tr>
                        <th>Sinh viên</th>
                        <th>Nguyện vọng</th>
                        <th>Giảng viên</th>
                        <th>Chọn Giảng viên</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) {
                        // Mảng chứa tất cả các nguyện vọng
                        $nguyenvong = [
                            1 => ['name' => $row['ngv_1'], 'id' => $row['id_ngv_1']],
                            2 => ['name' => $row['ngv_2'], 'id' => $row['id_ngv_2']],
                            3 => ['name' => $row['ngv_3'], 'id' => $row['id_ngv_3']],
                        ];

                        // Kiểm tra số lượng sinh viên của từng giảng viên
                        $can_choose = [];
                        foreach ($nguyenvong as $key => $value) {
                            $sql_check_count = "SELECT COUNT(*) AS student_count 
                                                FROM huongdan 
                                                WHERE id_giangvien = ?";
                            $stmt_check = mysqli_prepare($conn, $sql_check_count);
                            mysqli_stmt_bind_param($stmt_check, 'i', $value['id']);
                            mysqli_stmt_execute($stmt_check);
                            mysqli_stmt_bind_result($stmt_check, $student_count);
                            mysqli_stmt_fetch($stmt_check);
                            mysqli_stmt_close($stmt_check);
                            $can_choose[$key] = $student_count < 5;
                        }
                    ?>
                        <tr>
                            <td class="student-name" rowspan="3"><?php echo $row['sinhvien']; ?></td>
                            <?php 
                                // Dùng vòng for để hiển thị các nguyện vọng
                                for ($i = 1; $i <= 3; $i++) { 
                                    $ngv_name = $nguyenvong[$i]['name'] ? $nguyenvong[$i]['name'] : 'Không có';
                                    $ngv_id = $nguyenvong[$i]['id'];
                                    $can_choose_ngv = $can_choose[$i];
                                    if ($i > 1) {
                                        echo "<tr>";
                                    }
                            ?>
                                <td>Nguyện vọng <?php echo $i; ?></td>
                                <td><?php echo $ngv_name; ?></td>
                                <?php if ($i == 1) { ?>
                                    <td rowspan="3">
                                        <select name="approved_<?php echo $row['id_sinhvien']; ?>" class="approve-select">
                                            <option value="0">Không chấp nhận</option>
                                            <?php 
                                                for ($j = 1; $j <= 3; $j++) {
                                                    $ngv_id = $nguyenvong[$j]['id'];
                                                    if ($ngv_id && $can_choose[$j]) {
                                                        echo "<option value='$ngv_id'>{$nguyenvong[$j]['name']}</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </td>
                                <?php } ?>
                            <?php 
                                    if ($i > 1) {
                                        echo "</tr>";
                                    }
                                }
                            ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <button type="submit" class="btn-submit">Duyệt Nguyện Vọng</button>
            <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
                <div class="alert alert-success">Nguyện vọng đã được duyệt thành công!</div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>

<?php
mysqli_close($conn);
?>
