<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'comm_events_list-admin.php';
    }else {
        include 'comm_events_list-no-admin.php';
    }
?>