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
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add Data</h5>
                        <form name="form1" method="post" class="needs-validation" novalidate onsubmit="sendForm(event)" enctype="multipart/form-data">

                            <div class="mb-3">
                                <label for="admin_account" class="form-label">admin_account</label>
                                <input type="text" class="form-control" id="admin_account" name="admin_account" required>
                                <div class="invalid-feedback" id="admin_account_feedback">Please provide a valid admin_account.</div>
                            </div>
                            <div class="mb-3">
                                <label for="admin_password" class="form-label">admin_password</label>
                                <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                                <div class="invalid-feedback">Please provide a valid admin_password.</div>
                            </div>
                            <div class="mb-3">
                                <label for="admin_email" class="form-label">admin_email</label>
                                <input type="email" class="form-control" id="admin_email" name="admin_email" required>
                                <div class="invalid-feedback">Please provide a valid admin_email.</div>
                            </div>
                            <div class="mb-3">
                                <label for="admin_permission" class="form-label">admin_permission</label>
                                <input type="text" class="form-control" id="admin_permission" name="admin_permission" required>
                                <div class="invalid-feedback">Please provide a valid admin_permission.</div>
                            </div>
                            <div class="mb-3 d-none">
                                <label for="avatar_URL" class="form-label">avatar_URL</label>
                                <input type="text" class="form-control" id="avatar_URL" name="avatar_URL">
                            </div>
                            <div class="mb-3">
                                <label for="admin_photo" class="form-label">avatar_image</label>
                                <input type="file" class="form-control " id="admin_photo" name="admin_photo" required>
                                <div class="mb-3 invalid-feedback">Please provide a valid image.</div>
                                <!-- photo preview -->
                                <div class="mb-3" id="preview"></div>
                                <!-- photo preview -->
                            </div>

                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                    </div>
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
                <h1 class="modal-title fs-5" id="exampleModalLabel">Addition</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success" role="alert">
                    Successfully added
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continue</button>
                <a type="button" class="btn btn-primary" href="./member_admin_user_list.php">Return</a>
            </div>
        </div>
    </div>
</div>

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
                <h1 class="modal-title fs-5" id="duplicatesModalLabel">Result</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert">
                    Admin email already exists.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Duplicates Modal -->

<?php include '../parts/scripts.php' ?>
<script>
    const {
        name: name_f,
        email: email_f,
    } = document.form1;

    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
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


    const sendForm = e => {
        // 阻止表單的默認提交行為，這樣頁面就不會刷新，允許我們用JavaScript處理表單提交。
        e.preventDefault();

        // 獲取表單元素
        const admin_account_f = document.form1.admin_account;
        const admin_email_f = document.form1.admin_email;

        // TODO: 資料送出之前, 要做檢查(有沒有填寫, 格式對不對)
        let isPassed = true; // 表單有沒有通過檢查

        // 重置驗證提示
        admin_account_f.classList.remove('is-invalid');
        admin_email_f.classList.remove('is-invalid');

        // 前端驗證
        if (admin_account_f.value.length < 2) {
            isPassed = false;
            admin_account_f.classList.add('is-invalid');
        }

        const admin_account_fid = document.getElementById('admin_account');
        const admin_account_feedback = document.getElementById('admin_account_feedback');

        admin_account_fid.addEventListener('input', function() {
            // 這裡可以加上其他條件檢查，確保輸入的值是有效的
            if (admin_account_fid.value.length >= 2) {
                admin_account_feedback.style.display = 'none'; // 隱藏錯誤提示
            }
        });

        if (admin_email_f.value && !validateEmail(admin_email_f.value)) {
            isPassed = false;
            admin_email_f.classList.add('is-invalid');
        }

        if (isPassed) {
            // 沒有外觀 的表單
            const fd = new FormData(document.form1);
            // console.log(fd); // 檢查送出的資料
            if (fd.get('avatar_URL') === '') {
                fd.set('avatar_URL', '/Taipei_Date/account/img/default_admin.jpg');
                // 替換成您的預設 URL
                // 'https://media.istockphoto.com/id/1495088043/vector/user-profile-icon-avatar-or-person-icon-profile-picture-portrait-symbol-default-portrait.jpg?s=612x612&w=0&k=20&c=dhV2p1JwmloBTOaGAtaA3AW1KSnjsdMt7-U_3EZElZ0=';
            }


            fetch('member_admin_user_add-api.php', {
                    method: 'POST',
                    body: fd, // content-type: multipart/form-data
                }).then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(result => {
                    console.log({
                        result
                    });
                    if (result.success) {
                        // 顯示成功消息
                        myModal.show();
                        // 清空表單
                        document.form1.reset();
                        // 移除所有驗證錯誤樣式和提示訊息
                        clearValidationErrors();
                        // 重置表單的 HTML5 驗證狀態
                        document.form1.classList.remove('was-validated');
                    } else if (result.code === 1) {
                        // Show the duplicates modal if the email already exists
                        const duplicatesModal = new bootstrap.Modal(document.getElementById('duplicatesModal'));
                        duplicatesModal.show();
                    } else {
                        // 顯示失敗視窗
                        console.log({
                            result
                        });
                        errorModal.show();
                    }
                })
                .catch(error => {
                    console.log(error);
                    // console.error('Fetch error:', error);
                });
        }
    }

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

    // 添加 Bootstrap 驗證
    (function() {
        'use strict';

        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();

    const myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
</script>
<?php include '../parts/html-foot.php' ?>