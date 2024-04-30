<?php
require '../parts/db_connect.php';

$trip_saved_id = isset($_GET['trip_saved_id']) ? intval($_GET['trip_saved_id']) : 0;

$sql = "DELETE FROM trip_saved WHERE trip_saved_id=$trip_saved_id ";

$pdo->query($sql);

# $_SERVER['HTTP_REFERER'] # 人從哪裡來

$goto = empty($_SERVER['HTTP_REFERER']) ? 'trip_saved_list.php' : $_SERVER['HTTP_REFERER'];

header('Location: ' . $goto);
