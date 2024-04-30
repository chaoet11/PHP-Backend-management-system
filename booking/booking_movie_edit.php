<?php
require '../_admin-required.php';
require '../parts/db_connect.php';
$pageName = 'edit';
$title = '編輯';

$movie_id = isset($_GET['movie_id']) ? intval($_GET['movie_id']) : 0;
$sql = "SELECT * FROM booking_movie WHERE movie_id=$movie_id";

$row = $pdo->query($sql)->fetch();
if (empty($row)) {
    header('Location: booking_movie_list.php');
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
        <h5>Booking System</h5>
        <!-- add breadcrumb start -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="booking_movie_list.php" class="text-decoration-none">Booking</a></li>
                <li class="breadcrumb-item active" aria-current="page">Movie</li>
            </ol>
        </nav>
        <!-- add breadcrumb end -->
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">編輯資料</h5>
                    <form name="form1" method="post" onsubmit="sendForm(event)">
                        <div class="mb-3">
                            <label class="form-label">movie_id</label>
                            <input type="text" class="form-control" disabled value="<?= $row['movie_id'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <input type="hidden" name="movie_id" value="<?= $row['movie_id'] ?>">
                        <div class="mb-3">
                            <label for="title" class="form-label">title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="<?= $row['title'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="poster_img" class="form-label">poster_img</label>
                            <input type="text" class="form-control" id="poster_img" name="poster_img"
                                value="<?= $row['poster_img'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="movie_description" class="form-label">movie_description</label>
                            <textarea class="form-control" name="movie_description" id="movie_description" cols="30"
                                rows="3"><?= $row['movie_description'] ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="movie_rating" class="form-label">movie_rating</label>
                            <input type="text" class="form-control" id="movie_rating" name="movie_rating"
                                value="<?= $row['movie_rating'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="movie_type_id" class="form-label">movie_type_id</label>
                            <input type="text" class="form-control" id="movie_type_id" name="movie_type_id"
                                value="<?= $row['movie_type_id'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <button type="submit" class="btn btn-primary">修改</button>
                    </form>
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
                <a type="button" class="btn btn-primary" href="./booking_movie_list.php">Return</a>
            </div>
        </div>
    </div>
</div>
<!-- Success Modal -->

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
        movie_id: document.form1.movie_id.value,
        title: document.form1.title.value,
        poster_img: document.form1.poster_img.value,
        movie_description: document.form1.movie_description.value,
        movie_rating: document.form1.movie_rating.value,
        movie_type_id: document.form1.movie_type_id.value
    };

    const sendForm = e => {
        // 阻止表單的默認提交行為，這樣頁面就不會刷新，允許我們用JavaScript處理表單提交。
        e.preventDefault();

        let isChanged = initialValues.movie_id !== document.form1.movie_id.value ||
                        initialValues.title !== document.form1.title.value ||
                        initialValues.poster_img !== document.form1.poster_img.value ||
                        initialValues.movie_description !== document.form1.movie_description.value ||
                        initialValues.movie_rating !== document.form1.movie_rating.value ||
                        initialValues.movie_type_id !== document.form1.movie_type_id.value;

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

            fetch('booking_movie_edit-api.php', {
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
                })
                .catch(ex => console.log(ex))
        }
    }
    const myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
    const editFailedModal = new bootstrap.Modal(document.getElementById('editFailedModal'));
    const noEditModal = new bootstrap.Modal(document.getElementById('noEditModal'));
</script>
<?php include '../parts/html-foot.php' ?>