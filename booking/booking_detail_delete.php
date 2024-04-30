<?php
    require '../parts/db_connect.php';

    $booking_detail_id = isset($_GET['booking_detail_id']) ? intval($_GET['booking_detail_id']) : 0;

    $sql = "DELETE FROM booking_detail WHERE booking_detail_id=$booking_detail_id ";

    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'booking_detail_list.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>