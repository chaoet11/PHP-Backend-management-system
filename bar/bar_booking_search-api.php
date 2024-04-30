<?php
require '../parts/db_connect.php';

// 取得開始和结束日期
$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';

$sql = "SELECT bar_booking.*, bars.bar_name, member_user.username, 
    CONCAT(bar_time_slots.bar_start_time, ' ~ ', bar_time_slots.bar_end_time) AS time_slot 
    FROM bar_booking
    LEFT JOIN member_user ON bar_booking.user_id = member_user.user_id
    LEFT JOIN bars ON bar_booking.bar_id = bars.bar_id
    LEFT JOIN bar_time_slots ON bar_booking.bar_time_slot_id = bar_time_slots.bar_time_slot_id
    WHERE bar_booking.bar_booking_time BETWEEN ? AND ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$startDate, $endDate]);
$rows = $stmt->fetchAll();

echo json_encode(['data' => $rows]);
?>
