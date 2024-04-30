<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'bar_area_list-admin.php';
    }else {
        include 'bar_area_list-no-admin.php';
    }
?>