<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'bars_list-admin.php';
    }else {
        include 'bars_list-no-admin.php';
    }
?>