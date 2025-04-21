<?php 
include 'connect.php';
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

if (!empty($keyword)) {
    $searchTerm = "%$keyword%";
    $sql = "SELECT nguoidung.*, chuyennganh.ten_chuyennganh 
            FROM nguoidung 
            LEFT JOIN chuyennganh ON nguoidung.id_chuyennganh = chuyennganh.id
            WHERE (
            nguoidung.ma_so_nguoidung LIKE ?
            OR nguoidung.ho_ten LIKE ?
            )";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss",
        $searchTerm, $searchTerm
    );
    $stmt->execute();
    $result = $stmt->get_result();

    // Nếu có kết quả tìm kiếm, hiển thị bảng
    if ($result->num_rows > 0) {
        echo "<table class= 'table-search' border='1'>";
        echo "<tr><th>Mã số</th><th>Họ tên</th><th>Email</th><th>Điện thoại</th><th>Ngày sinh</th><th>Giới tính</th><th>Địa chỉ</th><th>Chuyên ngành</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row['ma_so_nguoidung']."</td>";
            echo "<td>".$row['ho_ten']."</td>";
            echo "<td>".$row['email']."</td>";
            echo "<td>".$row['so_dien_thoai']."</td>";
            echo "<td>".$row['ngay_sinh']."</td>";
            echo "<td>".$row['gioi_tinh']."</td>";
            echo "<td>".$row['dia_chi']."</td>";
            echo "<td>".$row['ten_chuyennganh']."</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='no-results'>Không tìm thấy kết quả nào.</p>";
    }
}

function isActive($name) {
    return (isset($_GET['page_layout']) && $_GET['page_layout'] === $name) ? 'active' : '';
}

?>



<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trang Chủ Quản Lý Sinh Viên HUMG</title>
  <link
      rel="Website icon"
      type="png"
      href="../assest/img/Logo_Truong_Dai_hoc_Mo_-_Dia_chat.jpg"/>
  <link rel="stylesheet" href="../assests/css/reset.css">
  <link rel="stylesheet" href="../assest/css/add_user.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <style>
    ol,ul {
      padding: 0 !important;
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <nav class="sidebar">
      <h2 class="sidebar-title">Menu</h2>
      <ul class="sidebar-menu">
        <li style="display: none;" class="menu-item"><a href="admin_dashboard.php?page_layout=add_major" class="menu-link">Thêm chuyên ngành</a></li>
        <li class="menu-item"><a href="admin_dashboard.php?page_layout=major" class="menu-link <?= isActive('major') ?>"  ><i class="bi bi-laptop"></i> Danh sách chuyên ngành</a></li>
        <li style="display: none;" class="menu-item"><a href="admin_dashboard.php?page_layout=add_user" class="menu-link">Thêm người dùng</a></li>
        <li class="menu-item"><a href="admin_dashboard.php?page_layout=teacher_list" class="menu-link <?= isActive('teacher_list') ?>"  ><i class="bi bi-person-lines-fill"></i><Dialog></Dialog> Danh sách giảng viên</a></li>
        <li class="menu-item"><a href="admin_dashboard.php?page_layout=student_list" class="menu-link <?= isActive('student_list') ?>" ><i class="bi bi-person-circle"></i> Danh sách sinh viên</a></li>
        <li class="menu-item"><a href="admin_dashboard.php?page_layout=mocTienDo_list" class="menu-link <?= isActive('mocTienDo_list') ?>"><i class="bi bi-card-list"></i> Danh sách mốc tiến độ</a></li>
        <li class="menu-item"><a href="admin_dashboard.php?page_layout=logout" class="menu-link"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a></li>
      </ul>
    </nav>

    <!-- Nội dung chính -->
    <div class="content">
    <div class="content-title_container">
        <!-- form tìm kiếm -->
        <?php
        $page_layout = isset($_GET['page_layout']) ? $_GET['page_layout'] : '';
        if ($page_layout !== 'add_major' && $page_layout !== 'add_user') { 
            ?>
            <form id="search-form" class="search" action="admin_dashboard.php" method="GET">
            <input type="text" name="keyword" placeholder="Tìm kiếm..." required>
                <button type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            <?php
        }
        ?>
        </div>
    
      <!-- <?php
      session_start();
      if (!isset($_SESSION['nguoidung'])) {
        header('location: ../login.php');
      }
      ?> -->

      <?php
      include('connect.php');
      if (isset($_GET['page_layout'])) {
        switch ($_GET['page_layout']) {
          case "major":
            include('major.php');
            break;
          case "add_major":
            include('add_major.php');
            break;
          case "update_major":
            include('update_major.php');
            break;
          case "process_delete-major":
            include('process_delete-major.php');
            break;
          case "teacher_list":
            include('teacher_list.php');
            break;
            case "update_teacher":
              include('update_teacher.php');
              break;
          case "student_list":
            include('student_list.php');
            break;
          case "add_user":
            include('add_user.php');
            break;
          case "update_student":
            include('update_user.php');
            break;
          case "delete_user_process":
            include('delete_user_process.php');
            break;
          case "mocTienDo_list":
            include('mocTienDo_list.php');
            break;
          case "mocTienDo":
            include('mocTienDo.php');
            break;
          case "logout":
            session_destroy();
            session_unset();
            header('location: ../login.php');
            break;
        }
      }
      ?>
    </div>
  </div>

<script></script>  
</body>

</html>