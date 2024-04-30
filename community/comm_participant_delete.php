<?php
    require '../parts/db_connect.php';

    $comm_participant_id = isset($_GET['comm_participant_id']) ? intval($_GET['comm_participant_id']) : 0;

    $sql = "DELETE FROM comm_participants WHERE comm_participant_id=$comm_participant_id ";

    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'comm_participant_list.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>