<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'bar_booking_list-admin.php';
    }else {
        include 'bar_booking_list-no-admin.php';
    }
?>