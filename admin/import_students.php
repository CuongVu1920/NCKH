<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
include 'connect.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['excelFile']) || $_FILES['excelFile']['error'] !== UPLOAD_ERR_OK) {
        die('Lỗi upload file');
    }

    $inputFileName = $_FILES['excelFile']['tmp_name'];
    $spreadsheet = IOFactory::load($inputFileName);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();

    // Lấy dữ liệu từ dòng 12 trở đi (index 11)
    $rows = array_slice($rows, 11);

    $successCount = 0;
    $errorCount = 0;
    $errors = [];
    $default_password = '123'; // Mật khẩu mặc định
    $default_role = 'sinhvien';   // Vai trò mặc định

    foreach ($rows as $row) {
        // Nếu không có mã số SV thì bỏ qua
        if (empty($row[1])) continue;

        $ma_sv = $row[1];           // Mã SV (B)
        $ho_ten = $row[2];          // Họ và tên (C)
        $email = $row[3];           // Email (D)
        $ngay_sinh_raw = $row[4];   // Ngày sinh (E)
        $ngay_sinh = null;
        
        // Xử lý định dạng ngày sinh
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $ngay_sinh_raw, $matches)) {
            $ngay_sinh = $matches[3] . '-' . str_pad($matches[2], 2, '0', STR_PAD_LEFT) . '-' . str_pad($matches[1], 2, '0', STR_PAD_LEFT);
        } else {
            $ngay_sinh = $ngay_sinh_raw; // Nếu không đúng định dạng thì giữ nguyên
        }
        
        $gioi_tinh = $row[5];       // Giới tính (F)
        $so_dien_thoai = $row[6];   // Số điện thoại (G)
        $dia_chi = $row[7];         // Địa chỉ (H)
        $chuyen_nganh = $row[8];    // Chuyên ngành (I)

        // Kiểm tra mã số sinh viên đã tồn tại chưa
        $check_sql = "SELECT id FROM nguoidung WHERE ma_so_nguoidung = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $ma_sv);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $errorCount++;
            $errors[] = "Mã số sinh viên $ma_sv đã tồn tại";
            continue;
        }

        // Lấy ID chuyên ngành
        $chuyennganh_sql = "SELECT id FROM chuyennganh WHERE ten_chuyennganh = ?";
        $chuyennganh_stmt = $conn->prepare($chuyennganh_sql);
        $chuyennganh_stmt->bind_param("s", $chuyen_nganh);
        $chuyennganh_stmt->execute();
        $chuyennganh_result = $chuyennganh_stmt->get_result();
        $chuyennganh_row = $chuyennganh_result->fetch_assoc();
        $id_chuyennganh = $chuyennganh_row ? $chuyennganh_row['id'] : null;

        if ($id_chuyennganh === null) {
            $errorCount++;
            $errors[] = "Chuyên ngành '$chuyen_nganh' không tồn tại trong hệ thống.";
            continue;
        }

        // Insert sinh viên với mật khẩu và vai trò mặc định
        $sql = "INSERT INTO nguoidung (ma_so_nguoidung, ho_ten, email, matkhau, so_dien_thoai, ngay_sinh, gioi_tinh, dia_chi, id_chuyennganh, vaitro) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssss", $ma_sv, $ho_ten, $email, $default_password, $so_dien_thoai, $ngay_sinh, $gioi_tinh, $dia_chi, $id_chuyennganh, $default_role);

        if ($stmt->execute()) {
            $successCount++;
        } else {
            $errorCount++;
            $errors[] = "Lỗi khi thêm sinh viên $ma_sv: " . $stmt->error;
        }
    }

    $message = "Import hoàn tất: $successCount sinh viên được thêm thành công";
    if ($errorCount > 0) {
        $_SESSION['import_errors'] = $errors;
        header("Location: admin_dashboard.php?page_layout=student_list");
        exit();
    } else {
        header("Location: admin_dashboard.php?page_layout=student_list&message=" . urlencode($message));
        exit();
    }
} else {
    header("Location: admin_dashboard.php?page_layout=student_list");
    exit();
}

