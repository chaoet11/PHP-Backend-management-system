<?php
    require '../parts/db_connect.php';

    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

    $sql = "DELETE FROM member_user WHERE user_id=$user_id ";

    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'member_user_list.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>