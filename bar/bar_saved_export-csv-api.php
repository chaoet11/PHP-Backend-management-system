<?php
    require '../_admin-required.php';
    require '../parts/db_connect.php';

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="bar_saved_data.csv"');

    $output = fopen('php://output', 'w');

    // 輸出CSV 標題列
    fputcsv($output, ['bar_saved_id', 'user_id', 'bar_id']);

    $query = "SELECT bar_saved_id, user_id, bar_id FROM bar_saved";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // 循環遍歷查詢結果並輸出到CSV
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
?>