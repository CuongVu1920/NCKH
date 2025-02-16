<?php
include('connect.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM detai_giangvien WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        echo "<script>alert('Không tìm thấy đề tài!'); window.location.href='topic.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Không tìm thấy ID đề tài!'); window.location.href='topic.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Đề Tài</title>
    <!-- Reset CSS -->
    <link rel="stylesheet" href="../assests/css/reset.css">

    <!-- Style CSS -->
    <link rel="stylesheet" href="../assest/css/add_topic.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->

        <!-- Nội dung chính -->
        <div class="content">
            <h2 class="content-title">Sửa Đề Tài</h2>
            
            <!-- Form thêm đề tài -->
            <form class="add-detai-form" action="update_topic_process.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                <label for="ten_detai">Tên Đề Tài:</label>
                <input type="text" id="ten_detai" name="ten_detai" value="<?php echo $row['ten_de_tai']; ?>" required>

                <label for="mo_ta">Mô Tả:</label>
                <textarea id="mo_ta" name="mo_ta" rows="4" required><?php echo $row['mo_ta']; ?></textarea>

                <label for="trang_thai">Trạng Thái:</label>
                <select id="trang_thai" name="trang_thai">
                    <option value="con_trong" <?php echo ($row['trang_thai'] == 'con_trong') ? 'selected' : ''; ?>>Còn trống</option>
                    <option value="da_chon" <?php echo ($row['trang_thai'] == 'da_chon') ? 'selected' : ''; ?>>Đã chọn</option>
                </select>

                <button type="submit" class="btn submit">Cập Nhật</button>
            </form>
        </div>
    </div>
</body>
</html> 