<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'member_admin_user_list-admin.php';
    }else {
        include '../_login.php';
    }
?>