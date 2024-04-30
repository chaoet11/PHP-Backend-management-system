<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'bar_saved_list-admin.php';
    }else {
        include 'bar_saved_list-no-admin.php';
    }
?>