<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Đề Tài</title>
    <link rel="stylesheet" href="../assests/css/reset.css">
    <link rel="stylesheet" href="../assest/css/chamDiem.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->

        <!-- Nội dung chính -->
        <div class="content">
            <h2 class="content-title">Chấm điểm</h2>
            
            <!-- Form thêm đề tài -->
            <form class="chamDiem-form" action="chamDiem_process.php" method="POST">
                <label for="nhapDiem">Nhập điểm:</label>
                <input type="number" id="diem" name="diem" required min="0" max="10" step="0.1">

                <label for="danhGia">Đánh giá:</label>
                <textarea id="danhGia" name="danhGia" rows="4" required></textarea>

                <button type="submit" class="btn submit">Nhập điểm</button>
            </form>
        </div>
    </div>
</body>
</html> 