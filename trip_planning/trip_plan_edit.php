<?php

require '../_admin-required.php';
require '../parts/db_connect.php';
$pageName = 'edit';

$title = 'Edit';

$trip_plan_id = isset($_GET['trip_plan_id']) ? intval($_GET['trip_plan_id']) : 0;
$sql = "SELECT * FROM trip_plans WHERE trip_plan_id=$trip_plan_id";

$row = $pdo->query($sql)->fetch();
if (empty($row)) {
    header('Location: trip_plan_list.php');
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
            <h5>Trip Planning</h5>
            <!-- add breadcrumb start -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">

                    <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="trip_plan_list.php" class="text-decoration-none">Trip Planning</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Plans</li>
                </ol>
            </nav>
            <!-- add breadcrumb end -->
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Data</h5>
                        <form name="form1" method="post" onsubmit="sendForm(event)">
                            <div class="mb-3">
                                <label class="form-label">trip_plan_id</label>
                                <input type="text" class="form-control" disabled value="<?= $row['trip_plan_id'] ?>">
                                <div class="form-text"></div>
                            </div>
                            <input type="hidden" name="trip_plan_id" value="<?= $row['trip_plan_id'] ?>">
                            <div class="mb-3">
                                <label for="user_id" class="form-label">user_id</label>
                                <input type="text" class="form-control" id="user_id" name="user_id" value="<?= $row['user_id'] ?>">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="trip_title" class="form-label">trip_title</label>
                                <input type="text" class="form-control" id="trip_title" name="trip_title" value="<?= $row['trip_title'] ?>">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="trip_content" class="form-label">trip_content</label>
                                <input type="text" class="form-control" id="trip_content" name="trip_content" value="<?= $row['trip_content'] ?>">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="trip_description" class="form-label">trip_description</label>
                                <input type="text" class="form-control" id="trip_description" name="trip_description" value="<?= $row['trip_description'] ?>">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="trip_notes" class="form-label">trip_notes</label>
                                <textarea class="form-control" name="trip_notes" id="trip_notes" cols="30" rows="3"><?= $row['trip_notes'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="trip_date" class="form-label">trip_date</label>
                                <input type="date" class="form-control" id="trip_date" name="trip_date" value="<?= $row['trip_date'] ?>">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="trip_draft" class="form-label">trip_draft</label>
                                <select class="form-select" id="trip_draft" name="trip_draft">
                                    <option value="0" <?php echo ($row['trip_draft'] == '0') ? 'selected' : ''; ?>>0</option>
                                    <option value="1" <?php echo ($row['trip_draft'] == '1') ? 'selected' : ''; ?>>1</option>
                                </select>
                                <div class="form-text"></div>
                            </div>
                            <button type="submit" class="btn btn-primary">修改</button>
                        </form>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Result</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" role="alert">
                        Edit Successful
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continue</button>
                    <a type="button" class="btn btn-primary" href="./trip_plan_list.php">Return</a>
                </div>
            </div>
        </div>
    </div>

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
            user_id: document.form1.user_id.value,
            trip_title: document.form1.trip_title.value,
            trip_content: document.form1.trip_content.value,
            trip_description: document.form1.trip_description.value,
            trip_notes: document.form1.trip_notes.value,
            trip_date: document.form1.trip_date.value,
            trip_draft: document.form1.trip_draft.value
        };
        const sendForm = e => {
            // 阻止表單的默認提交行為，這樣頁面就不會刷新，允許我們用JavaScript處理表單提交。
            e.preventDefault();




            // 檢查是否有更改
            let isChanged = initialValues.user_id !== document.form1.user_id.value ||
                initialValues.trip_title !== document.form1.trip_title.value ||
                initialValues.trip_content !== document.form1.trip_content.value ||
                initialValues.trip_description !== document.form1.trip_description.value ||
                initialValues.trip_notes !== document.form1.trip_notes.value ||
                initialValues.trip_date !== document.form1.trip_date.value ||
                initialValues.trip_draft !== document.form1.trip_draft.value;

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

                fetch('trip_plan_edit-api.php', {
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