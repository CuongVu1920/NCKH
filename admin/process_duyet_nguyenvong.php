<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'approved_') === 0 && $value != "0") {
            $id_sinhvien = str_replace('approved_', '', $key);
            $id_giangvien = intval($value);

            // Lấy học kỳ hiện tại
            $query_hocky = "SELECT id FROM hocky WHERE trang_thai = 'Hoạt động' LIMIT 1";
            $result_hocky = mysqli_query($conn, $query_hocky);
            $row_hocky = mysqli_fetch_assoc($result_hocky);

            if ($row_hocky) {
                $id_hocky = $row_hocky['id'];

                // Kiểm tra số sinh viên mà giảng viên đã nhận trong học kỳ này
                $check_limit = "SELECT COUNT(*) AS total FROM huongdan WHERE id_giangvien = ? AND id_hocky = ?";
                $stmt_limit = mysqli_prepare($conn, $check_limit);
                mysqli_stmt_bind_param($stmt_limit, "ii", $id_giangvien, $id_hocky);
                mysqli_stmt_execute($stmt_limit);
                $result_limit = mysqli_stmt_get_result($stmt_limit);
                $row_limit = mysqli_fetch_assoc($result_limit);
                $so_sinh_vien = $row_limit['total'];

                if ($so_sinh_vien < 5) { // Giảng viên chỉ nhận tối đa 5 sinh viên
                    // Kiểm tra xem sinh viên đã có giảng viên hướng dẫn chưa
                    $check_exist = "SELECT id FROM huongdan WHERE id_sinhvien = ? AND id_hocky = ?";
                    $stmt = mysqli_prepare($conn, $check_exist);
                    mysqli_stmt_bind_param($stmt, "ii", $id_sinhvien, $id_hocky);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) == 0) {
                        // Thêm vào bảng huongdan
                        $insert_huongdan = "INSERT INTO huongdan (id_sinhvien, id_giangvien, id_hocky) VALUES (?, ?, ?)";
                        $stmt_insert = mysqli_prepare($conn, $insert_huongdan);
                        mysqli_stmt_bind_param($stmt_insert, "iii", $id_sinhvien, $id_giangvien, $id_hocky);
                        mysqli_stmt_execute($stmt_insert);

                        // Cập nhật trạng thái nguyện vọng thành "Đã duyệt"
                        $update_nv = "UPDATE nguyenvong SET trangthai = 'Đã duyệt' WHERE id_sinhvien = ? AND id_giangvien = ?";
                        $stmt_update = mysqli_prepare($conn, $update_nv);
                        mysqli_stmt_bind_param($stmt_update, "ii", $id_sinhvien, $id_giangvien);
                        mysqli_stmt_execute($stmt_update);

                        // Cập nhật trạng thái "Bị từ chối" cho các nguyện vọng khác của sinh viên
                        $reject_nv = "UPDATE nguyenvong SET trangthai = 'Bị từ chối' WHERE id_sinhvien = ? AND id_giangvien != ?";
                        $stmt_reject = mysqli_prepare($conn, $reject_nv);
                        mysqli_stmt_bind_param($stmt_reject, "ii", $id_sinhvien, $id_giangvien);
                        mysqli_stmt_execute($stmt_reject);
                    }

                    mysqli_stmt_close($stmt);
                }
                mysqli_stmt_close($stmt_limit);
            }
        }
    }

    // Đóng kết nối và quay lại trang duyệt nguyện vọng
    mysqli_close($conn);
    header("Location: approve_requests.php?success=1");
    exit();
} else {
    header("Location: approve_requests.php");
    exit();
}
?>
