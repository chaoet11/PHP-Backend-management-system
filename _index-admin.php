<?php include __DIR__.'/parts/db_connect.php';
    $pageName = 'home';
    $title = 'Home Page';
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
        <?php include  __DIR__ .'/parts/sidebar.php' ?>
        <!-- sidebar -->
        <div class="col-12 col-md-8 col-lg-10" style="background-color: #003e52">
            <div class="col-6">
                <h2 style="color: #bc955c">Home page</h2>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/parts/scripts.php' ?>
<?php include __DIR__ . '/parts/html-foot.php' ?>

