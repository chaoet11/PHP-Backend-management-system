<?php
require '../parts/db_connect.php';

$calendar_id = isset($_GET['calendar_id']) ? intval($_GET['calendar_id']) : 0;

$sql = "DELETE FROM trip_calendar WHERE calendar_id=$calendar_id ";

$pdo->query($sql);

# $_SERVER['HTTP_REFERER'] # 人從哪裡來

$goto = empty($_SERVER['HTTP_REFERER']) ? 'trip_calendar_list.php' : $_SERVER['HTTP_REFERER'];

header('Location: ' . $goto);
