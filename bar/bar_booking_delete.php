<?php
    require '../parts/db_connect.php';

    $bar_booking_id = isset($_GET['bar_booking_id']) ? intval($_GET['bar_booking_id']) : 0;

    $sql = "DELETE FROM bar_booking WHERE bar_booking_id=$bar_booking_id ";

    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'bar_booking_list.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>