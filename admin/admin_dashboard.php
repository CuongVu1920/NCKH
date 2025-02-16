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
    <!-- Sidebar -->
    <nav class="sidebar">
      <h2 class="sidebar-title">Menu</h2>
      <ul class="sidebar-menu">
        <li class="menu-item"><a href="admin_dashboard.php?page_layout=add_major" class="menu-link">Thêm chuyên ngành</a></li>
        <li class="menu-item"><a href="admin_dashboard.php?page_layout=major" class="menu-link">Danh sách chuyên ngành</a></li>
        <li class="menu-item"><a href="admin_dashboard.php?page_layout=add_user" class="menu-link">Thêm người dùng</a></li>
        <li class="menu-item"><a href="admin_dashboard.php?page_layout=teacher_list" class="menu-link">Danh sách giảng viên</a></li>
        <li class="menu-item"><a href="admin_dashboard.php?page_layout=student_list" class="menu-link">Danh sách sinh viên</a></li>
        <li class="menu-item"><a href="admin_dashboard.php?page_layout=logout" class="menu-link">Đăng xuất</a></li>
      </ul>
    </nav>

    <!-- Nội dung chính -->
    <div class="content">
      <?php
      session_start();
      if (!isset($_SESSION['nguoidung'])) {
        header('location: ../login.php');
      }
      ?>

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
</body>

</html>