<?php
require '../_admin-required.php';
require '../parts/db_connect.php';
$pageName = 'edit';
$title = 'Edit';

$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
$sql = "SELECT * FROM comm_post WHERE post_id=$post_id";

$row = $pdo->query($sql)->fetch();
if (empty($row)) {
    header('Location: comm_post_list.php');
    exit; # 結束 php 程式 
}

$sql_comm_photo = "SELECT p.*, ph.img FROM comm_post p LEFT JOIN comm_photo ph ON p.post_id = ph.post_id WHERE p.post_id=?";
$stmt = $pdo->prepare($sql_comm_photo);
$stmt->execute([$post_id]);
$row = $stmt->fetch();

// 
if (!empty($row['img'])) {
    $imageData = base64_encode($row['img']);
    $imageSrc = 'data:image/jpeg;base64,' . $imageData;
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
                <li class="breadcrumb-item"><a href="comm_post_list.php" class="text-decoration-none">Community</a></li>
                <li class="breadcrumb-item active" aria-current="page">Post</li>
            </ol>
        </nav>
        <!-- add breadcrumb end -->
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit</h5>
                    <form name="form1" method="post" enctype="multipart/form-data" novalidate>
                        <div class="mb-3">
                            <label class="form-label">post_id</label>
                            <input type="text" class="form-control" disabled value="<?= $row['post_id'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <input type="hidden" name="post_id" value="<?= $row['post_id'] ?>">
                        <div class="mb-3">
                            <label for="context" class="form-label">context</label>
                            <textarea class="form-control" name="context" id="context" cols="30" rows="3" required><?= $row['context'] ?></textarea>
                            <div class="invalid-feedback">Context is required.</div>
                        </div>
                        <div class="mb-3">
                            <label for="user_id" class="form-label">user_id</label>
                            <input type="text" class="form-control" id="user_id" name="user_id" pattern="\d*" value="<?= $row['user_id'] ?>" required>
                            <div class="invalid-feedback">User ID must be a number.</div>
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Image</label>
                            <input type="file" class="form-control" id="photo" name="photo">
                            <!-- Preview -->
                            <img src="<?= $imageSrc ?>" id="preview" alt="Image preview" style="width: 320px; height: 213px; margin-top: 10px;">
                            <!-- Preview -->
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continue</button>
                <a type="button" class="btn btn-primary" href="./comm_post_list.php">Return</a>
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
    // 在DOMContentLoaded監聽器外部初始化initialValues
    let initialValues = {
        context: '',
        user_id: ''
    };
    let isImageChanged = false; // 初始設為false

    document.addEventListener('DOMContentLoaded', function () {
        // 初始化initialValues的值
        initialValues.context = document.form1.context.value;
        initialValues.user_id = document.form1.user_id.value;

        // 為表單提交添加監聽器
        document.forms['form1'].addEventListener('submit', function (e) {
            e.preventDefault(); // 阻止表單默認提交行為

            // 檢查表單是否有效
            if (!this.checkValidity()) {
                e.stopPropagation(); // 阻止事件繼續傳播
            } else {
                // 檢查是否有更改
                let isChanged = initialValues.context !== this.context.value ||
                                initialValues.user_id !== this.user_id.value ||
                                isImageChanged;
                // 如果未更改，顯示彈出窗口
                if (!isChanged) {
                    noEditModal.show();
                    return;
                }

                // 如果有更改，則執行表單提交邏輯
                const fd = new FormData(this);

                fetch('comm_post_edit-api.php', {
                    method: 'POST',
                    body: fd,
                }).then(r => r.json())
                .then(result => {
                    if (result.success) {
                        myModal.show(); // 編輯成功，顯示成功模態框

                        // 更新initialValues以反映最新的表單值
                        initialValues.context = document.form1.context.value;
                        initialValues.user_id = document.form1.user_id.value;
                        isImageChanged = false; // 重設圖片更改標誌
                    } else {
                        editFailedModal.show(); // 編輯失敗，顯示失敗模態框
                    }
                }).catch(ex => {
                    console.error(ex);
                    editFailedModal.show(); // 發生異常，顯示失敗模態框
                });
            }

            this.classList.add('was-validated'); // 添加Bootstrap驗證樣式
        });

        // 監聽文件選擇變化
        document.getElementById('photo').addEventListener('change', function (event) {
            if (event.target.files.length > 0) {
                isImageChanged = true; // 更新isImageChanged為true
                const file = event.target.files[0];
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('preview').src = e.target.result; // 更新圖片預覽
                };
                reader.readAsDataURL(file);
            }
        });
    });

    const myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
    const editFailedModal = new bootstrap.Modal(document.getElementById('editFailedModal'));
    const noEditModal = new bootstrap.Modal(document.getElementById('noEditModal'));
</script>
<?php include '../parts/html-foot.php' ?>