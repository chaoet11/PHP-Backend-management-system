<?php
require '../_admin-required.php';
require '../parts/db_connect.php';
$pageName = 'edit';
$title = 'Edit';

$bar_id = isset($_GET['bar_id']) ? intval($_GET['bar_id']) : 0;
$sql = "SELECT * FROM bars WHERE bar_id=$bar_id";

$row = $pdo->query($sql)->fetch();
if (empty($row)) {
    header('Location: bars_list.php');
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
                <li class="breadcrumb-item"><a href="bars_list.php" class="text-decoration-none">Bar</a></li>
                <li class="breadcrumb-item active" aria-current="page">Bars</li>
            </ol>
        </nav>
        <!-- add breadcrumb end -->
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Data</h5>
                    <form name="form1" method="post" onsubmit="sendForm(event)">
                        <div class="mb-3">
                            <label class="form-label">bar_id</label>
                            <input type="text" class="form-control" disabled value="<?= $row['bar_id'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <input type="hidden" name="bar_id" value="<?= $row['bar_id'] ?>">
                        <div class="mb-3">
                            <label for="bar_name" class="form-label">bar_name</label>
                            <input type="text" class="form-control" id="bar_name" name="bar_name"
                                value="<?= $row['bar_name'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="bar_city" class="form-label">bar_city</label>
                            <input type="text" class="form-control" id="bar_city" name="bar_city"
                                value="<?= $row['bar_city'] ?>" readonly>
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="bar_area_id" class="form-label">bar_area_id</label>
                            <select class="form-control" id="bar_area_id" name="bar_area_id">
                                <option value="1" <?php echo ($row['bar_area_id'] == '1') ? 'selected' : ''; ?>>松山區</option>
                                <option value="2" <?php echo ($row['bar_area_id'] == '2') ? 'selected' : ''; ?>>信義區</option>
                                <option value="3" <?php echo ($row['bar_area_id'] == '3') ? 'selected' : ''; ?>>大安區</option>
                                <option value="4" <?php echo ($row['bar_area_id'] == '4') ? 'selected' : ''; ?>>中山區</option>
                                <option value="5" <?php echo ($row['bar_area_id'] == '5') ? 'selected' : ''; ?>>中正區</option>
                                <option value="6" <?php echo ($row['bar_area_id'] == '6') ? 'selected' : ''; ?>>大同區</option>
                                <option value="7" <?php echo ($row['bar_area_id'] == '7') ? 'selected' : ''; ?>>萬華區</option>
                                <option value="8" <?php echo ($row['bar_area_id'] == '8') ? 'selected' : ''; ?>>文山區</option>
                                <option value="9" <?php echo ($row['bar_area_id'] == '9') ? 'selected' : ''; ?>>南港區</option>
                                <option value="10" <?php echo ($row['bar_area_id'] == '10') ? 'selected' : ''; ?>>內湖區</option>
                                <option value="11" <?php echo ($row['bar_area_id'] == '11') ? 'selected' : ''; ?>>士林區</option>
                                <option value="12" <?php echo ($row['bar_area_id'] == '12') ? 'selected' : ''; ?>>北投區</option>
                            </select>
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="bar_addr" class="form-label">bar_addr</label>
                            <input type="text" class="form-control" id="bar_addr" name="bar_addr"
                                value="<?= $row['bar_addr'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="bar_opening_time" class="form-label">bar_opening_time</label>
                            <input type="time" class="form-control" id="bar_opening_time" name="bar_opening_time"
                                value="<?= $row['bar_opening_time'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="bar_closing_time" class="form-label">bar_closing_time</label>
                            <input type="time" class="form-control" id="bar_closing_time" name="bar_closing_time"
                                value="<?= $row['bar_closing_time'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="bar_contact" class="form-label">bar_contact</label>
                            <input type="text" class="form-control" id="bar_contact" name="bar_contact"
                                value="<?= $row['bar_contact'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="bar_description" class="form-label">bar_description</label>
                            <input type="text" class="form-control" id="bar_description" name="bar_description"
                                value="<?= $row['bar_description'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="bar_type_id" class="form-label">bar_type_id</label>
                            <select class="form-control" id="bar_type_id" name="bar_type_id">
                                <option value="1" <?php echo ($row['bar_type_id'] == '1') ? 'selected' : ''; ?>>1. 運動酒吧 Sport Bar</option>
                                <option value="2" <?php echo ($row['bar_type_id'] == '2') ? 'selected' : ''; ?>>2. 音樂酒吧 Music Bar</option>
                                <option value="3" <?php echo ($row['bar_type_id'] == '3') ? 'selected' : ''; ?>>3. 異國酒吧 Foreign Bar</option>
                                <option value="4" <?php echo ($row['bar_type_id'] == '4') ? 'selected' : ''; ?>>4. 特色酒吧 Specialty Bar</option>
                                <option value="5" <?php echo ($row['bar_type_id'] == '5') ? 'selected' : ''; ?>>5. 其他 Other Bar</option>
                            </select>
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="bar_latitude" class="form-label">bar_latitude</label>
                            <input type="text" class="form-control" id="bar_latitude" name="bar_latitude"
                                value="<?= $row['bar_latitude'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="bar_longtitude" class="form-label">bar_longtitude</label>
                            <input type="text" class="form-control" id="bar_longtitude" name="bar_longtitude"
                                value="<?= $row['bar_longtitude'] ?>">
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
                <a type="button" class="btn btn-primary" href="./bars_list.php">Return</a>
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
        bar_id: document.form1.bar_id.value,
        bar_name: document.form1.bar_name.value,
        bar_city: document.form1.bar_city.value,
        bar_area_id: document.form1.bar_area_id.value,
        bar_addr: document.form1.bar_addr.value,
        bar_opening_time: document.form1.bar_opening_time.value,
        bar_closing_time: document.form1.bar_closing_time.value,
        bar_contact: document.form1.bar_contact.value,
        bar_description: document.form1.bar_description.value,
        bar_type_id: document.form1.bar_type_id.value,
        bar_latitude: document.form1.bar_latitude.value,
        bar_longtitude: document.form1.bar_longtitude.value
    };
    const sendForm = e => {
        // 阻止表單的默認提交行為，這樣頁面就不會刷新，允許我們用JavaScript處理表單提交。
        e.preventDefault();

        // 檢查是否有更改
        let isChanged = initialValues.bar_id !== document.form1.bar_id.value ||
                initialValues.bar_name !== document.form1.bar_name.value ||
                initialValues.bar_city !== document.form1.bar_city.value ||
                initialValues.bar_area_id !== document.form1.bar_area_id.value ||
                initialValues.bar_addr !== document.form1.bar_addr.value ||
                initialValues.bar_opening_time !== document.form1.bar_opening_time.value ||
                initialValues.bar_closing_time !== document.form1.bar_closing_time.value ||
                initialValues.bar_contact !== document.form1.bar_contact.value ||
                initialValues.bar_description !== document.form1.bar_description.value ||
                initialValues.bar_type_id !== document.form1.bar_type_id.value ||
                initialValues.bar_latitude !== document.form1.bar_latitude.value ||
                initialValues.bar_longtitude !== document.form1.bar_longtitude.value;

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

        fetch('bars_edit-api.php', {
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