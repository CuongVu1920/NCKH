<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tinh sinh viên</title>
    <link rel="stylesheet" href="../assests/css/reset.css">

    <link rel="stylesheet" href="../assest/css/add_user.css">
</head>
<body>
  <div class="container">
      <!-- Sidebar -->
      <nav class="sidebar">
        <h2 class="sidebar-title">Menu</h2>
        <ul class="sidebar-menu">
            <li class="menu-item"><a href="teacher_dashboard.php?page_layout=teacher_info" class="menu-link">Thông tin</a></li>
            <li class="menu-item"><a href="teacher_dashboard.php?page_layout=teacher_non" class="menu-link">Thông báo nguyện vọng</a></li>
            <li class="menu-item"><a href="teacher_dashboard.php?page_layout=accepted_students" class="menu-link">Danh sách sinh viên</a></li>
            <li class="menu-item"><a href="teacher_dashboard.php?page_layout=approve_topics" class="menu-link">Thông báo đề tài</a></li>
            <li class="menu-item"><a href="teacher_dashboard.php?page_layout=add_topic" class="menu-link">Thêm đề tài</a></li>
            <li class="menu-item"><a href="teacher_dashboard.php?page_layout=topic" class="menu-link">Danh sách đề tài</a></li>
            <li class="menu-item"><a href="../admin/admin_dashboard.php?page_layout=logout" class="menu-link">Đăng xuất</a></li>
        </ul>
    </nav>

    <!-- Nội dung chính -->
    <div class="content">
        <?php 
          session_start();
          if(!isset($_SESSION['nguoidung'])){
            header('location: ../login.php');
          }        
        ?>
        
        <?php 
          include('connect.php');
          if(isset($_GET['page_layout'])){
              switch($_GET['page_layout'])
                  {
                    case "teacher_info":
                        include('teacher_info.php'); 
                        break;
                    case "teacher_non":
                        include('teacher_non.php');
                        break;
                    case "update_status":
                        include('update_status.php');
                        break;
                    case "accepted_students":
                        include('accepted_students.php');
                        break;
                    case "topic":
                        include('topic.php');
                        break;
                    case "add_topic":
                      include('add_topic.php');
                      break;
                    case "delete_topic":
                        include('delete_topic.php');
                        break;
                    case "approve_topics":
                        include('approve_topics.php');
                        break;
                    case "process_approval":
                        include('process_approval.php');
                        break;
                    case "logout":
                        session_destroy();
                        session_unset();
                        header('../admin/login.php');
                        break; 
                  }
          }   
        ?>
    </div>
  </div>
</body>
</html>
