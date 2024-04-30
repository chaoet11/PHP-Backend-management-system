<?php
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pwd = '';
    $db_name = 'taipei_date';

    // data source name

    $dsn = "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4";

    $pdo_options = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];

    try{
        $pdo = new PDO($dsn, $db_user, $db_pwd, $pdo_options);
    } catch(PDOException $exception) {
        echo $exception->getMessage();
    }

    // 啟動 session 的功能
    if(!isset($_SESSION)){
        session_start();
    }
?>