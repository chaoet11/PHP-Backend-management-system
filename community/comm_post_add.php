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
                        <li class="breadcrumb-item "><a href="/Taipie_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                        <li class="breadcrumb-item"><a href="comm_post_list.php" class="text-decoration-none">Community</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add</li>
                    </ol>
                </nav>
                <!-- add breadcrumb end -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add Data</h5>
                        <form name="form1" method="post" class="needs-validation" novalidate onsubmit="sendForm(event)">
                            <!-- <div class="mb-3">
                                <label for="post_id" class="form-label">post_id</label>
                                <input type="text" class="form-control" id="post_id" name="post_id" required>
                                <div class="invalid-feedback">Please provide a valid post ID.</div>
                            </div> -->
                            <div class="mb-3">
                                <label for="context" class="form-label">context</label>
                                <textarea class="form-control" name="context" id="context" cols="30" rows="3" required></textarea>
                                <div class="invalid-feedback">Please provide some context.</div>
                            </div>
                            <div class="mb-3">
                                <label for="user_id" class="form-label">user_id</label>
                                <input type="text" class="form-control" id="user_id" name="user_id" required pattern="\d*">
                                <div class="invalid-feedback">Please provide a user ID (numbers only).</div>
                            </div>
                            <div class="mb-3">
                                <label for="photo" class="form-label">Image</label>
                                <input type="file" class="form-control mb-3" id="photo" name="photo[]" required>
                                <div class="invalid-feedback">Please provide a valid image.</div>
                                <!-- photo preview -->
                                <div id="preview"></div>
                                <!-- photo preview -->
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>

                            <!-- Import CSV button -->
                            <div class="mb-3">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadCsvModal"><i class="bi bi-file-earmark-spreadsheet"></i> Import from CSV</button>
                            </div>
                            <!-- Import CSV button -->

                            <!-- Import Google sheet button -->
                            <div class="mb-3">
                                <button type="button" class="btn btn-primary" onclick="addGSheetToDatabase()"><i class="bi bi-google"></i> Import from Google</button>
                            </div>
                            <!-- Import Google sheet button -->
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
                <a type="button" class="btn btn-primary" href="./comm_post_list.php">Return</a>
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

<!-- CSV Modal -->
<div class="modal fade" id="uploadCsvModal" tabindex="-1" aria-labelledby="uploadCsvModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="uploadCsvModalLabel">Import from CSV</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="csvUploadForm">
                    <div class="mb-3">
                        <label for="csvFile" class="form-label">CSV File</label>
                        <input type="file" class="form-control" id="csvFile" name="csvFile" accept=".csv" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitCsv()">Import</button>
            </div>
        </div>
    </div>
</div>
<!-- CSV Modal -->

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="loadingModalLabel">Processing</h1>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2">Please wait, data is being uploaded...</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Loading Modal -->


<?php include '../parts/scripts.php' ?>
<script>
    const {
        post_id: post_id_f,
        context: context_f,
        created_at: created_at_f,
        updated_at: updated_at_f,
        user_id: user_id_f
    } = document.form1;

    // 確保表單在提交前進行驗證
    document.form1.addEventListener('submit', function(event) {
        if (!this.checkValidity()) {
            event.preventDefault(); // 阻止表單提交
            event.stopPropagation(); // 阻止事件冒泡
        }

        this.classList.add('was-validated');
    }, false);

    const sendForm = e => {
        // 阻止表單的默認提交行為，這樣頁面就不會刷新，允許我們用JavaScript處理表單提交。
        e.preventDefault();

        // TODO: 資料送出之前, 要做檢查(有沒有填寫, 格式對不對)
        let isPassed = true; // 表單有沒有通過檢查

        if (isPassed) {
            const fd = new FormData(document.form1);

            fetch('comm_post_add-api.php', {
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
                        // 移除所有驗證錯誤樣式和提示訊息
                        clearValidationErrors();
                        // 重置表單的 HTML5 驗證狀態
                        document.form1.classList.remove('was-validated');
                    } else {
                        // 顯示失敗視窗
                        errorModal.show();
                    }
                })
                .catch(ex => console.log(ex))
        }
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
    
    // Upload Photo Preview 
    document.getElementById('photo').addEventListener('change', function(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('preview');
        previewContainer.innerHTML = ''; // 清空現有的預覽
        
        Array.from(files).forEach(file => {
            if (!file.type.startsWith('image/')) { return; } // 確保文件類型為圖片

            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '400px'; // 設置預覽圖片的最大寬度
                img.style.maxHeight = '240px'; // 設置預覽圖片的最大高度
                img.style.objectFit = 'cover'; // 保持圖片比例
                img.style.marginRight = '10px'; // 設置預覽圖片之間的間隔
                previewContainer.appendChild(img);
            };

            reader.readAsDataURL(file);
        });
    });
    // Upload Photo Preview 


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
        // 顯示讀取模態框
        const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
        loadingModal.show();

        const sheetUrl = document.getElementById('sheetId').value;
        const sheetId = extractSheetIdFromUrl(sheetUrl);
        
        if (!sheetId) {
            alert('Invalid Google Sheet URL. Please check your input.');
            loadingModal.hide(); // 隱藏讀取模態框
            return;
        }
        
        fetch('comm_post_add-gsheet-api.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({spreadsheetId: sheetId}),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                myModal.show();
            } else {
                errorModal.show();
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            errorModal.show();
        })
        .finally(() => {
            loadingModal.hide(); // 隱藏讀取模態框
        });

        googleSheetModal.hide(); // 隱藏Google Sheets輸入框模態框
    }


    function submitCsv() {
        const formData = new FormData();
        const csvFile = document.getElementById('csvFile').files[0];
        
        if (!csvFile) {
            alert('Please select a CSV file to upload.');
            return;
        }
        
        formData.append('csvFile', csvFile);
        
        fetch('comm_post_add-csv-api.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
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

        // Close CSV Modal
        const uploadCsvModal = bootstrap.Modal.getInstance(document.getElementById('uploadCsvModal'));
        uploadCsvModal.hide();
    }

    const googleSheetModal = new bootstrap.Modal(document.getElementById('googleSheetModal'));
    const myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));

</script>

<?php include '../parts/html-foot.php' ?>