<?php
    require '../parts/db_connect.php';

    $comm_photo_id = isset($_GET['comm_photo_id']) ? intval($_GET['comm_photo_id']) : 0;

    $sql = "DELETE FROM comm_photo WHERE comm_photo_id=$comm_photo_id";

    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'comm_photo_list.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>