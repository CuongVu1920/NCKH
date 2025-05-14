<?php
    session_start();
    if(isset($_SESSION['nguoidung'])){
        header("Location: admin/admin_dashboard.php");
    }else{
        header("Location: login.php");
    }
 ?>