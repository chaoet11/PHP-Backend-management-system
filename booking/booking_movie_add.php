<?php
require '../_admin-required.php';
require '../parts/db_connect.php';
$pageName = 'add';
$title = '新增';
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
                        <li class="breadcrumb-item"><a href="booking_movie_list.php" class="text-decoration-none">Booking</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add</li>
                    </ol>
                </nav>
                <!-- add breadcrumb end -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add Data</h5>
                        <form name="form1" method="post" class="needs-validation" novalidate onsubmit="sendForm(event)">
                            <div class="mb-3">
                                <label for="title" class="form-label">title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                                <div class="invalid-feedback">Please provide a valid title.</div>
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="poster_img" class="form-label">poster_img</label>
                                <input type="text" class="form-control" id="poster_img" name="poster_img" required>
                                <div class="invalid-feedback">Please provide a valid poster_img.</div>
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="movie_description" class="form-label">movie_description</label>
                                <textarea class="form-control" name="movie_description" id="movie_description" cols="30" rows="3" required></textarea>
                                <div class="invalid-feedback">Please provide a valid movie_description.</div>
                            </div>
                            <div class="mb-3">
                                <label for="movie_rating" class="form-label">movie_rating</label>
                                <input type="text" class="form-control" id="movie_rating" name="movie_rating" required>
                                <div class="invalid-feedback">Please provide a valid movie_rating.</div>
                                <div class="form-text"></div>
                                
                                
                            </div>
                            <div class="mb-3">
                                <label for="movie_type_id" class="form-label">movie_type_id</label>
                                <input type="text" class="form-control" id="movie_type_id" name="movie_type_id" required>
                                <div class="invalid-feedback">Please provide a valid movie_type_id.</div>
                                <div class="form-text" ></div>
                                
                            </div>
                            <div class="mb-3">
                                <label for="photo" class="form-label">Image</label>
                                <input type="file" class="form-control mb-3" id="photo" name="photo[]" required>
                                <div class="invalid-feedback">Please provide a valid image.</div>
                                <!-- photo preview -->
                                <div id="preview"></div>
                                <!-- photo preview -->

                            <!-- Import CSV button -->
                            <div class="mb-3">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadCsvModal"><i class="bi bi-file-earmark-spreadsheet"></i> Import from CSV</button>
                            </div>
                            <!-- Import CSV button -->

                            <!-- Import Google sheet button -->
                            <!-- <div class="mb-3">
                                <button type="button" class="btn btn-primary" onclick="addGSheetToDatabase()"><i class="bi bi-google"></i> Import from Google</button>
                            </div> -->
                            <!-- Import Google sheet button -->
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
                <a type="button" class="btn btn-primary" href="./booking_movie_list.php">Return</a>
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

<?php include '../parts/scripts.php' ?>
<script>
    const sendForm = e => {
        // 阻止表單的默認提交行為，這樣頁面就不會刷新，允許我們用JavaScript處理表單提交。
        e.preventDefault();

        // TODO: 資料送出之前, 要做檢查(有沒有填寫, 格式對不對)
        let isPassed = true; // 表單有沒有通過檢查

        if (isPassed) {
            const fd = new FormData(document.form1);

            fetch('booking_movie_add-api.php', {
                method: 'POST',
                body: fd, // content-type: multipart/form-data
            }).then(r => r.json())
                .then(result => {
                    console.log({ result });
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
        const sheetUrl = document.getElementById('sheetId').value; // 獲取用戶輸入的URL
        const sheetId = extractSheetIdFromUrl(sheetUrl); // 從URL中提取Sheet ID

        // console.log('Sheet URL:', sheetUrl);
        // console.log('Sheet ID:', sheetId);

        // 驗證提取的Sheet ID
        if (!sheetId) {
            alert('Invalid Google Sheet URL. Please check your input.');
            return;
        }

        // 使用提取的Sheet ID調用後端API
        fetch('booking_movie_add-gsheet-api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
        
            body: JSON.stringify({ spreadsheetId: sheetId }), // 發送提取的Sheet ID
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
        });

        // 關閉Google import modal
        googleSheetModal.hide();
    }

    function submitCsv() {
        const formData = new FormData();
        const csvFile = document.getElementById('csvFile').files[0];
        
        if (!csvFile) {
            alert('Please select a CSV file to upload.');
            return;
        }
        
        formData.append('csvFile', csvFile);
        
        fetch('booking_movie_add-csv-api.php', {
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