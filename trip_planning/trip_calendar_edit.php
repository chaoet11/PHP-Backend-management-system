<?php
require '../_admin-required.php';
require '../parts/db_connect.php';
$pageName = 'edit';
$title = 'Edit';

$calendar_id = isset($_GET['calendar_id']) ? intval($_GET['calendar_id']) : 0;
$sql = "SELECT * FROM trip_calendar WHERE calendar_id=$calendar_id";

$row = $pdo->query($sql)->fetch();
if (empty($row)) {
    header('Location: trip_calendar_list.php');
    exit; # 結束 php 程式 
}
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

        <div class="col-12 col-md-8 col-lg-10">
            <h5>Trip</h5>
            <!-- add breadcrumb start -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="trip_calendar_list.php" class="text-decoration-none">Trip Planning</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Calendar</li>
                </ol>
            </nav>
            <!-- add breadcrumb end -->
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Data</h5>
                        <form name="form1" method="post" onsubmit="sendForm(event)">
                            <div class="mb-3">
                                <label class="form-label">calendar_id</label>
                                <input type="text" class="form-control" disabled value="<?= $row['calendar_id'] ?>">
                                <div class="form-text"></div>
                            </div>
                            <input type="hidden" name="calendar_id" value="<?= $row['calendar_id'] ?>">
                            <div class="mb-3">
                                <label for="trip_plan_id" class="form-label">trip_plan_id</label>
                                <input type="text" class="form-control" id="trip_plan_id" name="trip_plan_id" value="<?= $row['trip_plan_id'] ?>">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="primary_trip_detail_id" class="form-label">primary_trip_detail_id</label>
                                <input type="text" class="form-control" id="primary_trip_detail_id" name="primary_trip_detail_id" value="<?= $row['primary_trip_detail_id'] ?>">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="secondary_trip_detail_id" class="form-label">secondary_trip_detail_id</label>
                                <input type="text" class="form-control" id="secondary_trip_detail_id" name="secondary_trip_detail_id" value="<?= $row['secondary_trip_detail_id'] ?>">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="tertiary_trip_detail_id" class="form-label">tertiary_trip_detail_id</label>
                                <input type="text" class="form-control" id="tertiary_trip_detail_id" name="tertiary_trip_detail_id" value="<?= $row['tertiary_trip_detail_id'] ?>">
                                <div class="form-text"></div>
                            </div>
                            <button type="submit" class="btn btn-primary">Edit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Success Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Result</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" role="alert">
                        Edit Successful
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continue </button>
                    <a type="button" class="btn btn-primary" href="./trip_calendar_list.php">Return</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Success Modal -->

    <!-- Edit Failed Modal -->
    <div class="modal fade" id="editFailedModal" tabindex="-1" aria-labelledby="editFailedModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editFailedModalLabel">Result</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert">
                        Editing Failed
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Edit Failed Modal -->

    <!-- No Edit Modal -->
    <div class="modal fade" id="noEditModal" tabindex="-1" aria-labelledby="noEditModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="noEditModalLabel">Result</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning" role="alert">
                        No Edit Made
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- No Edit Modal -->

    <?php include '../parts/scripts.php' ?>
    <script>
        let initialValues = {
            trip_plan_id: document.form1.trip_plan_id.value,
            primary_trip_detail_id: document.form1.primary_trip_detail_id.value,
            secondary_trip_detail_id: document.form1.secondary_trip_detail_id.value,
            tertiary_trip_detail_id: document.form1.tertiary_trip_detail_id.value
        };
        const sendForm = e => {
            // 阻止表單的默認提交行為，這樣頁面就不會刷新，允許我們用JavaScript處理表單提交。
            e.preventDefault();

            // 檢查是否有更改
            let isChanged = initialValues.trip_plan_id !== document.form1.trip_plan_id.value ||
                initialValues.primary_trip_detail_id !== document.form1.primary_trip_detail_id.value ||
                initialValues.secondary_trip_detail_id !== document.form1.secondary_trip_detail_id.value ||
                initialValues.tertiary_trip_detail_id !== document.form1.tertiary_trip_detail_id.value;

            if (!isChanged) {
                // 如果未更改, 顯示彈出視窗
                noEditModal.show();
                return;
            }

            // TODO: 資料送出之前, 要做檢查(有沒有填寫, 格式對不對)
            let isPassed = true; // 表單有沒有通過檢查



            if (isPassed) {
                // 沒有外觀 的表單
                const fd = new FormData(document.form1);

                fetch('trip_calendar_edit-api.php', {
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
                            editFailedModal.show();
                        }
                    }).catch(ex => {
                        console.error(ex);
                        editFailedModal.show();
                    });
            }
        }
        const myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
        const editFailedModal = new bootstrap.Modal(document.getElementById('editFailedModal'));
        const noEditModal = new bootstrap.Modal(document.getElementById('noEditModal'));
    </script>
    <?php include '../parts/html-foot.php' ?>