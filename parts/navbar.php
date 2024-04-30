<link rel="stylesheet" href="/Taipei_Date/parts/style.css">

<?php
if (empty($pageName)) {
    $pageName = "";
}
?>
<?php if (isset($_SESSION['admin']) && isset($_SESSION['admin']['id'])) : ?>
    <?php
    // 假設你的 admin_user_id 存儲在 $_SESSION['admin']['id'] 中
    $userId = $_SESSION['admin']['id'];

    // 使用資料庫查詢獲取頭像資訊
    $query = "SELECT avatar_img, google_avatar_url FROM admin_user WHERE admin_user_id = :admin_user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':admin_user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    // 獲取結果
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
<?php endif ?>



<div class="container-fluid border-bottom border-primary px-0">
    <nav class="navbar navbar-expand-lg" style="background-color: #003e52">
        <div class="container-fluid px-0">
            <h3 class="text-primary mb-0 ms-0" style="margin-left: 0;">Taipei Date</h3>
            <!-- <a class="navbar-brand" href="./index.php">Navbar</a> -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= $pageName == 'list' ? 'active' : '' ?>" href="./comm_post_list.php">列表</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $pageName == 'add' ? 'active' : '' ?>" href="./comm_post_add.php">新增</a>
                    </li>
                </ul> -->
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 d-flex align-items-center">
                <?php if (isset($_SESSION['admin'])) : ?>
                        <li class="nav-item">
                            <div class="d-flex align-items-center">
                                <?php if (!empty($result['avatar_img'])) : ?>
                                    <?php
                                    $base64Image = base64_encode($result['avatar_img']);
                                    echo '<img class="rounded-circle me-2" style="width:35px;height:35px;" src="data:image/jpeg;base64,' . $base64Image . '" alt=""><a class="nav-link fs-5"> ' . $_SESSION['admin']['nickname'] . '</a>';
                                    ?>
                                <?php elseif (!empty($result['google_avatar_url'])) : ?>
                                    <img class="rounded-circle me-2" style="width:35px;height:35px;" src="<?= $result['google_avatar_url']; ?>" alt=""><a class="nav-link fs-5"><?= $_SESSION['admin']['nickname']; ?></a>
                                <?php else : ?>
                                    <a class="nav-link fs-5"><?= $_SESSION['admin']['nickname']; ?></a>
                                <?php endif ?>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/Taipei_Date/_logout.php"><i class="bi bi-box-arrow-right fs-4"></i></a>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $pageName == 'login' ? 'active' : '' ?>" href="/Taipei_Date/_login.php">Sign in</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $pageName == 'register' ? 'active' : '' ?>" href="/Taipei_Date/_register.php">Register</a>
                        </li>
                    <?php endif ?>
                </ul>
            </div>
        </div>
    </nav>
</div>