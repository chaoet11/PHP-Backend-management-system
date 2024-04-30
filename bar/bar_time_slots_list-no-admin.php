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

    $t_sql = "SELECT COUNT(1) FROM bar_time_slots";
    $t_stmt = $pdo->query($t_sql);
    $row = $t_stmt->fetch(PDO::FETCH_NUM);
    // $row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

    // print_r($row); 
    // exit; #直接離開程式
    $totalRows = $row[0]; # 取得總筆數
    $totalPages = 0; # 預設值
    $rows = []; # 預設值

    // 假設默認排序是按 bar_time_slot_id 升序
    $sortColumn = $_GET['sort'] ?? 'bar_time_slot_id';
    $order = $_GET['order'] ?? 'ASC';

    // 確保只允許特定的列名和排序方向
    $allowedSortColumns = ['bar_time_slot_id', 'bar_start_time', 'bar_end_time', 'bar_max_capacity'];
    $allowedOrder = ['ASC', 'DESC'];
    $sortColumn = in_array($sortColumn, $allowedSortColumns) ? $sortColumn : 'bar_time_slot_id';
    $order = in_array(strtoupper($order), $allowedOrder) ? strtoupper($order) : 'ASC';

    if($totalRows > 0) {
        $totalPages = ceil($totalRows / $perPage);

        if($page > $totalPages) {
            // redirect
            header('Location: ?page='. $totalPages);
            exit;
        }

        // $sql = sprintf("SELECT * FROM bar_time_slots ORDER BY bar_time_slot_id DESC
        //             LIMIT %s, %s", ($page-1)*$perPage, $perPage);

        // 修改 SQL 查詢以包含排序參數
        $sql = sprintf("SELECT * FROM bar_time_slots ORDER BY %s %s LIMIT %s, %s", $sortColumn, $order, ($page-1)*$perPage, $perPage);
        
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
        <h5>Bar</h5>
        <!-- add breadcrumb start -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="bar_times_slots_list.php" class="text-decoration-none">Bar</a></li>
                <li class="breadcrumb-item active" aria-current="page">Time Slots</li>
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
                        <span>bar_time_slot_id</span>
                        <a href="?sort=bar_time_slot_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a>
                        </div>
                    </th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>bar_start_time</span>
                        <a href="?sort=bar_start_time&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a>
                        </div>
                    </th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>bar_end_time</span>
                        <a href="?sort=bar_end_time&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a>
                        </div>
                    </th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>bar_max_capacity</span>
                        <a href="?sort=bar_max_capacity&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a>
                        </div>
                    </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rows as $row): ?>
                    <tr>
                    <td><?= $row['bar_time_slot_id'] ?></td>
                    <td><?= $row['bar_start_time'] ?></td>
                    <td><?= $row['bar_end_time'] ?></td>
                    <td><?= $row['bar_max_capacity'] ?></td>
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
    // function delete_one(bar_time_slot_id){
    //     if(confirm(`Do you want to delete the data with the ID ${bar_time_slot_id} ?`)){
    //         location.href = `bar_time_slots_delete.php?bar_time_slot_id=${bar_time_slot_id}`
    //     }
    // }
</script>
<?php include '../parts/html-foot.php' ?>