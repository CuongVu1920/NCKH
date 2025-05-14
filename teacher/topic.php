<?php
include('connect.php'); 

    // Truy vấn danh sách đề tài từ cơ sở dữ liệu
    $id_giangvien = $_SESSION['nguoidung']['id']; // Lấy ID giảng viên từ session

    // Lấy tổng số đề tài
    $sql = "SELECT dg.*, nd.ma_so_nguoidung, cn.ma_chuyennganh
        FROM detai_giangvien dg
        JOIN nguoidung nd ON dg.id_giangvien = nd.id
        LEFT JOIN chuyennganh cn ON nd.id_chuyennganh = cn.id
        WHERE dg.id_giangvien = '$id_giangvien'";
    $result = mysqli_query($conn, $sql);

    // phân trang 
    $result_per_page = 7;

    $number_of_result = mysqli_num_rows($result);
    $number_of_page = ceil($number_of_result/$result_per_page);
    $page = isset($_GET['idpage']) ? (int)$_GET['idpage'] : 1;
    if ($page < 1) {
    $page = 1; // Đảm bảo page không nhỏ hơn 1
    }

    $this_page_first_result = ($page-1)*$result_per_page;

    // Truy vấn lại với LIMIT
    $sql = "SELECT dg.*, nd.ma_so_nguoidung, cn.ma_chuyennganh
            FROM detai_giangvien dg
            JOIN nguoidung nd ON dg.id_giangvien = nd.id
            LEFT JOIN chuyennganh cn ON nd.id_chuyennganh = cn.id
            WHERE dg.id_giangvien = '$id_giangvien'
            LIMIT $this_page_first_result, $result_per_page";
    $result = mysqli_query($conn, $sql);

?>




<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Đề Tài</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assests/css/reset.css">
    <link rel="stylesheet" href="../assest/css/topic.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->

        <!-- Nội dung chính -->
        <div class="content">

        <div class="content-add_student">
               <h2 class="content-title">Danh sách đề tài</h2>
                <a href="teacher_dashboard.php?page_layout=add_topic" class="add_user"> <i style="margin-right: 10px;" class="bi bi-plus-square"></i> Thêm Đề Tài</a>
            </div>
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
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                        $ma_chuyennganh = $row['ma_chuyennganh'] ?? '???';
                        $ma_giangvien = $row['ma_so_nguoidung'] ?? '???';
                        $id_detai = str_pad($row['id'], 2, '0', STR_PAD_LEFT);
                        $ma_detai = "$ma_chuyennganh - $ma_giangvien - $id_detai";

                        echo "<tr>";
                        echo "<td>$ma_detai</td>";
                        echo "<td>" . $row['ten_de_tai'] . "</td>";
                        echo "<td>" . $row['mo_ta'] . "</td>";
                        echo "<td>" . ($row['trang_thai'] == 'con_trong' ? 'Còn trống' : 'Đã chọn') . "</td>";
                        echo "<td>
                                <a href='edit_topic.php?id=" . $row['id'] . "' class='btn edit'>Chỉnh sửa</a>
                                <a href='delete_topic.php?id=" . $row['id'] . "' class='btn delete' onclick='return confirm(\"Bạn có chắc muốn xóa không?\")'>Xóa</a>
                              </td>";
                        echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align: center;'>Không có đề tài nào.</td></tr>";
                    }   
                    ?>
                </tbody>
            </table>
        </div>


         
    <?php
    // Đảm bảo biến $current_page và $number_of_page không bị lỗi
    $current_page = isset($_GET['idpage']) ? (int)$_GET['idpage'] : 1;
    if ($current_page < 1) {
        $current_page = 1;
    }
    if ($current_page > $number_of_page) {
        $current_page = $number_of_page;
    }
    ?>

 <nav>
    <ul class="pagination justify-content-center">
        <?php if ($current_page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="teacher_dashboard.php?page_layout=topic&idpage=1">Đầu</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="teacher_dashboard.php?page_layout=topic&idpage=<?php echo ($current_page - 1); ?>">Trước</a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $number_of_page; $i++): ?>
            <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                <a class="page-link" href="teacher_dashboard.php?page_layout=topic&idpage=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($current_page < $number_of_page): ?>
            <li class="page-item">
                <a class="page-link" href="teacher_dashboard.php?page_layout=topic&idpage=<?php echo ($current_page + 1); ?>">Tiếp</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="teacher_dashboard.php?page_layout=topic&idpage=<?php echo $number_of_page; ?>">Cuối</a>
            </li>
        <?php endif; ?>
    </ul>
</nav> 
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
?>