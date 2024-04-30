<?php
    require '../parts/db_connect.php';

    $bar_pic_id = isset($_GET['bar_pic_id']) ? intval($_GET['bar_pic_id']) : 0;

    $sql = "DELETE FROM bar_pic WHERE bar_pic_id=$bar_pic_id";

    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'bar_pic_list.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>