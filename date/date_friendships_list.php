<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'date_friendships_list-admin.php';
    }else {
        include 'date_friendships_list-no-admin.php';
    }
?>