<?php
require '../_admin-required.php';
require '../parts/db_connect.php';
$pageName = 'edit';
$title = 'Edit';

$comm_event_id = isset($_GET['comm_event_id']) ? intval($_GET['comm_event_id']) : 0;
$sql = "SELECT * FROM comm_events WHERE comm_event_id=$comm_event_id";

$row = $pdo->query($sql)->fetch();
if (empty($row)) {
    header('Location: comm_event_list.php');
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
        <h5>Community</h5>
        <!-- add breadcrumb start -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="/Taipie_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="comm_events_list.php" class="text-decoration-none">Community</a></li>
                <li class="breadcrumb-item active" aria-current="page">Events</li>
            </ol>
        </nav>
        <!-- add breadcrumb end -->
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit</h5>
                    <form name="form1" method="post" onsubmit="sendForm(event)">
                        <div class="mb-3">
                            <label class="form-label">comm_event_id</label>
                            <input type="text" class="form-control" disabled value="<?= $row['comm_event_id'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <input type="hidden" name="comm_event_id" value="<?= $row['comm_event_id'] ?>">
                        <div class="mb-3">
                            <label for="title" class="form-label">title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="<?= $row['title'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">description</label>
                            <textarea class="form-control" name="description" id="description" cols="30"
                                rows="3"><?= $row['description'] ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">status</label>
                            <input type="text" class="form-control" id="status" name="status"
                                value="<?= $row['status'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">location</label>
                            <textarea class="form-control" name="location" id="location" cols="30"
                                rows="3"><?= $row['location'] ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="user_id" class="form-label">user_id</label>
                            <input type="text" class="form-control" id="user_id" name="user_id"
                                value="<?= $row['user_id'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="start_time" class="form-label">start_time</label>
                            <input type="text" class="form-control" id="start_time" name="start_time"
                                value="<?= $row['start_time'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="end_time" class="form-label">end_time</label>
                            <input type="text" class="form-control" id="end_time" name="end_time"
                                value="<?= $row['end_time'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <!-- <div class="mb-3">
                            <label for="created_at" class="form-label">created_at</label>
                            <input type="text" class="form-control" id="created_at" name="created_at"
                                value="<?= $row['created_at'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="updated_at" class="form-label">updated_at</label>
                            <input type="text" class="form-control" id="updated_at" name="updated_at"
                                value="<?= $row['updated_at'] ?>">
                            <div class="form-text"></div>
                        </div> -->
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
                <a type="button" class="btn btn-primary" href="./comm_events_list.php">Return</a>
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
        comm_event_id: document.form1.comm_event_id.value,
        title: document.form1.title.value,
        description: document.form1.description.value,
        status: document.form1.status.value,
        location: document.form1.location.value,
        user_id: document.form1.user_id.value,
        start_time: document.form1.start_time.value,
        end_time: document.form1.end_time.value
    };

    const sendForm = e => {
        // 阻止表單的默認提交行為，這樣頁面就不會刷新，允許我們用JavaScript處理表單提交。
        e.preventDefault();

        // 檢查是否有更改
        let isChanged = initialValues.comm_event_id !== document.form1.comm_event_id.value ||
                initialValues.title !== document.form1.title.value ||
                initialValues.description !== document.form1.description.value ||
                initialValues.status !== document.form1.status.value ||
                initialValues.location !== document.form1.location.value ||
                initialValues.user_id !== document.form1.user_id.value ||
                initialValues.start_time !== document.form1.start_time.value ||
                initialValues.end_time !== document.form1.end_time.value;

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

            fetch('comm_events_edit-api.php', {
                method: 'POST',
                body: fd, // content-type: multipart/form-data
            }).then(r => r.json())
            .then(result => {
                console.log({
                    result
                });
                if (result.success) {
                    myModal.show();
                }else {
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