<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'bar_time_slots_list-admin.php';
    }else {
        include 'bar_time_slots_list-no-admin.php';
    }
?>