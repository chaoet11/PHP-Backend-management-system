<?php
    session_start();

    if(isset($_SESSION['admin'])) {
        include __DIR__ . '/_index-admin.php';
    }else {
        include __DIR__ . '/_login.php';
    }
?>