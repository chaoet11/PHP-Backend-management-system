<?php
    require '../_admin-required.php';
    require '../parts/db_connect.php';

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="member_user_list.csv"');

    $output = fopen('php://output', 'w');

    // 輸出CSV 標題列
    fputcsv($output, ['user_id', 'username', 'account', 'email', 'password_hash', 'profile_picture_url', 'gender', 'user_active', 'birthday', 'mobile', 'profile_content', 'created_at', 'updated_at']);

    $query = "SELECT user_id, username, account, email, password_hash, profile_picture_url, gender, user_active, birthday, mobile, profile_content, created_at, updated_at FROM member_user";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // 循環遍歷查詢結果並輸出到CSV
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
?>
