<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'bar_rating_list-admin.php';
    }else {
        include 'bar_rating_list-no-admin.php';
    }
?>