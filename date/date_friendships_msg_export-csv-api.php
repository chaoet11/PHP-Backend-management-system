<?php
    require '../_admin-required.php';
    require '../parts/db_connect.php';

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="date_friendships_msg_data.csv"');

    $output = fopen('php://output', 'w');

    // 輸出CSV 標題列
    fputcsv($output, ['message_id', 'friendship_id', 'sender_id', 'receiver_id', 'content', 'sended_at']);

    $query = "SELECT message_id, friendship_id, sender_id, receiver_id, content, sended_at FROM friendships_message";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // 循環遍歷查詢結果並輸出到CSV
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
?>