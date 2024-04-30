<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'booking_detail_list-admin.php';
    }else {
        include 'booking_detail_list-no-admin.php';
    }
?>