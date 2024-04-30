<?php
require '../_admin-required.php';
require '../parts/db_connect.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Google\Client;
use Google\Service\Sheets;

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['spreadsheetId'])) {
    $client = new Google_Client();
    $client->setApplicationName("mySheet");
    $client->setScopes([Sheets::SPREADSHEETS]);
    $client->setAuthConfig(__DIR__ . '/../google/credentials.json');
    $client->setAccessType('offline');

    $service = new Sheets($client);
    $spreadsheetId = $data['spreadsheetId'];
    $getRange = 'A1:G100'; // 根據表單調整選取範圍

    try {
        $response = $service->spreadsheets_values->get($spreadsheetId, $getRange);
        $values = $response->getValues();

        if (!empty($values)) {
            $output['success'] = true;
            // 跳過第一行標題
            foreach (array_slice($values, 1) as $row) {
                // 檢查並解析日期
                if (!empty($row[5])) {
                    try {
                        $dateObject = new DateTime($row[5]);
                        $formattedDate = $dateObject->format('Y-m-d');
                    } catch (Exception $e) {
                        error_log("Date parsing error: " . $e->getMessage());
                        continue; // 如果日期無效，跳過這行
                    }
                } else {
                    continue; // 如果日期欄位是空的，跳過這行
                }

                // 從 row 數組中讀取其他數據
                $user_id = isset($row[0]) ? $row[0] : null;
                $trip_title = isset($row[1]) ? $row[1] : null;
                $trip_content = isset($row[2]) ? $row[2] : null;
                $trip_description = isset($row[3]) ? $row[3] : null;
                $trip_notes = isset($row[4]) ? $row[4] : null;
                $trip_draft = isset($row[6]) ? $row[6] : null;

                if ($user_id && $trip_title && $trip_content && $formattedDate && $trip_draft !== null) {
                    $sql = "INSERT INTO `trip_plans` (`user_id`, `trip_title`, `trip_content`, `trip_description`, `trip_notes`, `trip_date`, `trip_draft`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    try {
                        $stmt->execute([$user_id, $trip_title, $trip_content, $trip_description, $trip_notes, $formattedDate, $trip_draft]);
                    } catch (PDOException $e) {
                        http_response_code(500);
                        echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
                        exit;
                    }
                }
            }
        } else {
            $output['error'] = 'No data found in Google Sheet.';
        }
    } catch (Exception $e) {
        $output['success'] = false;
        $output['error'] = 'Unable to connect to Google Sheets API: ' . $e->getMessage();
    }
} else {
    $output['success'] = false;
    $output['error'] = 'No Spreadsheet ID provided';
}

echo json_encode($output);
