
<?php
include 'connect.php';

// Kiểm tra nếu có ID được truyền vào
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Lấy thông tin người dùng cần sửa
    $stmt = $conn->prepare("SELECT * FROM nguoidung WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Nếu không tìm thấy người dùng, quay lại danh sách
    if (!$user) {
        header("Location: admin_dashboard.php?page_layout=user_list");
        exit();
    }

    // Lấy danh sách chuyên ngành
    $sql_major = "SELECT id, ten_chuyennganh FROM chuyennganh";
    $result_major = $conn->query($sql_major);
}

// Xử lý khi form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $role = $_POST['role'];
    $user_id = trim($_POST['user_id']);
    $major = $_POST['major'] ?? NULL;

    // Nếu có mật khẩu mới, cập nhật
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE nguoidung SET ho_ten = ?, email = ?, mat_khau = ?, ngay_sinh = ?, gioi_tinh = ?, so_dien_thoai = ?, dia_chi = ?, vaitro = ?, ma_so_nguoidung = ?, id_chuyennganh = ? WHERE id = ?");
        $stmt->bind_param("ssssssssssi", $full_name, $email, $password, $birth_date, $gender, $phone, $address, $role, $user_id, $major, $id);
    } else {
        $stmt = $conn->prepare("UPDATE nguoidung SET ho_ten = ?, email = ?, ngay_sinh = ?, gioi_tinh = ?, so_dien_thoai = ?, dia_chi = ?, vaitro = ?, ma_so_nguoidung = ?, id_chuyennganh = ? WHERE id = ?");
        $stmt->bind_param("sssssssssi", $full_name, $email, $birth_date, $gender, $phone, $address, $role, $user_id, $major, $id);
    }

    if ($stmt->execute()) {
        if ($role === "giangvien") {
            header("Location: admin_dashboard.php?page_layout=teacher_list&status=success");
        } else {
            header("Location: admin_dashboard.php?page_layout=student_list&status=success");
        }
        exit();
    }    
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập Nhật Người Dùng</title>
    <link rel="stylesheet" href="../assests/css/reset.css">
    <link rel="stylesheet" href="../assest/css/add_user.css">
</head>
<body>
  <div class="container">
    <div class="content">
        <h2 class="content-title">Cập Nhật Người Dùng</h2>
        <form class="user-form" action="" method="post">
            <div class="form-group">
                <label for="full_name">Họ và tên:</label>
                <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($user['ho_ten']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu (để trống nếu không đổi):</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="form-group">
                <label for="birth_date">Ngày sinh:</label>
                <input type="date" id="birth_date" name="birth_date" value="<?= $user['ngay_sinh'] ?>" required>
            </div>

            <div class="form-group">
                <label for="gender">Giới tính:</label>
                <select id="gender" name="gender" required>
                    <option value="Nam" <?= $user['gioi_tinh'] == 'Nam' ? 'selected' : '' ?>>Nam</option>
                    <option value="Nu" <?= $user['gioi_tinh'] == 'Nu' ? 'selected' : '' ?>>Nữ</option>
                    <option value="Khac" <?= $user['gioi_tinh'] == 'Khac' ? 'selected' : '' ?>>Khác</option>
                </select>
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại:</label>
                <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($user['so_dien_thoai']) ?>" required>
            </div>

            <div class="form-group">
                <label for="address">Địa chỉ:</label>
                <input type="text" id="address" name="address" value="<?= htmlspecialchars($user['dia_chi']) ?>" required>
            </div>

            <div class="form-group">
                <label for="role">Vai trò:</label>
                <select id="role" name="role" required>
                    <option value="sinhvien" <?= $user['vaitro'] == 'sinhvien' ? 'selected' : '' ?>>Sinh viên</option>
                    <option value="giangvien" <?= $user['vaitro'] == 'giangvien' ? 'selected' : '' ?>>Giảng viên</option>
                </select>
            </div>

            <div class="form-group">
                <label for="user_id">Mã số:</label>
                <input type="text" id="user_id" name="user_id" value="<?= htmlspecialchars($user['ma_so_nguoidung']) ?>" required>
            </div>

            <div class="form-group" id="student-fields">
                <label for="major">Chuyên ngành:</label>
                <select id="major" name="major">
                    <?php while ($row = $result_major->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>" <?= $user['id_chuyennganh'] == $row['id'] ? 'selected' : '' ?>><?= $row['ten_chuyennganh'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn-submit">Lưu</button>
            <a href="admin_dashboard.php?page_layout=student_list" class="btn-cancel">Hủy</a>
        </form>
    </div>
  </div>
</body>
</html>
