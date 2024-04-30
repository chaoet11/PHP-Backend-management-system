<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'comm_post_list-admin.php';
    }else {
        include 'comm_post_list-no-admin.php';
    }
?>