<?php
    require '../parts/db_connect.php';

    $friendship_id = isset($_GET['friendship_id']) ? intval($_GET['friendship_id']) : 0;

    $sql = "DELETE FROM friendships WHERE friendship_id=$friendship_id ";

    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'date_friendships_list.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>