<?php
require '../_admin-required.php';
require '../parts/db_connect.php';
$pageName = 'edit';
$title = 'Edit';

$admin_user_id = isset($_GET['admin_user_id']) ? intval($_GET['admin_user_id']) : 0;
$sql = "SELECT * FROM admin_user WHERE admin_user_id=$admin_user_id";
$row = $pdo->query($sql)->fetch();
$base64Image = base64_encode($row['avatar_img']);

if (empty($row)) {
    header('Location: member_admin_user_list.php');
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
                    <li class="breadcrumb-item"><a href="member_admin_user_list.php" class="text-decoration-none">Account Center</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Admin User</li>
                </ol>
            </nav>
            <!-- add breadcrumb end -->
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Data</h5>
                        <form name="form1" method="post" onsubmit="sendForm(event)">
                            <div class="mb-3">
                                <label for="admin_user_id" class="form-label">admin_user_id</label>
                                <input type="text" class="form-control" id="admin_user_id" name="admin_user_id" readonly value="<?= $row['admin_user_id'] ?> ">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="admin_account" class="form-label">admin_account</label>
                                <input type="text" class="form-control" id="admin_account" name="admin_account" value="<?= $row['admin_account'] ?> ">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="admin_password_hash" class="form-label">admin_password_hash</label>
                                <input type="text" class="form-control" id="admin_password_hash" name="admin_password_hash" value="<?= $row['admin_password_hash'] ?> ">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="admin_email" class="form-label">admin_email</label>
                                <input type="text" class="form-control" id="admin_email" name="admin_email" value="<?= $row['admin_email'] ?> ">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="admin_permission" class="form-label">admin_permission</label>
                                <input type="text" class="form-control" id="admin_permission" name="admin_permission" value="<?= $row['admin_permission'] ?> ">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="avatar_URL" class="form-label">avatar</label><br>
                                <img class="rounded-circle mt-2" style="width: 50px;height:50px;" src="<?= $base64Image ? 'data:image/jpeg;base64,' . $base64Image : $row['google_avatar_url'] ?>" alt="">
                                <input type="text" class="form-control d-none" id="avatar_URL" name="avatar_URL" value="<?= $row['google_avatar_url'] ?> ">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="admin_photo" class="form-label">avatar_image</label>
                                <input type="file" class="form-control " id="admin_photo" name="admin_photo">
                                <div class="mb-3 invalid-feedback">Please provide a valid image.</div>
                                <!-- photo preview -->
                                <div class="mb-3" id="preview"></div>
                                <!-- photo preview -->
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
                    <a type="button" class="btn btn-primary" href="./member_admin_user_list.php">Return</a>
                </div>
            </div>
        </div>
    </div>

    <!-- ModalError -->
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Result</h1>
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

    <?php include '../parts/scripts.php' ?>
    <script>
        // Upload Photo Preview 
        document.getElementById('admin_photo').addEventListener('change', function(event) {
            const files = event.target.files;
            const previewContainer = document.getElementById('preview');
            previewContainer.innerHTML = ''; // 清空現有的預覽

            Array.from(files).forEach(file => {
                if (!file.type.startsWith('image/')) {
                    return;
                } // 確保文件類型為圖片

                const reader = new FileReader();

                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '400px'; // 設置預覽圖片的最大寬度
                    img.style.maxHeight = '240px'; // 設置預覽圖片的最大高度
                    img.style.objectFit = 'cover'; // 保持圖片比例
                    img.style.marginRight = '10px'; // 設置預覽圖片之間的間隔
                    previewContainer.appendChild(img);
                };

                reader.readAsDataURL(file);
            });
        });


        const sendForm = e => {
            // 阻止表單的默認提交行為，這樣頁面就不會刷新，允許我們用JavaScript處理表單提交。
            e.preventDefault();

            // TODO: 資料送出之前, 要做檢查(有沒有填寫, 格式對不對)
            let isPassed = true; // 表單有沒有通過檢查



            if (isPassed) {
                // 沒有外觀 的表單
                const fd = new FormData(document.form1);
                console.log('fd');

                const fileInput = document.getElementById('admin_photo');

                // 將檔案資訊加入 FormData
                for (const file of fileInput.files) {
                    fd.append('admin_photo', file);
                }

                fetch('member_admin_user_edit-api.php', {
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
                            myModalError.show();
                        }
                    })
                    .catch(ex => console.log(ex))
            }
        }
        const myModal = new bootstrap.Modal(document.getElementById('exampleModal'))
        const myModalError = new bootstrap.Modal(document.getElementById('exampleModal2'))
    </script>
    <?php include '../parts/html-foot.php' ?>