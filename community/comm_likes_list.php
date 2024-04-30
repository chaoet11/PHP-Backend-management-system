<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'comm_likes_list-admin.php';
    }else {
        include 'comm_likes_list-no-admin.php';
    }
?>