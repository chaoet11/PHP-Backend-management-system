<?php
require '../_admin-required.php';
require '../parts/db_connect.php';
$pageName = 'edit';
$title = 'Edit';

$bar_pic_id = isset($_GET['bar_pic_id']) ? intval($_GET['bar_pic_id']) : 0;
$sql = "SELECT * FROM bar_pic WHERE bar_pic_id=$bar_pic_id";

$row = $pdo->query($sql)->fetch();
if (empty($row)) {
    header('Location: bar_pic_list.php');
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
        <h5>Bar</h5>
        <!-- add breadcrumb start -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="/taipei_date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="bar_pic_list.php" class="text-decoration-none">Bar</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pic</li>
            </ol>
        </nav>
        <!-- add breadcrumb end -->
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Data</h5>
                    <form name="form1" method="post" onsubmit="sendForm(event)">
                        <div class="mb-3">
                            <label class="form-label">bar_pic_id</label>
                            <input type="text" class="form-control" disabled value="<?= $row['bar_pic_id'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <input type="hidden" name="bar_pic_id" value="<?= $row['bar_pic_id'] ?>">
                        <div class="mb-3">
                            <label for="bar_pic_name" class="form-label">bar_pic_name</label>
                            <input type="text" class="form-control" id="bar_pic_name" name="bar_pic_name"
                                value="<?= $row['bar_pic_name'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="bar_id" class="form-label">bar_id</label>
                            <input type="text" class="form-control" id="bar_id" name="bar_id"
                                value="<?= $row['bar_id'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
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
                <a type="button" class="btn btn-primary" href="./bar_pic_list.php">Return</a>
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
        bar_pic_id: document.form1.bar_pic_id.value,
        bar_pic_name: document.form1.bar_pic_name.value,
        bar_id: document.form1.bar_id.value
    };

    const sendForm = e => {
        // 阻止表單的默認提交行為，這樣頁面就不會刷新，允許我們用JavaScript處理表單提交。
        e.preventDefault();

         // 檢查是否有更改
        let isChanged = initialValues.bar_pic_id !== document.form1.bar_pic_id.value ||
                initialValues.bar_pic_name !== document.form1.bar_pic_name.value ||
                initialValues.bar_id !== document.form1.bar_id.value;

        if (!isChanged) {
            // 如果未更改, 顯示彈出視窗
            noEditModal.show();
            return;
        }

        // TODO: 資料送出之前, 要做檢查(有沒有填寫, 格式對不對)
        let isPassed = true; // 表單有沒有通過檢查

        if (isPassed) {
            const fd = new FormData(document.form1);

            fetch('bar_pic_edit-api.php', {
                method: 'POST',
                body: fd,
            }).then(r => r.json())
                .then(result => {
                    console.log({ result });
                    if (result.success) {
                        myModal.show();
                    } else {
                        editFailedModal.show();
                    }
                })
                .catch(ex => {
                    console.log(ex);
                    editFailedModal.show();
                });
        }
    }
    const myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
    const editFailedModal = new bootstrap.Modal(document.getElementById('editFailedModal'));
    const noEditModal = new bootstrap.Modal(document.getElementById('noEditModal'));
</script>
<?php include '../parts/html-foot.php' ?>