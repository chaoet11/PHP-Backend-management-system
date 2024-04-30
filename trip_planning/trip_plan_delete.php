<?php
require '../parts/db_connect.php';

$trip_plan_id = isset($_GET['trip_plan_id']) ? intval($_GET['trip_plan_id']) : 0;

$sql = "DELETE FROM trip_plans WHERE trip_plan_id=$trip_plan_id ";

$pdo->query($sql);

# $_SERVER['HTTP_REFERER'] # 人從哪裡來

$goto = empty($_SERVER['HTTP_REFERER']) ? 'trip_plan_list.php' : $_SERVER['HTTP_REFERER'];

header('Location: ' . $goto);
