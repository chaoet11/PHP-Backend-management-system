<?php
    require '../_admin-required.php';
    require '../parts/db_connect.php';

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="bars_data.csv"');

    $output = fopen('php://output', 'w');

    // 輸出CSV 標題列
    fputcsv($output, ['bar_id', 'bar_name', 'bar_city', 'bar_area_id', 'bar_addr', 'bar_opening_time', 'bar_closing_time', 'bar_contact', 'bar_description' , 'bar_type_id', 'bar_latitude', 'bar_longtitude']);

    $query = "SELECT bar_id, bar_name, bar_city, bar_area_id, bar_addr, bar_opening_time, bar_closing_time, bar_contact, bar_description, bar_type_id, bar_latitude, bar_longtitude FROM bars";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // 循環遍歷查詢結果並輸出到CSV
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
?>