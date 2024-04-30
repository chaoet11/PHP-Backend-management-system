<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'bar_pic_list-admin.php';
    }else {
        include 'bar_pic_list-no-admin.php';
    }
?>