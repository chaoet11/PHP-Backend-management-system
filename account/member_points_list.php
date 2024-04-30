<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'member_points_list-admin.php';
    }else {
        include 'member_points_list-no-admin.php';
    }
?>