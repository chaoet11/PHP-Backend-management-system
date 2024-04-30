<?php
    require '../parts/db_connect.php';

    $bar_time_slot_id = isset($_GET['bar_time_slot_id']) ? intval($_GET['bar_time_slot_id']) : 0;

    $sql = "DELETE FROM bar_time_slots WHERE bar_time_slot_id=$bar_time_slot_id ";

    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'bar_time_slots_list.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>