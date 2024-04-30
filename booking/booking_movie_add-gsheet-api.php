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
        $getRange = 'A1:E100'; // 根據表單調整選取範圍

        try {
            $response = $service->spreadsheets_values->get($spreadsheetId, $getRange);
            $values = $response->getValues();

            if (!empty($values)) {
                $output['success'] = true;
                // 跳過第一行標題
                foreach (array_slice($values, 1) as $row) {
                    // 目前固定樣式表單
                    $title = isset($row[1]) ? $row[1] : null;
                    $poster_img = isset($row[2]) ? $row[2] : null;
                    $movie_description = isset($row[3]) ? $row[3] : null;
                    $movie_rating = isset($row[4]) ? $row[4] : null;
                    $movie_type_id = isset($row[5]) ? $row[5] : null;
                    
                    if ($title && $poster_img && $movie_description && $movie_description && $movie_type_id) { // 確保不為空
                        $sql = "INSERT INTO `booking_movie` (title, poster_img, movie_description, movie_rating, movie_type_id) VALUES (?, ?, ?, ?, ?)";
                        $stmt = $pdo->prepare($sql);
                        try {
                            $stmt->execute([$title, $poster_img, $movie_description, $movie_rating, $movie_type_id]);
                        } catch (PDOException $e) {
                            $output['success'] = false;
                            $output['error'] = 'Insertion error: ' . $e->getMessage();
                            break; // 發生錯誤時停止處理
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
