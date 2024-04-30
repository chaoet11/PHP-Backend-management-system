<?php
    require '../_admin-required.php';
    require '../parts/db_connect.php';

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="comm_post_data.csv"');

    $output = fopen('php://output', 'w');

    // 輸出CSV 標題列
    fputcsv($output, ['post_id', 'context', 'created_at', 'updated_at', 'user_id']);

    $query = "SELECT post_id, context, created_at, updated_at, user_id FROM comm_post";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // 循環遍歷查詢結果並輸出到CSV
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
?>
