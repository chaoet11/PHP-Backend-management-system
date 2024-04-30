<?php
require '../_admin-required.php';
require '../parts/db_connect.php';
$pageName = 'edit';
$title = 'Edit';

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$sql = "SELECT * FROM member_user WHERE user_id=$user_id";

$row = $pdo->query($sql)->fetch();
if (empty($row)) {
    header('Location: member_user_list.php');
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
            <h5>Account Center</h5>
            <!-- add breadcrumb start -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="member_user_list.php" class="text-decoration-none">Account Center</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Member User</li>
                </ol>
            </nav>
            <!-- add breadcrumb end -->
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Data</h5>
                        <form name="form1" method="post" onsubmit="sendForm(event)">
                            <div class="mb-3">
                                <label for="user_id" class="form-label">user_id</label>
                                <input type="text" class="form-control" id="user_id" name="user_id" readonly value="<?= $row['user_id'] ?> ">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?= $row['username'] ?> ">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="account" class="form-label">account</label>
                                <input type="text" class="form-control" id="account" name="account" value="<?= $row['account'] ?> ">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="password_hash" class="form-label">password_hash</label>
                                <input type="text" class="form-control" id="password_hash" name="password_hash" value="<?= $row['password_hash'] ?> ">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">email</label>
                                <input type="text" class="form-control" id="email" name="email" value="<?= $row['email'] ?> ">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="profile_picture_url" class="form-label">profile_picture_url</label>
                                <input type="text" class="form-control" id="profile_picture_url" name="profile_picture_url" value="<?= $row['profile_picture_url'] ?> ">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="gender" class="form-label">gender</label>
                                <input type="text" class="form-control" id="gender" name="gender" value="<?= $row['gender'] ?> ">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="user_active" class="form-label">user_active</label>
                                <input type="text" class="form-control" id="user_active" name="user_active" value="<?= $row['user_active'] ?> ">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="birthday" class="form-label">birthday</label>
                                <input type="text" class="form-control" id="birthday" name="birthday" value="<?= $row['birthday'] ?> ">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="mobile" class="form-label">mobile</label>
                                <input type="text" class="form-control" id="mobile" name="mobile" value="<?= $row['mobile'] ?> ">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="profile_content" class="form-label">profile_content</label>
                                <input type="text" class="form-control" id="profile_content" name="profile_content" value="<?= $row['profile_content'] ?> ">
                                <div class="form-text"></div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
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
                    <a type="button" class="btn btn-primary" href="./member_user_list.php">Return</a>
                </div>
            </div>
        </div>
    </div>

    <!--Edit Failed Modal -->
    <div class="modal fade" id="editFailedModal" tabindex="-1" aria-labelledby="editFailedModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editFailedModalLabel">Result</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert">
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
            email: document.form1.email.value,
            user_id: document.form1.user_id.value
        };

        const sendForm = e => {
            // 阻止表單的默認提交行為，這樣頁面就不會刷新，允許我們用JavaScript處理表單提交。
            e.preventDefault();

            // 檢查是否有更改
            let isChanged = initialValues.email !== document.form1.email.value ||
                initialValues.user_id !== document.form1.user_id.value;
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

                fetch('member_user_edit-api.php', {
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
                    })
                    .catch(ex => {
                        console.log(ex);
                        editFailedModal.show();
                    })
            }
        }
        const myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
        const editFailedModal = new bootstrap.Modal(document.getElementById('editFailedModal'));
        const noEditModal = new bootstrap.Modal(document.getElementById('noEditModal'));
    </script>
    <?php include '../parts/html-foot.php' ?>