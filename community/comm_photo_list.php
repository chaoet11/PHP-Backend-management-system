<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'comm_photo_list-admin.php';
    }else {
        include 'comm_photo_list-no-admin.php';
    }
?>