<?php
    require '../parts/db_connect.php';

    $booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;

    $sql = "DELETE FROM booking_system WHERE booking_id=$booking_id ";

    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'booking_system_list.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>