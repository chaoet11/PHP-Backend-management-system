<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'comm_saved_list-admin.php';
    }else {
        include 'comm_saved_list-no-admin.php';
    }
?>