<?php
    require '../parts/db_connect.php';

    $comm_event_id = isset($_GET['comm_event_id']) ? intval($_GET['comm_event_id']) : 0;

    $sql = "DELETE FROM comm_events WHERE comm_event_id=$comm_event_id ";

    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'comm_events_list.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>