<?php
require '../parts/db_connect.php';

$trip_detail_id = isset($_GET['trip_detail_id']) ? intval($_GET['trip_detail_id']) : 0;

$sql = "DELETE FROM trip_details WHERE trip_detail_id=$trip_detail_id ";

$pdo->query($sql);

# $_SERVER['HTTP_REFERER'] # 人從哪裡來

$goto = empty($_SERVER['HTTP_REFERER']) ? 'trip_detail_list.php' : $_SERVER['HTTP_REFERER'];

header('Location: ' . $goto);
