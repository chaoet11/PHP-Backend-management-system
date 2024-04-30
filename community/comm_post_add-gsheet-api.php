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
    $getRange = 'A1:F100'; // 根據表單調整選取範圍

    try {
        $response = $service->spreadsheets_values->get($spreadsheetId, $getRange);
        $values = $response->getValues();

        if (!empty($values)) {
            $output['success'] = true;
            foreach (array_slice($values, 1) as $row) {
                $context = isset($row[1]) ? $row[1] : null;
                $userId = isset($row[4]) ? $row[4] : null;
                $imageUrl = isset($row[5]) ? $row[5] : null;
                
                if ($context && $userId && $imageUrl) {
                    $imageData = file_get_contents($imageUrl);
                    // 檢查是否成功下載圖片
                    if ($imageData !== false) {
                        $sql = "INSERT INTO `comm_post` (`context`, `user_id`) VALUES (?, ?)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$context, $userId]);
                        $postId = $pdo->lastInsertId();

                        // 將圖片數據和其他資訊插入資料庫
                        $sqlImg = "INSERT INTO `comm_photo` (`photo_name`, `post_id`, `img`) VALUES (?, ?, ?)";
                        $stmtImg = $pdo->prepare($sqlImg);
                        $stmtImg->bindParam(1, $defaultPhotoName);
                        $stmtImg->bindParam(2, $postId);
                        $stmtImg->bindParam(3, $imageData, PDO::PARAM_LOB);
                        $defaultPhotoName = "default_photo_name.jpg";
                        $stmtImg->execute();
                    } else {
                        $output['errors'][] = 'Failed to download image from URL: ' . $imageUrl;
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
?>
