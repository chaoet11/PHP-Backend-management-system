<?php
    require '../parts/db_connect.php';

    $comm_saved_id = isset($_GET['comm_saved_id']) ? intval($_GET['comm_saved_id']) : 0;

    $sql = "DELETE FROM comm_saved WHERE comm_saved_id=$comm_saved_id ";

    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'comm_saved_list.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>