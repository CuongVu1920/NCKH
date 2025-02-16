<?php
include 'connect.php';

// Lấy danh sách chuyên ngành từ database
$sql_major = "SELECT id, ten_chuyennganh FROM chuyennganh";
$result_major = $conn->query($sql_major);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Người Dùng</title>
    <link rel="stylesheet" href="../assests/css/reset.css">
    <link rel="stylesheet" href="../assest/css/add_user.css">
</head>
<body>
  <div class="container">
    <div class="content">
        <h2 class="content-title">Thêm Người Dùng</h2>
        <form class="user-form" action="add_user_process.php" method="post">
            <div class="form-group">
                <label for="full_name">Họ và tên:</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="birth_date">Ngày sinh:</label>
                <input type="date" id="birth_date" name="birth_date" required>
            </div>

            <div class="form-group">
                <label for="gender">Giới tính:</label>
                <select id="gender" name="gender" required>
                    <option value="Nam">Nam</option>
                    <option value="Nu">Nữ</option>
                    <option value="Khac">Khác</option>
                </select>
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại:</label>
                <input type="tel" id="phone" name="phone" required>
            </div>

            <div class="form-group">
                <label for="address">Địa chỉ:</label>
                <input type="text" id="address" name="address" required>
            </div>

            <div class="form-group">
                <label for="role">Vai trò:</label>
                <select id="role" name="role" required>
                    <option value="sinhvien">Sinh viên</option>
                    <option value="giangvien">Giảng viên</option>
                </select>
            </div>

            <div class="form-group">
                <label for="user_id">Mã số:</label>
                <input type="text" id="user_id" name="user_id" placeholder="Mã sinh viên / Mã giảng viên" required>
            </div>

            <div class="form-group" id="student-fields">
                <label for="major">Chuyên ngành:</label>
                <select id="major" name="major">
                    <?php while ($row = $result_major->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>"><?= $row['ten_chuyennganh'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn-submit">Thêm</button>
        </form>
    </div>
  </div>
</body>
</html>
