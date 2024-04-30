<?php
    require '../parts/db_connect.php';

    $comm_comment_id = isset($_GET['comm_comment_id']) ? intval($_GET['comm_comment_id']) : 0;

    $sql = "DELETE FROM comm_comment WHERE comm_comment_id=$comm_comment_id ";

    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'comm_comment_list.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>