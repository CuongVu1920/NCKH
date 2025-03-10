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
</head>
<body>
  <div class="container">
      <!-- Sidebar -->
      <nav class="sidebar">
        <h2 class="sidebar-title">Menu</h2>
        <ul class="sidebar-menu">
            <li class="menu-item"><a href="student_dashboard.php?page_layout=student_info" class="menu-link"> <i class="bi bi-person-circle"></i> Thông tin</a></li>
            <li class="menu-item"><a href="student_dashboard.php?page_layout=choice_teacher"class="menu-link"><i class="bi bi-list"></i> Danh sách giảng viên</a></li>
            <li class="menu-item"><a href="student_dashboard.php?page_layout=list_topic" class="menu-link"><i class="bi bi-list-task"></i> Danh sách đề tài</a></li>
            <li class="menu-item"><a href="student_dashboard.php?page_layout=mocTienDo" class="menu-link"> <i class="bi bi-check-circle-fill"></i> Mốc tiến độ</a></li>
            <li class="menu-item"><a href="../admin/admin_dashboard.php?page_layout=logout" class="menu-link"><i class="bi bi-box-arrow-right"></i>  Đăng xuất</a></li>
        </ul>
    </nav>

    <!-- Nội dung chính -->
    <div class="content">
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
