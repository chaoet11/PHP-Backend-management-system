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
                        <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                        <li class="breadcrumb-item"><a href="booking_system_list.php" class="text-decoration-none">Booking System</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add</li>
                    </ol>
                </nav>
                <!-- add breadcrumb end -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add Data</h5>
                        <form name="form1" method="post" onsubmit="sendForm(event)">
                            <div class="mb-3">
                                <label for="booking_id" class="form-label">booking_id</label>
                                <input type="text" class="form-control" id="booking_id" name="booking_id">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="user_id" class="form-label">user_id</label>
                                <input type="text" class="form-control" id="user_id" name="user_id">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="service_id" class="form-label">service_id</label>
                                <input type="text" class="form-control" id="service_id" name="service_id">
                                <div class="form-text"></div>
                            <div class="mb-3">
                                <label for="points_change" class="form-label">points_change</label>
                                <input type="text" class="form-control" id="points_change" name="points_change">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="movie_date" class="form-label">movie_date</label>
                                <input type="date" class="form-control" id="movie_date" name="movie_date">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="movie_time" class="form-label">movie_time</label>
                                <input type="time" class="form-control" id="movie_time" name="movie_time">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="order_id" class="form-label">order_id</label>
                                <input type="text" class="form-control" id="order_id" name="order_id">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="order_status" class="form-label">order_status</label>
                                <!-- <input type="text" class="form-control" id="order_status" name="order_status"> -->
                                <select name="order_status" id="order_status">
                                    <option value="1" style="color: forestgreen">1:已付款</option>
                                    <option value="0" style="color: red;">0:未付款</option>
                                </select>
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">price</label>
                                <input type="text" class="form-control" id="price" name="price">
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
                            <div class="mb-3">
                                <label for="movie_id" class="form-label">movie_id</label>
                                <input type="text" class="form-control" id="movie_id" name="movie_id">
                                <div class="form-text"></div>
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
                <a type="button" class="btn btn-primary" href="./booking_system_list.php">Return</a>
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

            fetch('booking_system_add-api.php', {
                method: 'POST',
                body: fd, // content-type: multipart/form-data
            }).then(r => r.json())
                .then(result => {
                    console.log({
                        result
                    });
                    if (result.success) {
                        myModal.show();
                    }
                })
                .catch(ex => console.log(ex))
        }
    }
    const myModal = new bootstrap.Modal(document.getElementById('exampleModal'))
</script>
<?php include '../parts/html-foot.php' ?>