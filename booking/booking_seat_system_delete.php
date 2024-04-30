<?php
    require '../parts/db_connect.php';

    $seat_id = isset($_GET['seat_id']) ? intval($_GET['seat_id']) : 0;

    $sql = "DELETE FROM booking_seat_system WHERE seat_id=$seat_id";

    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'booking_seat_system_list.php.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>