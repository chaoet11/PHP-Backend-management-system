<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'member_points_inc_list-admin.php';
    }else {
        include 'member_points_inc_list-no-admin.php';
    }
?>