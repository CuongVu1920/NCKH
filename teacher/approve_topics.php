<?php
include('connect.php');

    if (!isset($_SESSION['nguoidung']) || $_SESSION['nguoidung']['vaitro'] != 'giangvien') {
        echo "<script>alert('Bạn không có quyền truy cập!'); window.location.href='login.php';</script>";
        exit();
    }

    $teacher_id = $_SESSION['nguoidung']['id'];

// Lấy danh sách đề tài chờ duyệt
    $sql = "SELECT chondetai.id, 
                    nguoidung.id AS ma_sinhvien, 
                    nguoidung.ho_ten AS ten_sinhvien, 
                    chuyennganh.ten_chuyennganh AS chuyen_nganh, 
                    detai_giangvien.ten_de_tai, 
                    chondetai.trang_thai 
            FROM chondetai 
            JOIN nguoidung ON chondetai.id_sinhvien = nguoidung.id 
            JOIN chuyennganh ON nguoidung.id_chuyennganh = chuyennganh.id 
            JOIN detai_giangvien ON chondetai.id_detai = detai_giangvien.id
            WHERE chondetai.id_giangvien = '$teacher_id' AND chondetai.trang_thai = 'cho_duyet'";


    $result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duyệt Đề Tài</title>
    <link rel="stylesheet" href="../assests/css/reset.css">
    <link rel="stylesheet" href="../assests/css/approve.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<style>
    /* Reset CSS */

    /* Container */
    .container {
        width: 100%;
        max-width: 100%;
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* Title */
    .content-title {
        font-size: 24px;
        font-weight: bold;
        color: #4a90e2;
        text-align: center;
        margin-bottom: 20px;
    }

    /* Table */
    .detai-table {
        width: 100%;
        max-width: 1200px;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .detai-table th, .detai-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    /* Header styling */
    .detai-table th {
        background-color: #4a90e2;
        color: white;
        font-size: 16px;
    }

    /* Row styling */
    .detai-table td {
        font-size: 14px;
        color: #333;
    }

    /* Hover effect */
    .detai-table tr:hover {
        background-color: #f1f1f1;
    }

    /* Button styles */
    .btn {
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .accept {
        background-color: #4CAF50;
        color: white;
    }

    .accept:hover {
        background-color: #45a049;
    }

    .reject {
        background-color: #f44336;
        color: white;
    }

    .reject:hover {
        background-color: #e53935;
    }

    /* Fade out effect when row is deleted */
    .detai-table tr {
        transition: opacity 1s ease;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .detai-table th, .detai-table td {
            padding: 8px 10px;
        }

        .content-title {
            font-size: 20px;
        }
    }

</style>
<body>
    <div class="container">
        <h2 class="content-title">Danh sách đề tài chờ duyệt</h2>
        <table class="detai-table">
            <thead>
                <tr>
                    <th>Mã SV</th>
                    <th>Sinh viên</th>
                    <th>Chuyên ngành</th>
                    <th>Đề tài</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr id="row_<?php echo $row['id']; ?>">
                        <td><?php echo $row['ma_sinhvien']; ?></td>
                        <td><?php echo $row['ten_sinhvien']; ?></td>
                        <td><?php echo $row['chuyen_nganh']; ?></td>
                        <td><?php echo $row['ten_de_tai']; ?></td>
                        <td id="status_<?php echo $row['id']; ?>"><?php echo $row['trang_thai']; ?></td>
                        <td>
                            <button onclick="approveTopic(<?php echo $row['id']; ?>)" class="btn accept">Chấp nhận</button>
                            <button onclick="rejectTopic(<?php echo $row['id']; ?>)" class="btn reject">Từ chối</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

<script>
    function approveTopic(id) {
        if (confirm("Bạn có chắc muốn chấp nhận đề tài này?")) {
            $.ajax({
                url: 'process_approval.php',
                type: 'POST',
                data: { id: id, action: 'approve' },
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        $("#status_" + id).text("Đồng ý");
                        $("#row_" + id).fadeOut(1000);
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    }

    function rejectTopic(id) {
        let reason = prompt("Nhập lý do từ chối:");
        if (reason) {
            $.ajax({
                url: 'process_approval.php',
                type: 'POST',
                data: { id: id, action: 'reject', reason: reason },
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        $("#row_" + id).fadeOut(1000);
                    } else {
                        alert(response.message);x
                    }
                }
            });
        }
    }
</script>
</body>
</html>

<?php mysqli_close($conn); ?>
