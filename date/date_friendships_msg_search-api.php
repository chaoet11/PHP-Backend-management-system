<?php
require '../parts/db_connect.php';

$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';


$sql = "SELECT
            fm.friendship_id,
            sender.username AS sender_id,
            receiver.username AS receiver_id,
            fm.message_id,
            fm.content,
            fm.sended_at
        FROM
            friendships_message fm
        LEFT JOIN
            member_user sender ON fm.sender_id = sender.user_id
        LEFT JOIN
            member_user receiver ON fm.receiver_id = receiver.user_id
        WHERE
            fm.sended_at BETWEEN ? AND ?";
$stmt = $pdo->prepare($sql);
$stmt->execute(["$start_date 00:00:00", "$end_date 23:59:59"]);
$rows = $stmt->fetchAll();

echo json_encode(['data' => $rows]);
?>