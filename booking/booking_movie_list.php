<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'booking_movie_list-admin.php';
    }else {
        include 'booking_movie_list-no-admin.php';
    }
?>