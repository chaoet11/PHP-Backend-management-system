<?php
    require '../parts/db_connect.php';

    $message_id = isset($_GET['message_id']) ? intval($_GET['message_id']) : 0;

    $sql = "DELETE FROM friendships_message WHERE message_id=$message_id ";

    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'date_friendships_msg_list.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>