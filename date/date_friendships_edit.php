<?php
require '../_admin-required.php';
require '../parts/db_connect.php';
$pageName = 'edit';
$title = 'Edit';

$friendship_id = isset($_GET['friendship_id']) ? intval($_GET['friendship_id']) : 0;
$sql = "SELECT * FROM friendships WHERE friendship_id=$friendship_id";

$row = $pdo->query($sql)->fetch();
if (empty($row)) {
    header('Location: date_friendships_list.php');
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
        <h5>Blind Date</h5>
        <!-- add breadcrumb start -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="date_friendships_list.php" class="text-decoration-none">Blind Date</a></li>
                <li class="breadcrumb-item active" aria-current="page">Friendships</li>
            </ol>
        </nav>
        <!-- add breadcrumb end -->
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Data</h5>
                    <form name="form1" method="post" onsubmit="sendForm(event)">
                        <div class="mb-3">
                            <label class="form-label">friendship_id</label>
                            <input type="text" class="form-control" disabled value="<?= $row['friendship_id'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <input type="hidden" name="friendship_id" value="<?= $row['friendship_id'] ?>">
                        <div class="mb-3">
                            <label class="form-label">user_id1</label>
                            <input type="text" class="form-control" disabled value="<?= $row['user_id1'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <input type="hidden" name="user_id1" value="<?= $row['user_id1'] ?>">
                        <div class="mb-3">
                            <label class="form-label">user_id2</label>
                            <input type="text" class="form-control" disabled value="<?= $row['user_id2'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <input type="hidden" name="user_id2" value="<?= $row['user_id2'] ?>">
                        <div class="mb-3">
                            <label for="friendship_status" class="form-label">Friendship Status</label>
                            <select class="form-select" id="friendship_status" name="friendship_status">
                                <option value="pending" <?php echo ($row['friendship_status'] == 'pending') ? 'selected' : ''; ?>>pending</option>
                                <option value="accepted" <?php echo ($row['friendship_status'] == 'accepted') ? 'selected' : ''; ?>>accepted</option>
                                <option value="blocked" <?php echo ($row['friendship_status'] == 'blocked') ? 'selected' : ''; ?>>blocked</option>
                            </select>
                            <div class="form-text"></div>
                        </div>
                        <!-- <div class="mb-3">
                            <label class="form-label">created_at</label>
                            <input type="text" class="form-control" disabled value="<?= $row['created_at'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <input type="hidden" name="created_at" value="<?= $row['created_at'] ?>">
                        <div class="mb-3">
                            <label class="form-label">updated_at</label>
                            <input type="text" class="form-control" disabled value="<?= $row['updated_at'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <input type="hidden" name="updated_at" value="<?= $row['updated_at'] ?>"> -->
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
                <a type="button" class="btn btn-primary" href="./date_friendships_list.php">Return</a>
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
        friendship_id: document.form1.friendship_id.value,
        user_id1: document.form1.user_id1.value,
        user_id2: document.form1.user_id2.value,
        friendship_status: document.form1.friendship_status.value
    };

    const sendForm = e => {
        // 阻止表單的默認提交行為，這樣頁面就不會刷新，允許我們用JavaScript處理表單提交。
        e.preventDefault();

        // 檢查是否有更改
        let isChanged = initialValues.friendship_id !== document.form1.friendship_id.value ||
                initialValues.user_id1 !== document.form1.user_id1.value ||
                initialValues.user_id2 !== document.form1.user_id2.value ||
                initialValues.friendship_status !== document.form1.friendship_status.value;

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

            fetch('date_friendships_edit-api.php', {
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