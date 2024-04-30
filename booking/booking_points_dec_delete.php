<?php
    require '../parts/db_connect.php';

    $points_decrease_id = isset($_GET['points_decrease_id']) ? intval($_GET['points_decrease_id']) : 0;

    $sql = "DELETE FROM booking_points_dec WHERE points_decrease_id=$points_decrease_id";

    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'booking_points_dec_list.php.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>