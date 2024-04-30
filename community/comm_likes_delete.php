<?php
    require '../parts/db_connect.php';

    $comm_likes_id = isset($_GET['comm_likes_id']) ? intval($_GET['comm_likes_id']) : 0;

    $sql = "DELETE FROM comm_likes WHERE comm_likes_id=$comm_likes_id ";

    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'comm_likes_list.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>