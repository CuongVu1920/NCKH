<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Đề Tài</title>
    <link rel="stylesheet" href="../assests/css/reset.css">
    <link rel="stylesheet" href="../assest/css/add_topic.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->

        <!-- Nội dung chính -->
        <div class="content">
            <h2 class="content-title">Thêm Đề Tài</h2>
            
            <!-- Form thêm đề tài -->
            <form class="add-detai-form" action="add_topic_process.php" method="POST">
                <label for="ten_detai">Tên Đề Tài:</label>
                <input type="text" id="ten_detai" name="ten_detai" required>

                <label for="mo_ta">Mô Tả:</label>
                <textarea id="mo_ta" name="mo_ta" rows="4" required></textarea>

                <button type="submit" class="btn submit">Thêm Đề Tài</button>
            </form>
        </div>
    </div>
</body>
</html> 