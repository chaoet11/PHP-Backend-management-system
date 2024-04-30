<?php
    require '../parts/db_connect.php';

    $show_time_id = isset($_GET['show_time_id']) ? intval($_GET['show_time_id']) : 0;

    $sql = "DELETE FROM booking_showtime_system WHERE show_time_id=$show_time_id";

    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'booking_showtime_system_list.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>