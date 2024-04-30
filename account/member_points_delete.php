<?php
    require '../parts/db_connect.php';

    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
    $sqlMemberPoints = "DELETE FROM member_points_inc WHERE user_id=$user_id ";
    $stmtMemberPoints = $pdo->query($sqlMemberPoints);
    $stmtMemberPoints->execute();

    $sqlBookingPoints = "DELETE FROM booking_points_dec WHERE user_id = $user_id ";
    $stmtBookingPoints = $pdo->query($sqlBookingPoints);
    $stmtBookingPoints->execute();

    

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'member_points_list.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>