<?php
require '../_admin-required.php';
require '../parts/db_connect.php';
$pageName = 'edit';
$title = 'Edit';

$show_time_id = isset($_GET['show_time_id']) ? intval($_GET['show_time_id']) : 0;
$sql = "SELECT * FROM booking_showtime_system WHERE show_time_id=$show_time_id";

$row = $pdo->query($sql)->fetch();
if (empty($row)) {
    header('Location: booking_seat_system_list.php');
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
        <h5>Booking</h5>
        <!-- add breadcrumb start -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="booking_showtime_system_list.php" class="text-decoration-none">Booking</a></li>
                <li class="breadcrumb-item active" aria-current="page">Showtime System</li>
            </ol>
        </nav>
        <!-- add breadcrumb end -->
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Data</h5>
                    <form name="form1" method="post" onsubmit="sendForm(event)">
                        <div class="mb-3">
                            <label class="form-label">show_time_id</label>
                            <input type="text" class="form-control" disabled value="<?= $row['show_time_id'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <input type="hidden" name="show_time_id" value="<?= $row['show_time_id'] ?>">
                        <div class="mb-3">
                            <label for="room_id" class="form-label">room_id</label>
                            <input type="text" class="form-control" id="room_id" name="room_id"
                                value="<?= $row['room_id'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="movie_id" class="form-label">movie_id</label>
                            <input type="text" class="form-control" id="movie_id" name="movie_id"
                                value="<?= $row['movie_id'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="movie_time" class="form-label">movie_time</label>
                            <input type="time" class="form-control" id="movie_time" name="movie_time"
                                value="<?= $row['movie_time'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="seat_count" class="form-label">seat_count</label>
                            <input type="text" class="form-control" id="seat_count" name="seat_count"
                                value="<?= $row['seat_count'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="movie_date" class="form-label">movie_date</label>
                            <input type="date" class="form-control" id="movie_date" name="movie_date"
                                value="<?= $row['movie_date'] ?>">
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
                <a type="button" class="btn btn-primary" href="./booking_showtime_system_list.php">Return</a>
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

            fetch('booking_showtime_system_edit-api.php', {
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