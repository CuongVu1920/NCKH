<?php
// process_add_hocky.php
require_once 'connect.php'; // Kết nối với database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy giá trị từ form
    $ten_hocky = $_POST['ten_hocky'];
    $nam_hoc = $_POST['nam_hoc'];
    $trang_thai = $_POST['trang_thai'];

    // Thêm dữ liệu vào bảng hocky
    $sql = "INSERT INTO hocky (ten_hocky, nam_hoc, trang_thai) 
            VALUES ('$ten_hocky', '$nam_hoc', '$trang_thai')";

    if (mysqli_query($conn, $sql)) {
        // Hiển thị thông báo và chuyển hướng sau 2 giây
        echo "<script>
                alert('Học kỳ đã được thêm thành công!');
                window.location.href = 'admin_dashboard.php?page_layout=hocky'; // Điều hướng đến trang hiển thị học kỳ
              </script>";
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
