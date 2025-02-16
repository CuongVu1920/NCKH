<?php
include('connect.php'); // Kết nối database

// Kiểm tra nếu có ID được truyền vào
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Kiểm tra xem chuyên ngành có đang được sử dụng trong bảng khác không
    $check_sql = "SELECT id FROM nguoidung WHERE id_chuyennganh = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("i", $id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // Nếu chuyên ngành đang được sử dụng, không cho phép xóa
        header("Location: admin_dashboard.php?page_layout=major&status=error");
        exit();
    }

    // Nếu không bị ràng buộc, tiến hành xóa
    $sql = "DELETE FROM chuyennganh WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?page_layout=major&status=success");
        exit();
    } else {
        header("Location: admin_dashboard.php?page_layout=major&status=fail");
        exit();
    }
} else {
    header("Location: admin_dashboard.php?page_layout=major");
    exit();
}
?>
