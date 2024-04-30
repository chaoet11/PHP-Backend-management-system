
<?php 
    include '../parts/db_connect.php';
    $pageName = 'list';
    $title = '列表';

    $perPage = 20;

    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    if($page < 1){
        // redirect
        header('Location: ?page=1');
        exit;
    }

    $t_sql = "SELECT COUNT(1) FROM booking_movie";
    $t_stmt = $pdo->query($t_sql);
    $row = $t_stmt->fetch(PDO::FETCH_NUM);
    // $row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

    // print_r($row); 
    // exit; #直接離開程式
    $totalRows = $row[0]; # 取得總筆數
    $totalPages = 0; # 預設值
    $rows = []; # 預設值

    if($totalRows > 0) {
        $totalPages = ceil($totalRows / $perPage);

        if($page > $totalPages) {
            // redirect
            header('Location: ?page='. $totalPages);
            exit;
        }

        $sql = sprintf("SELECT * FROM booking_movie ORDER BY movie_id DESC
                    LIMIT %s, %s", ($page-1)*$perPage, $perPage);
        $stmt = $pdo->query($sql); 
        $rows = $stmt->fetchAll(); 
    }
?>

<?php include '../parts/html-head.php'?>

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
                <li class="breadcrumb-item"><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="booking_movie_list.php" class="text-decoration-none">Booking</a></li>
                <li class="breadcrumb-item active" aria-current="page">Movie</li>
            </ol>
        </nav>
        <!-- add breadcrumb end -->

        <!-- pagination -->

        <?php include '../parts/pagination.php'?>

        <!-- pagination -->
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-primary">
                <thead>
                    <tr>
                        <th>movie_id</th>
                        <th>title</th>
                        <th>poster_img</th>
                        <th>movie_description</th>
                        <th>movie_rating</th>
                        <th>movie_type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rows as $row): ?>
                    <tr>
                        <td><?= $row['movie_id'] ?></td>
                        <td><?= $row['title'] ?></td>
                        <td><?= $row['poster_img'] ?></td>
                        <td><?= $row['movie_description'] ?></td>
                        <td><?= $row['movie_rating'] ?></td>
                        <td><?= $row['movie_type'] ?></td>
                        <!-- strip_tags -->
                        <!-- 避免 XSS 攻擊問題 -->
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>
</div>  

<?php include '../parts/scripts.php' ?>
<script>
    function delete_one(movie_id){
        if(confirm(`是否要刪除編號為 ${movie_id} 的資料?`)){
            location.href = `booking_movie_delete.php?movie_id=${movie_id}`
        }
    }
</script>
<?php include '../parts/html-foot.php' ?>

