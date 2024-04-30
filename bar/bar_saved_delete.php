<?php
    require '../parts/db_connect.php';

    $bar_saved_id = isset($_GET['bar_saved_id']) ? intval($_GET['bar_saved_id']) : 0;

    $sql = "DELETE FROM bar_saved WHERE bar_saved_id=$bar_saved_id ";

    $pdo->query($sql);


    $goto = empty($_SERVER['HTTP_REFERER']) ? 'bar_saved_list.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>