<?php
require '../_admin-required.php';
require '../parts/db_connect.php';
$pageName = 'edit';
$title = 'Edit';

$bar_booking_id = isset($_GET['bar_booking_id']) ? intval($_GET['bar_booking_id']) : 0;
$sql = "SELECT * FROM bar_booking WHERE bar_booking_id=$bar_booking_id";

$row = $pdo->query($sql)->fetch();
if (empty($row)) {
    header('Location: bar_booking_list.php');
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
                <li class="breadcrumb-item"><a href="bar_booking_list.php" class="text-decoration-none">Bar</a></li>
                <li class="breadcrumb-item active" aria-current="page">Booking</li>
            </ol>
        </nav>
        <!-- add breadcrumb end -->
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Data</h5>
                    <form name="form1" method="post" onsubmit="sendForm(event)">
                        <div class="mb-3">
                            <label class="form-label">bar_booking_id</label>
                            <input type="text" class="form-control" disabled value="<?= $row['bar_booking_id'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <input type="hidden" name="bar_booking_id" value="<?= $row['bar_booking_id'] ?>">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">user_id</label>
                            <input type="text" class="form-control" id="user_id" name="user_id"
                                value="<?= $row['user_id'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="bar_id" class="form-label">bar_id</label>
                            <input type="text" class="form-control" id="bar_id" name="bar_id"
                                value="<?= $row['bar_id'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="bar_booking_time" class="form-label">bar_booking_time</label>
                            <input type="date" class="form-control" id="bar_booking_time" name="bar_booking_time"
                                value="<?= $row['bar_booking_time'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="bar_booking_people_num" class="form-label">bar_booking_people_num</label>
                            <input type="text" class="form-control" id="bar_booking_people_num" name="bar_booking_people_num"
                                value="<?= $row['bar_booking_people_num'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="bar_time_slot_id" class="form-label">bar_time_slot_id</label>
                            <select class="form-control" id="bar_time_slot_id" name="bar_time_slot_id">
                                <option value="1" <?php echo ($row['bar_time_slot_id'] == '1') ? 'selected' : ''; ?>>19:00-20:30</option>
                                <option value="2" <?php echo ($row['bar_time_slot_id'] == '2') ? 'selected' : ''; ?>>20:30-22:00</option>
                                <option value="3" <?php echo ($row['bar_time_slot_id'] == '3') ? 'selected' : ''; ?>>22:00-23:30</option>
                            </select> 
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
                <a type="button" class="btn btn-primary" href="./bar_booking_list.php">Return</a>
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
        bar_booking_id: document.form1.bar_booking_id.value,
        user_id: document.form1.user_id.value,
        bar_id: document.form1.bar_id.value,
        bar_booking_time: document.form1.bar_booking_time.value,
        bar_booking_people_num: document.form1.bar_booking_people_num.value,
        bar_time_slot_id: document.form1.bar_time_slot_id.value,
    };

    const sendForm = e => {
        // 阻止表單的默認提交行為，這樣頁面就不會刷新，允許我們用JavaScript處理表單提交。
        e.preventDefault();

  // 檢查是否有更改
        let isChanged = initialValues.bar_booking_id !== document.form1.bar_booking_id.value ||
                initialValues.user_id !== document.form1.user_id.value ||
                initialValues.bar_id !== document.form1.bar_id.value ||
                initialValues.bar_booking_time !== document.form1.bar_booking_time.value ||
                initialValues.bar_booking_people_num !== document.form1.bar_booking_people_num.value ||
                initialValues.bar_time_slot_id !== document.form1.bar_time_slot_id.value;

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

            fetch('bar_booking_edit-api.php', {
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