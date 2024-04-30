<?php
    require '../parts/db_connect.php';

    $points_increase_id = isset($_GET['points_increase_id']) ? intval($_GET['points_increase_id']) : 0;
    $sqlMemberPoints = "DELETE FROM member_points_inc WHERE points_increase_id=$points_increase_id";
    $stmtMemberPoints = $pdo->query($sqlMemberPoints);
    $stmtMemberPoints->execute();

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'member_points_inc_list.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>