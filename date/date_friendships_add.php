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
                        <li class="breadcrumb-item"><a href="date_friendships_list.php" class="text-decoration-none">Friendships</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add</li>
                    </ol>
                </nav>
                <!-- add breadcrumb end -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add Data</h5>
                        <form name="form1" method="post" class="needs-validation" novalidate onsubmit="sendForm(event)">
                            <!-- <div class="mb-3">
                                <label for="friendship_id" class="form-label">friendship_id</label>
                                <input type="text" class="form-control" id="friendship_id" name="friendship_id">
                                <div class="form-text"></div>
                            </div> -->
                            <div class="mb-3">
                                <label for="user_id1" class="form-label">user_id1</label>
                                <input type="text" class="form-control" id="user_id1" name="user_id1" minlength="1" maxlength="11" required>
                                <div class="invalid-feedback">Please provide a valid user_id1.</div>
                                <div class="form-text" id="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="user_id2" class="form-label">user_id2</label>
                                <input type="text" class="form-control" id="user_id2" name="user_id2" required>
                                <div class="invalid-feedback">Please provide a valid user_id2.</div>
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="friendship_status" class="form-label">Friendship Status</label>
                                <select class="form-select" id="friendship_status" name="friendship_status" required>
                                    <option value="" disabled selected>Please choose friendship status.</option>
                                    <option value="pending">pending</option>
                                    <option value="accepted">accepted</option>
                                    <option value="blocked">blocked</option>
                                </select>
                                <div class="invalid-feedback">Please provide a valid Friendship Status.</div>
                                <div class="form-text"></div>
                            </div>
                            <!-- <div class="mb-3">
                                <label for="created_at" class="form-label">created_at</label>
                                <input type="text" class="form-control" id="created_at" name="created_at">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="updated_at" class="form-label">updated_at</label>
                                <input type="text" class="form-control" id="updated_at" name="updated_at">
                                <div class="form-text"></div>
                            </div> -->
                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
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
                <a type="button" class="btn btn-primary" href="./date_friendships_list.php">Return</a>
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

<!-- Duplicates Modal -->
<div class="modal fade" id="duplicatesModal" tabindex="-1" aria-labelledby="duplicatesModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="duplicatesModalLabel">Addition Result</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert">
                    Duplicates friendship status
                </div>
            </div>
            <div class="modal-footer">
                <a type="button" class="btn btn-primary" href="./date_friendships_add.php">Close</a>
            </div>
        </div>
    </div>
</div>
<!-- Duplicates Modal -->

<?php include '../parts/scripts.php' ?>
<script>
    const sendForm = e => {
        e.preventDefault();

        let isPassed = true;

        if (isPassed) {
            const fd = new FormData(document.form1);

            // 新增判斷是否有重複的組合 (user_id1, user_id2)
            fetch('date_friendships_add-api.php?checkDuplicate=true', {
                method: 'POST',
                body: fd,
            }).then(r => r.json())
                .then(result => {
                    console.log({
                        result
                    });
                    if (result.exists) {
                        // 顯示重複錯誤消息
                        duplicatesModal.show();
                    } else if (result.success) {
                        // 顯示成功消息
                        myModal.show();
                        // 清空表單
                        document.form1.reset();
                        // 移除所有驗證錯誤樣式和提示訊息
                        clearValidationErrors();
                        // 重置表單的 HTML5 驗證狀態
                        document.form1.classList.remove('was-validated');
                    } else {
                        // 顯示一般錯誤消息
                        errorModal.show();
                    }
                })
                .catch(ex => console.log(ex));
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
    const duplicatesModal = new bootstrap.Modal(document.getElementById('duplicatesModal'));
</script>
<?php include '../parts/html-foot.php' ?>