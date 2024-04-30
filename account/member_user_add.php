<?php
require '../_admin-required.php';
require '../parts/db_connect.php';
$pageName = 'add';
$title = 'Add';
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
                <h5>Account Center</h5>
                <!-- add breadcrumb start -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                        <li class="breadcrumb-item"><a href="member_user_list.php" class="text-decoration-none">Account Center</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Member User</li>
                    </ol>
                </nav>
                <!-- add breadcrumb end -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add Data</h5>
                        <form name="form1" method="post" onsubmit="sendForm(event)">
                            <div class="mb-3">
                                <label for="username" class="form-label">username</label>
                                <input type="text" class="form-control" id="username" name="username">
                            </div>

                            <div class="mb-3">
                                <label for="account" class="form-label">account</label>
                                <input type="text" class="form-control" id="account" name="account">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">password</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>

                            <div class="mb-3">
                                <label for="profile_picture_url" class="form-label">profile_picture_url</label>
                                <input type="text" class="form-control" id="profile_picture_url" name="profile_picture_url">
                            </div>

                            <div class="mb-3">
                                <label for="gender" class="form-label">gender</label>
                                <input type="text" class="form-control" id="gender" name="gender">
                            </div>

                            <div class="mb-3">
                                <label for="user_active" class="form-label">user_active</label>
                                <input type="text" class="form-control" id="user_active" name="user_active">
                            </div>

                            <div class="mb-3">
                                <label for="birthday" class="form-label">birthday</label>
                                <input type="date" class="form-control" id="birthday" name="birthday">
                            </div>

                            <div class="mb-3">
                                <label for="mobile" class="form-label">mobile</label>
                                <input type="text" class="form-control" id="mobile" name="mobile">
                            </div>

                            <div class="mb-3">
                                <label for="profile_content" class="form-label">profile_content</label>
                                <input type="text" class="form-control" id="profile_content" name="profile_content">
                            </div>
                            <!-- Import CSV button -->
                            <div class="mb-3">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadCsvModal"><i class="bi bi-file-earmark-spreadsheet"></i> Import from CSV</button>
                            </div>
                            <!-- Import CSV button -->
                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
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
                <a type="button" class="btn btn-primary" href="./member_user_list.php">Return</a>
            </div>
        </div>
    </div>
</div>

<!-- ModalError -->
<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

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
            // 沒有外觀 的表單
            const fd = new FormData(document.form1);

            fetch('member_user_add-api.php', {
                    method: 'POST',
                    body: fd, // content-type: multipart/form-data
                }).then(r => r.json())
                .then(result => {
                    console.log({
                        result
                    });
                    if (result.success) {
                        myModal.show();
                    } else {
                        myModalError.show();
                    }
                })
                .catch(ex => console.log(ex))
        }
    }
    const myModal = new bootstrap.Modal(document.getElementById('exampleModal'))

    function submitCsv() {
        const formData = new FormData();
        const csvFile = document.getElementById('csvFile').files[0];
        
        if (!csvFile) {
            alert('Please select a CSV file to upload.');
            return;
        }
        
        formData.append('csvFile', csvFile);
        
        fetch('member_user_add-csv-api.php', {
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
</script>
<?php include '../parts/html-foot.php' ?>