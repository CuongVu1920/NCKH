<?php 
    session_start();
    if(!isset($_SESSION['nguoidung'])){
        header('location: ../login.php');
        exit();
    }

    include('connect.php');

    // Lấy ID sinh viên từ session
    $id_sinhvien = $_SESSION['nguoidung']['id'];

    // Kiểm tra xem sinh viên đã có giảng viên hướng dẫn chưa
    $stmt = $conn->prepare("SELECT id FROM huongdan WHERE id_sinhvien = ?");
    $stmt->bind_param("i", $id_sinhvien);
    $stmt->execute();
    $stmt->store_result();
    $has_teacher = $stmt->num_rows > 0; // True nếu đã có giảng viên

    function isActive($name) {
        return (isset($_GET['page_layout']) && $_GET['page_layout'] === $name) ? 'active' : '';
    }

    // phần tìm kiếm
    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
    if (!empty($keyword)) {
        $stmt_ch = $conn->prepare("SELECT id_chuyennganh FROM nguoidung WHERE id = ?");
        $stmt_ch->bind_param("i", $id_sinhvien);
        $stmt_ch->execute();
        $result_ch = $stmt_ch->get_result();
        $row_ch = $result_ch->fetch_assoc();
        $id_chuyennganh_sinhvien = $row_ch['id_chuyennganh'];

        $searchTerm = "%$keyword%";
        $sql = "SELECT nguoidung.*, chuyennganh.ten_chuyennganh 
                FROM nguoidung 
                LEFT JOIN chuyennganh ON nguoidung.id_chuyennganh = chuyennganh.id
                WHERE (nguoidung.ma_so_nguoidung LIKE ? OR nguoidung.ho_ten LIKE ?)
                AND nguoidung.vaitro = 'giangvien'
                AND nguoidung.id_chuyennganh = ?";
    
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $searchTerm, $searchTerm, $id_chuyennganh_sinhvien);    
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h2 
        style= '
          margin-left: 300px;
          margin-top: 100px;
          font-size:22px; 
          font-weight: bold; 
          font-family: Arial, sans-serif;
        '>Kết quả tìm kiếm: <strong>". $keyword . "</strong></h2>";
        echo "<form method='post' action='process_choice.php'>";
        echo "<table class= 'teacher-table' style= 'width:1032px; margin-left: 300px; margin-top: 15px' >";
        echo "<thead>";
        echo "<tr>
        <th>Mã số</th>
        <th>Họ tên</th>
        <th>Chuyên ngành</th>
        <th>Email</th>
        <th>Điện thoại</th>
        <th>Chọn</th>
        </tr>";
        echo " </thead>";

        while ($row = $result->fetch_assoc()) {
            echo "<tbody>";
            echo "<tr>";
            echo "<td>".$row['ma_so_nguoidung']."</td>";
            echo "<td>".$row['ho_ten']."</td>";
            echo "<td>".$row['ten_chuyennganh']."</td>";
            echo "<td>".$row['email']."</td>";
            echo "<td>".$row['so_dien_thoai']."</td>";
            echo "<td><input type='checkbox' name='teachers[]' value='" . $row['id'] . "' class='teacher-checkbox'></td>";
            echo "</tr>";
            echo "</tbody>";
        }
        echo "</table>";
        echo "<button type='submit' class='btn-submit'>Gửi nguyện vọng</button>";
        echo "</form>";
    } else {
        echo "<p class='no-results'>Không tìm thấy kết quả nào.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin sinh viên</title>
    <link
      rel="Website icon"
      type="png"
      href="../assest/img/Logo_Truong_Dai_hoc_Mo_-_Dia_chat.jpg"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assests/css/reset.css">
    <link rel="stylesheet" href="../assest/css/add_user.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../assest/css/choice_teacher.css?v=<?php echo time(); ?>">
</head>
<body>
  <div class="container">
      <!-- Sidebar -->
      <nav class="sidebar">
        <h2 class="sidebar-title">Menu</h2>
        <ul class="sidebar-menu">
            <li class="menu-item"><a href="student_dashboard.php?page_layout=student_info" class="menu-link <?= isActive('student_info') ?>"> <i class="bi bi-person-circle"></i> Thông tin</a></li>
            <li class="menu-item"><a href="student_dashboard.php?page_layout=choice_teacher"class="menu-link <?= isActive('choice_teacher') ?>"><i class="bi bi-list"></i> Danh sách giảng viên</a></li>
            <li class="menu-item"><a href="student_dashboard.php?page_layout=list_topic" class="menu-link <?= isActive('list_topic') ?>"><i class="bi bi-list-task"></i> Danh sách đề tài</a></li>
            <li class="menu-item"><a href="student_dashboard.php?page_layout=mocTienDo" class="menu-link  <?= isActive('mocTienDo') ?>"> <i class="bi bi-check-circle-fill"></i> Mốc tiến độ</a></li>
            <li class="menu-item"><a href="../admin/admin_dashboard.php?page_layout=logout" class="menu-link"><i class="bi bi-box-arrow-right"></i>  Đăng xuất</a></li>
        </ul>
    </nav>

    <!-- Nội dung chính -->
    <div class="content" >

    <div style="display: flex; justify-content: flex-end;">
    <div class="content-title_container">
        <!-- form tìm kiếm -->
        <?php
        $page_layout = isset($_GET['page_layout']) ? $_GET['page_layout'] : '';
        if ($page_layout === 'choice_teacher') { 
            ?>
            <form style="width: 100%;" id="search-form" class="search" action="student_dashboard.php" method="GET">
            <input type="text" name="keyword" placeholder="Tìm kiếm..." required>
                <button type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            <?php
        }
        ?>
        </div>
        </div>
        <?php 
          if(isset($_GET['page_layout'])){
              switch($_GET['page_layout'])
                  {
                    case "student_info":
                        include('student_info.php'); 
                        break;
                    case "choice_teacher":
                        include('choice_teacher.php');
                        break;
                    case "process_choice":
                        include('process_choice.php');
                        break;
                    case "list_topic":
                        include('list_topic.php');
                        break;
                    case "choose_topic":
                        include('choose_topic.php');
                        break;
                    case "add_user":
                      include('add_user.php');
                      break;
                    case "delete_user_process":
                        include('delete_user_process.php');
                        break;
                    case "mocTienDo":
                        include('mocTienDo.php');
                        break;
                    case "logout":
                        session_destroy();
                        session_unset();
                        header('Location: ../login.php');
                        exit();
                  }
          }
        ?>
    </div>
  </div>
</body>
</html>
