<?php
require '../_admin-required.php';
require '../parts/db_connect.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="trip_saved_list.csv"');

$output = fopen('php://output', 'w');

// 輸出CSV 標題列
fputcsv($output, ['trip_saved_id', 'trip_plan_id', 'user_id', 'created_at']);

$query = "SELECT trip_saved_id, trip_plan_id, user_id, created_at FROM trip_saved";
$stmt = $pdo->prepare($query);
$stmt->execute();

// 循環遍歷查詢結果並輸出到CSV
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}

fclose($output);
exit;
