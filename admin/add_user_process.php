<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password']; // Không mã hóa theo yêu cầu
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $role = $_POST['role'];
    $user_id = trim($_POST['user_id']);
    $major_id = isset($_POST['major']) ? $_POST['major'] : NULL;

    // Kiểm tra xem mã số hoặc email đã tồn tại chưa
    $check_sql = "SELECT * FROM nguoidung WHERE ma_so_nguoidung = ? OR email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ss", $user_id, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Lỗi: Mã số hoặc email đã tồn tại!";
    } else {
        // Nếu là sinh viên hoặc giảng viên, kiểm tra chuyên ngành
        if ($role == "sinhvien" || $role == "giangvien") {
            $major_check_sql = "SELECT id FROM chuyennganh WHERE id = ?";
            $stmt_major = $conn->prepare($major_check_sql);
            $stmt_major->bind_param("i", $major_id);
            $stmt_major->execute();
            $major_result = $stmt_major->get_result();

            if ($major_result->num_rows == 0) {
                echo "Lỗi: Chuyên ngành không hợp lệ!";
                exit();
            }
        }

        // Thêm người dùng vào bảng `nguoidung`
        $insert_sql = "INSERT INTO nguoidung (ma_so_nguoidung, ho_ten, email, matkhau, gioi_tinh, ngay_sinh, so_dien_thoai, dia_chi, vaitro, id_chuyennganh) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($insert_sql);
        $stmt_insert->bind_param("sssssssssi", $user_id, $full_name, $email, $password, $gender, $birth_date, $phone, $address, $role, $major_id);
        $stmt_insert->execute();

        // Chuyển hướng sau khi thêm thành công
        if ($role == "giangvien") {
            header("Location: admin_dashboard.php?page_layout=teacher_list");
        } elseif ($role == "sinhvien") {
            header("Location: admin_dashboard.php?page_layout=student_list");
        }
        exit();
    }
}

$conn->close();
?>
