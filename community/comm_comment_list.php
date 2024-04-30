<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'comm_comment_list-admin.php';
    }else {
        include 'comm_comment_list-no-admin.php';
    }
?>