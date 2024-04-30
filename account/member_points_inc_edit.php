<?php
require '../_admin-required.php';
require '../parts/db_connect.php';
$pageName = 'edit';
$title = 'Edit';

$points_increase_id = isset($_GET['points_increase_id']) ? intval($_GET['points_increase_id']) : 0;
$sql = "SELECT * FROM member_points_inc WHERE points_increase_id=$points_increase_id";

$row = $pdo->query($sql)->fetch();
if (empty($row)) {
    header('Location: member_points_inc_list.php');
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
                    <li class="breadcrumb-item"><a href="member_points_inc_list.php" class="text-decoration-none">Account Center</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Member Points Inc</li>
                </ol>
            </nav>
            <!-- add breadcrumb end -->
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Data</h5>
                        <form name="form1" method="post" onsubmit="sendForm(event)">
                            <div class="mb-3">
                                <label for="points_increase_id" class="form-label">points_increase_id</label>
                                <input type="text" class="form-control" id="points_increase_id" name="points_increase_id" readonly  value="<?= $row['points_increase_id'] ?> ">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="user_id" class="form-label">user_id</label>
                                <input type="text" class="form-control" id="user_id" name="user_id" value="<?= $row['user_id'] ?> ">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="points_increase" class="form-label">points_increase</label>
                                <input type="text" class="form-control" id="points_increase" name="points_increase" value="<?= $row['points_increase'] ?> ">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="reason" class="form-label">reason</label>
                                <select class="form-control" name="reason" id="reason" value="<?= $row['reason'] ?> ">
                                    <option value="Log-in Bonus">Log-in Bonus</option>
                                    <option value="Play Games Bonus">Play Games Bonus</option>
                                </select>
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="created_at" class="form-label">created_at</label>
                                <input type="text" class="form-control" id="created_at" name="created_at" value="<?= $row['created_at'] ?> ">
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
                    <a type="button" class="btn btn-primary" href="./member_points_inc_list.php">Return</a>
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
        const sendForm = e => {
            // 阻止表單的默認提交行為，這樣頁面就不會刷新，允許我們用JavaScript處理表單提交。
            e.preventDefault();

            // TODO: 資料送出之前, 要做檢查(有沒有填寫, 格式對不對)
            let isPassed = true; // 表單有沒有通過檢查

            if (isPassed) {
                // 沒有外觀 的表單
                const fd = new FormData(document.form1);

                fetch('member_points_inc_edit-api.php', {
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