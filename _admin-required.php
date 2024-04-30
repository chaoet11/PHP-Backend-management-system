<?php
    # 啟動 session 的功能
    if(!isset($_SESSION)) {
        session_start();
    }

    if(!isset($_SESSION['admin'])) {
        header('Location: ./_login.php');
        exit;
    }
?>