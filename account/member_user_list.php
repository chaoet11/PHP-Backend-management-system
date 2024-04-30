<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'member_user_list-admin.php';
    }else {
        include 'member_user_list-no-admin.php';
    }
?>