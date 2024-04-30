<?php include __DIR__ . '/parts/db_connect.php';
$pageName = 'home';
$title = 'Home Page';
?>

<?php
require __DIR__ . '/parts/db_connect.php';
$pageName = 'login';
$title = 'Sign IN';

if (isset($_SESSION['admin'])) {
    header('Location: _index.php');
    exit;
}

?>

<?php include __DIR__ . '/parts/html-head.php' ?>

<div class="container-fluid">
    <div class="row">
        <!-- navbar -->
        <nav class="navbar navbar-expand-lg col-12" style="background-color: #003e52">
            <div class="container-fluid">
                <?php include __DIR__ . '/parts/navbar.php' ?>
            </div>
        </nav>
        <!-- navbar -->

        <!-- sidebar -->
        <?php include __DIR__ . '/parts/sidebar.php' ?>
        <!-- sidebar -->
        <div class="col-12 col-md-8 col-lg-10" style="background-color: #003e52">
            <div class="col-6">
                <h2 style="color: #bc955c">Home page</h2>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Sign In</h5>
                        <form name="form1" method="post" onsubmit="sendForm(event)">
                            <div class="mb-3">
                                <label for="email" class="form-label">email</label>
                                <input type="text" class="form-control" id="email" name="email">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <div class="form-text"></div>
                            </div>
                            <button type="submit" class="btn btn-primary mb-2">Sign In</button>

                            <?php if (!isset($_GET['code'])) : ?>
                                <div class="mb-3">
                                    <a href="<?= $client->createAuthUrl() ?>" class="btn btn-primary">
                                        <i class="bi bi-google"></i>Login with Google Account
                                    </a>
                                </div>
                            <?php endif; ?>
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
                <h1 class="modal-title fs-5" id="exampleModalLabel">Result</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-primary" role="alert">
                    Incorrect account or password
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Continue to Login</button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/parts/scripts.php' ?>

<script>
    const sendForm = e => {
        // 阻止表單的默認提交行為，這樣頁面就不會刷新，允許我們用JavaScript處理表單提交。
        e.preventDefault();

        // TODO: 資料送出之前, 要做檢查(有沒有填寫, 格式對不對)
        let isPassed = true; // 表單有沒有通過檢查

        if (isPassed) {
            // 沒有外觀 的表單
            const fd = new FormData(document.form1);

            fetch('_login-api.php', {
                    method: 'POST',
                    body: fd, // content-type: multipart/form-data
                }).then(r => r.json())
                .then(result => {
                    console.log({
                        result
                    });
                    if (result.success) {
                        location.href = '_index.php';
                    } else {
                        myModal.show();
                    }
                })
                .catch(ex => console.log(ex))
        }
    }
    const myModal = new bootstrap.Modal(document.getElementById('exampleModal'))
</script>

<?php include __DIR__ . '/parts/html-foot.php' ?>