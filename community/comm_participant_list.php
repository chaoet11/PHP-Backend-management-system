<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include 'comm_participant_list-admin.php';
    }else {
        include 'comm_participant_list-no-admin.php';
    }
?>