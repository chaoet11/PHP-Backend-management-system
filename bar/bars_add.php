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
                <!-- add breadcrumb start -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item "><a href="_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                        <li class="breadcrumb-item"><a href="bars_list.php" class="text-decoration-none">Bar</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add</li>
                    </ol>
                </nav>
                <!-- add breadcrumb end -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add Data</h5>
                        <form name="form1" method="post" class="needs-validation" novalidate onsubmit="sendForm(event)">
                            <!-- <div class="mb-3">
                                <label for="bar_id" class="form-label">bar_id</label>
                                <input type="text" class="form-control" id="bar_id" name="bar_id">
                                <div class="form-text"></div>
                            </div> -->
                            <div class="mb-3">
                                <label for="bar_name" class="form-label">bar_name</label>
                                <input type="text" class="form-control" id="bar_name" name="bar_name" required>
                                <div class="invalid-feedback">Please provide a valid bar_name.</div>
                            </div>
                            <div class="mb-3">
                                <label for="bar_city" class="form-label">bar_city</label>
                                <input type="text" class="form-control" id="bar_city" name="bar_city" value="Taipei City" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="bar_area_id" class="form-label">bar_area_id</label>
                                <select class="form-control" id="bar_area_id" name="bar_area_id" required>
                                    <option value="" selected disabled>Please select an area.</option>
                                    <option value="1">松山區</option>
                                    <option value="2">信義區</option>
                                    <option value="3">大安區</option>
                                    <option value="4">中山區</option>
                                    <option value="5">中正區</option>
                                    <option value="6">大同區</option>
                                    <option value="7">萬華區</option>
                                    <option value="8">文山區</option>
                                    <option value="9">南港區</option>
                                    <option value="10">內湖區</option>
                                    <option value="11">士林區</option>
                                    <option value="12">北投區</option>
                                </select>
                                <div class="invalid-feedback">Please provide a valid bar_area_id.</div>
                            </div>
                            <div class="mb-3">
                                <label for="bar_addr" class="form-label">bar_addr</label>
                                <input type="text" class="form-control" id="bar_addr" name="bar_addr" required>
                                <div class="invalid-feedback">Please provide a valid bar_addr.</div>
                            </div>
                            <div class="mb-3">
                                <label for="bar_opening_time" class="form-label">bar_opening_time</label>
                                <input type="time" class="form-control" id="bar_opening_time" name="bar_opening_time" value="18:00" required>
                                <div class="invalid-feedback">Please provide a valid bar_opening_time.</div>
                            </div>
                            <div class="mb-3">
                                <label for="bar_closing_time" class="form-label">bar_closing_time</label>
                                <input type="time" class="form-control" id="bar_closing_time" name="bar_closing_time" value="18:00" required>
                                <div class="invalid-feedback">Please provide a valid bar_closing_time.</div>
                            </div>
                            <div class="mb-3">
                                <label for="bar_contact" class="form-label">bar_contact</label>
                                <input type="text" class="form-control" id="bar_contact" name="bar_contact" required>
                                <div class="invalid-feedback">Please provide a valid bar_contact.</div>
                            </div>
                            <div class="mb-3">
                                <label for="bar_description" class="form-label">bar_description</label>
                                <input type="text" class="form-control" id="bar_description" name="bar_description" required>
                                <div class="invalid-feedback">Please provide a valid bar_description.</div>
                            </div>
                            <div class="mb-3">
                                <label for="bar_type_id" class="form-label">bar_type_id</label>
                                <select class="form-control" id="bar_type_id" name="bar_type_id" required>
                                    <option value="" selected disabled>Please select a bar type.</option>
                                    <option value="1">1. 運動酒吧 Sport Bar</option>
                                    <option value="2">2. 音樂酒吧 Music Bar</option>
                                    <option value="3">3. 異國酒吧 Foreign Bar</option>
                                    <option value="4">4. 特色酒吧 Specialty Bar</option>
                                    <option value="5">5. 其他 Other Bar</option>
                                </select>
                                <div class="invalid-feedback">Please provide a valid bar_type_id.</div>
                            </div>
                            <div class="mb-3">
                                <label for="bar_latitude" class="form-label">bar_latitude</label>
                                <input type="text" class="form-control" id="bar_latitude" name="bar_latitude" required>
                                <div class="invalid-feedback">Please provide a valid bar_latitude.</div>
                            </div>
                            <div class="mb-3">
                                <label for="bar_longtitude" class="form-label">bar_longtitude</label>
                                <input type="text" class="form-control" id="bar_longtitude" name="bar_longtitude" required>
                                <div class="invalid-feedback">Please provide a valid bar_longtitude.</div>
                            </div>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                    </div>
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
                <a type="button" class="btn btn-primary" href="./bars_list.php">Return</a>
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

            fetch('bars_add-api.php', {
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

    const myModal = new bootstrap.Modal(document.getElementById('exampleModal'))
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
</script>
<?php include '../parts/html-foot.php' ?>