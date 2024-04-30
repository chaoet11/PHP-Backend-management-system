<?php
    require '../parts/db_connect.php';

    $bar_rating_id = isset($_GET['bar_rating_id']) ? intval($_GET['bar_rating_id']) : 0;

    $sql = "DELETE FROM bar_rating WHERE bar_rating_id=$bar_rating_id ";

    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'bar_rating_list.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>