<?php
    require '../parts/db_connect.php';

    $admin_user_id = isset($_GET['admin_user_id']) ? intval($_GET['admin_user_id']) : 0;

    $sql = "DELETE FROM admin_user WHERE admin_user_id=$admin_user_id ";

    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'member_admin_user_list.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>