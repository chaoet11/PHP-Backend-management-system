<?php
require '../_admin-required.php';
require '../parts/db_connect.php';
$pageName = 'add';
$title = 'Add';
?>

<?php

use Google\Client;
use Google\Service\Sheets;

// 載入一次 'vendor/autoload.php' 這個文件
require_once __DIR__ . '/../vendor/autoload.php';

// 初始化 Google Sheets Client
$client = new Google_Client();
$client->setApplicationName("mySheet");
$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
$client->addScope(Google\Service\Drive::DRIVE_METADATA_READONLY);

// 引入金鑰
$client->setAuthConfig(__DIR__ . '/../google/AuthConfig.json');

// 建立 Google Sheets Service
$service = new \Google_Service_Sheets($client);

?>

<?php include '../parts/html-head.php' ?>

<style>
    form .mb-3 .form-text {
        color: red;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- navbar -->
        <nav class="navbar navbar-expand-lg col-12" style="background-color: #003e52">
            <div class="container-fluid">
                <?php include '../parts/navbar.php' ?>
            </div>
        </nav>
        <!-- navbar -->

        <!-- sidebar -->
        <?php include '../parts/sidebar.php' ?>
        <!-- sidebar -->
        <div class="col-12 col-md-8 col-lg-10" style="background-color: #003e52">
            <div class="col-6">
                <!-- add breadcrumb start -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                        <li class="breadcrumb-item"><a href="trip_plan_list.php" class="text-decoration-none">Trip Planning</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add</li>
                    </ol>
                </nav>
                <!-- add breadcrumb end -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add Date</h5>
                        <form name="form1" method="post" class="needs-validation" novalidate onsubmit="sendForm(event)">
                            <!-- <div class="mb-3">
                                <label for="trip_plan_id" class="form-label">trip_plan_id</label>
                                <input type="text" class="form-control" id="trip_plan_id" name="trip_plan_id">
                                <div class="form-text"></div>
                            </div> -->
                            <div class="mb-3">
                                <label for="user_id" class="form-label">user_id</label>
                                <input type="text" class="form-control" id="user_id" name="user_id" required>
                                <div class="invalid-feedback">Please provide a valid user_id。</div>
                            </div>
                            <div class="mb-3">
                                <label for="trip_title" class="form-label">trip_title</label>
                                <input type="text" class="form-control" id="trip_title" name="trip_title" required>
                                <div class="invalid-feedback">Please provide a valid trip_title.</div>
                            </div>
                            <div class="mb-3">
                                <label for="trip_content" class="form-label">trip_content</label>
                                <input type="text" class="form-control" id="trip_content" name="trip_content" required>
                                <div class="invalid-feedback">Please provide a valid trip_content.</div>
                            </div>
                            <div class="mb-3">
                                <label for="trip_description" class="form-label">trip_description</label>
                                <input type="text" class="form-control" id="trip_description" name="trip_description">
                                <div class="invalid-feedback">Please provide a valid trip_description.</div>
                            </div>
                            <div class="mb-3">
                                <label for="trip_notes" class="form-label">trip_notes</label>
                                <textarea class="form-control" name="trip_notes" id="trip_notes" cols="30" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="trip_date" class="form-label">trip_date</label>
                                <input type="date" class="form-control" id="trip_date" name="trip_date" required>
                                <div class="invalid-feedback">Please choose a time date.</div>
                            </div>
                            <div class="mb-3">
                                <label for="trip_draft" class="form-label">trip_draft</label>
                                <select class="form-select" id="trip_draft" name="trip_draft" required>
                                    <option value="" disabled selected>Please decide if you want to share it or not.</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                </select>
                                <div class="invalid-feedback">Please correctly choose if you want to share it or not.</div>
                            </div>
                            <div class="mb-3">
                                <button type="button" class="btn btn-primary" onclick="addGSheetToDatabase()"><i class="bi bi-google"></i> Import from Google</button>
                            </div>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Addition Result</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success" role="alert">
                    Successfully added
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continue</button>
                <a type="button" class="btn btn-primary" href="./trip_plan_list.php">Return</a>
            </div>
        </div>
    </div>
</div>
<!-- Success Modal -->


<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Addition Result</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert">
                    Addition failed
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Error Modal -->
<!-- Google Sheet ID Modal -->
<div class="modal fade" id="googleSheetModal" tabindex="-1" aria-labelledby="googleSheetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="googleSheetModalLabel">Import from Google Sheet</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="googleSheetForm">
                    <div class="mb-3">
                        <label for="sheetId" class="form-label">Google Sheet URL: </label>
                        <input type="text" class="form-control" id="sheetId" name="sheetId" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitSheetId()">Import</button>
            </div>
        </div>
    </div>
</div>
<!-- Google Sheet ID Modal -->

<?php include '../parts/scripts.php' ?>
<script>
    const sendForm = e => {
        e.preventDefault();

        let isPassed = document.form1.checkValidity(); // 檢查表單是否有效
        document.form1.classList.add('was-validated'); // 添加 Bootstrap 驗證類

        if (!isPassed) {
            // 如果表單驗證失敗，顯示錯誤彈跳視窗
            errorModal.show();
            return; // 阻止表單提交
        }

        // 表單驗證通過的情況下，繼續執行提交
        const fd = new FormData(document.form1);

        fetch('trip_plan_add-api.php', {
                method: 'POST',
                body: fd, // content-type: multipart/form-data
            }).then(r => r.json())
            .then(result => {
                console.log({
                    result
                });
                if (result.success) {
                    // 顯示成功消息
                    myModal.show();
                    // 清空表單
                    document.form1.reset();

                    clearValidationErrors();

                    document.form1.classList.remove('was-validated');
                } else {
                    // 顯示失敗視窗
                    errorModal.show();

                }
            })
            .catch(ex => console.log(ex))
    }
    const clearValidationErrors = () => {
        // 移除所有表單輸入欄位的驗證錯誤樣式
        document.querySelectorAll('.form-control').forEach(el => {
            el.classList.remove('is-invalid');
        });
        // 清除所有的錯誤提示訊息
        document.querySelectorAll('.invalid-feedback').forEach(el => {
            el.textContent = '';
        });
    };

    function addGSheetToDatabase() {
        googleSheetModal.show();
    }

    // 從URL提取Sheet ID的
    function extractSheetIdFromUrl(url) {
        const regex = /\/d\/(.+?)\//; // 正規表達式匹配"/d/"和之後的"/"之間的所有字符
        const matches = url.match(regex);
        return matches ? matches[1] : null; // 如果匹配成功，返回Sheet ID
    }

    // 修改submitSheetId函数以使用URL而不是直接的Sheet ID
    function submitSheetId() {
        const sheetUrl = document.getElementById('sheetId').value; // 獲取用戶輸入的URL
        const sheetId = extractSheetIdFromUrl(sheetUrl); // 從URL中提取Sheet ID

        // 驗證提取的Sheet ID
        if (!sheetId) {
            alert('Invalid Google Sheet URL. Please check your input.');
            return;
        }

        // 使用提取的Sheet ID調用後端API
        fetch('trip_plan_add_google-api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },

                body: JSON.stringify({
                    spreadsheetId: sheetId
                }), // 發送提取的Sheet ID
            })
            .then(response => response.json())
            .then(data => {
                console.log('data res ===>', data)
                if (data.success) {
                    myModal.show();
                } else {
                    errorModal.show();
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                errorModal.show();
            });

        // 關閉Google import modal
        googleSheetModal.hide();
    }

    const googleSheetModal = new bootstrap.Modal(document.getElementById('googleSheetModal'));
    const myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
</script>
<?php include '../parts/html-foot.php' ?>