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
                        <li class="breadcrumb-item"><a href="booking_detail_list.php" class="text-decoration-none">Booking</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add</li>
                    </ol>
                </nav>
                <!-- add breadcrumb end -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add Data</h5>
                        <form name="form1" method="post" class="needs-validation" novalidate onsubmit="sendForm(event)">
                            <div class="mb-3">
                                <label for="booking_id" class="form-label">booking_id</label>
                                <input type="text" class="form-control" id="booking_id" name="booking_id" required>
                                <!-- <div class="form-text"></div> -->
                                <div class="invalid-feedback">Please provide a valid booking_id</div>
                            </div>
                            <div class="mb-3">
                                <label for="seat_id" class="form-label">seat_id</label>
                                <input type="text" class="form-control" id="seat_id" name="seat_id" required>
                                <div class="invalid-feedback">Please provide a valid seat_id</div>
                            <div class="mb-5 pt-3">
                                <label for="booking_type" class="form-label">booking_type</label>
                                <!-- <input type="text" class="form-control" id="booking_type" name="booking_type"> -->
                                <select name="booking_type" id="booking_type" required>
                                    <option value="0">0：Unpaid</option>
                                    <option value="1">1：Paid</option>
                                </select>
                                <div class="invalid-feedback">Please provide a valid option</div>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">新增結果</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success" role="alert">
                    新增成功
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續新增</button>
                <a type="button" class="btn btn-primary" href="./booking_detail_list.php">到列表頁</a>
            </div>
        </div>
    </div>
</div> -->


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
                <a type="button" class="btn btn-primary" href="booking_detail_list.php">Return</a>
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

            fetch('booking_detail_add-api.php', {
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
                    }else {
                        // 顯示失敗視窗
                        errorModal.show();
                        document.form1.reset();
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



    const myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
</script>
<?php include '../parts/html-foot.php' ?>