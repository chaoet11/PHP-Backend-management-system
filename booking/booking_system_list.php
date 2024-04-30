<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'booking_system_list-admin.php';
    }else {
        include 'booking_system_list-no-admin.php';
    }
?>