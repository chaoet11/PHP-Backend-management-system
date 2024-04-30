<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'booking_points_dec_list-admin.php';
    }else {
        include 'booking_points_dec_list-no-admin.php';
    }
?>