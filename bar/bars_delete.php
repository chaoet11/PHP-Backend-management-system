<?php
    require '../parts/db_connect.php';

    $bar_id = isset($_GET['bar_id']) ? intval($_GET['bar_id']) : 0;

    $sql = "DELETE FROM bars WHERE bar_id=$bar_id ";

    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'bars_list.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>