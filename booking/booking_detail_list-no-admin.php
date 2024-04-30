
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

    $t_sql = "SELECT COUNT(1) FROM booking_detail";
    $t_stmt = $pdo->query($t_sql);
    $row = $t_stmt->fetch(PDO::FETCH_NUM);
    // $row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

    // print_r($row); 
    // exit; #直接離開程式
    $totalRows = $row[0]; # 取得總筆數
    $totalPages = 0; # 預設值
    $rows = []; # 預設值

    // 假設默認排序是按 booking_detail_id 升序
    $sortColumn = $_GET['sort'] ?? 'booking_detail_id';
    $order = $_GET['order'] ?? 'ASC';

    // 確保只允許特定的列名和排序方向
    $allowedSortColumns = ['booking_detail_id', 'booking_id', 'seat_id', 'booking_type'];
    $allowedOrder = ['ASC', 'DESC'];
    $sortColumn = in_array($sortColumn, $allowedSortColumns) ? $sortColumn : 'booking_detail_id';
    $order = in_array(strtoupper($order), $allowedOrder) ? strtoupper($order) : 'ASC';   

    if($totalRows > 0) {
        $totalPages = ceil($totalRows / $perPage);

        if($page > $totalPages) {
            // redirect
            header('Location: ?page='. $totalPages);
            exit;
        }

        // 修改 SQL 查詢以包含排序參數
        $sql = sprintf("SELECT * FROM booking_detail ORDER BY %s %s LIMIT %s, %s", $sortColumn, $order, ($page-1)*$perPage, $perPage);
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
                <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="booking_detail_list.php" class="text-decoration-none">Booking</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
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
                    <th>
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                            <span>booking_detail_id</span>
                            <a href="?sort=booking_detail_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                            </a>
                            </div>
                        </th>
                        <th>
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                            <span>booking_id</span>
                            <a href="?sort=booking_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i> 
                            </a>
                            </div>
                        </th>
                        <th>
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                            <span>seat_id</span>
                            <a href="?sort=seat_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                            </a>
                            </div>
                        </th>
                        <th>
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                            <span>booking_type</span>
                            <a href="?sort=booking_type&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i> 
                            </a>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rows as $row): ?>
                    <tr>
                        <td><?= $row['booking_detail_id'] ?></td>
                        <td><?= $row['booking_id'] ?></td>
                        <td><?= $row['seat_id'] ?></td>
                        <td><?= $row['booking_type'] ?></td>
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

<?php include './parts/scripts.php' ?>
<script>
    function delete_one(booking_detail_id){
        if(confirm(`是否要刪除編號為 ${booking_detail_id} 的資料?`)){
            location.href = `booking_detail_delete.php?booking_detail_id=${booking_detail_id}`
        }
    }
</script>
<?php include './parts/html-foot.php' ?>

