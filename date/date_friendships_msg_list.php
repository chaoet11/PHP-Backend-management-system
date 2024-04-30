<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'date_friendships_msg_list-admin.php';
    }else {
        include 'date_friendships_msg_list-no-admin.php';
    }
?>