<?php
require '../parts/db_connect.php';

$bar_type_id = isset($_GET['bar_type_id']) ? intval($_GET['bar_type_id']) : 0;

$sql = "DELETE FROM bar_type WHERE bar_type_id=$bar_type_id ";

$pdo->query($sql);

# $_SERVER['HTTP_REFERER'] # 人從哪裡來

$goto = empty($_SERVER['HTTP_REFERER']) ? 'bar_type_list.php' : $_SERVER['HTTP_REFERER'];

header('Location: ' . $goto);
