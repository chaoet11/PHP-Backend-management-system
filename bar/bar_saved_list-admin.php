<?php 
    require '../parts/db_connect.php';
    $pageName = 'list';
    $title = '列表';


    $perPage = 20;

    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    if($page < 1){
        // redirect
        header('Location: ?page=1');
        exit;
    }

    $t_sql = "SELECT COUNT(1) FROM bar_saved";
    $t_stmt = $pdo->query($t_sql);
    $row = $t_stmt->fetch(PDO::FETCH_NUM);
    // $row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

    // print_r($row); 
    // exit; #直接離開程式
    $totalRows = $row[0]; # 取得總筆數
    $totalPages = 0; # 預設值
    $rows = []; # 預設值

    // 假設默認排序是按 bar_saved_id 升序
    $sortColumn = $_GET['sort'] ?? 'bar_saved_id';
    $order = $_GET['order'] ?? 'ASC';

    // 確保只允許特定的列名和排序方向
    $allowedSortColumns = ['bar_saved_id', 'user_id', 'bar_id'];
    $allowedOrder = ['ASC', 'DESC'];
    $sortColumn = in_array($sortColumn, $allowedSortColumns) ? $sortColumn : 'bar_saved_id';
    $order = in_array(strtoupper($order), $allowedOrder) ? strtoupper($order) : 'ASC';

    if($totalRows > 0) {
        $totalPages = ceil($totalRows / $perPage);

        if($page > $totalPages) {
            // redirect
            header('Location: ?page='. $totalPages);
            exit;
        }

        // $sql = sprintf("SELECT * FROM bar_saved ORDER BY bar_saved_id DESC
        //             LIMIT %s, %s", ($page-1)*$perPage, $perPage);

        // 修改 SQL 查詢以包含排序參數
        // $sql = sprintf("SELECT * FROM bar_saved ORDER BY %s %s LIMIT %s, %s", $sortColumn, $order, ($page-1)*$perPage, $perPage);
        // 修改 SQL 查詢以包含 JOIN
        $sql = sprintf(
        "SELECT bar_saved.*, member_user.username, bars.bar_name FROM bar_saved
        LEFT JOIN member_user ON bar_saved.user_id = member_user.user_id
        LEFT JOIN bars ON bar_saved.bar_id = bars.bar_id
        ORDER BY %s %s LIMIT %s, %s",
        $sortColumn,
        $order,
        ($page - 1) * $perPage,
        $perPage
    );
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
                <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="bar_saved_list.php" class="text-decoration-none">Bar</a></li>
                <li class="breadcrumb-item active" aria-current="page">Saved</li>
            </ol>
        </nav>
        <!-- add breadcrumb end -->

        <div class="row align-items-center">
            <!-- pagination -->
            <?php include '../parts/pagination.php'?>
            <!-- pagination -->

            <!-- add button start -->
            <div class="col-auto">
                <a href="bar_saved_add.php" class="btn btn-primary mb-3"><i class="fa-solid fa-plus"></i></a>
            </div>
            <!-- add end start -->
            <!-- Export CSV Button -->
            <div class="col-auto">
                <button type="button" class="btn btn-primary mb-3" onclick="exportCsv()"><i class="bi bi-file-earmark-arrow-down-fill"></i></button>
            </div>
            <!-- Export CSV Button -->
        </div>

        <div class="table-responsive">
        <table class="table table-bordered table-striped table-primary">
            <thead>
                <tr>
                    <th><i class="fa-solid fa-trash-can"></i></th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>bar_saved_id</span>
                        <a href="?sort=post_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a>
                        </div>
                    </th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>username</span>
                        <a href="?sort=post_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a>
                        </div>
                    </th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>bar_name</span>
                        <a href="?sort=post_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a>
                        </div>
                    </th>
                    <th><i class="fa-solid fa-file-pen"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($rows as $row): ?>
                <tr>
                    <td>
                        <a href="javascript: delete_one(<?= $row['bar_saved_id'] ?>)">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </td>
                    <td><?= $row['bar_saved_id'] ?></td>
                    <!-- <td><?= $row['user_id'] ?></td> -->
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <!-- <td><?= $row['bar_id'] ?></td> -->
                    <td><?= htmlspecialchars($row['bar_name']) ?></td>
                    <!-- strip_tags -->
                    <!-- 避免 XSS 攻擊問題 -->
                    <td>
                        <a href="bar_saved_edit.php?bar_saved_id=<?= $row['bar_saved_id'] ?>">
                            <i class="fa-solid fa-file-pen"></i>
                        </a>
                    </td>
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
    function delete_one(bar_saved_id){
        if(confirm(`Do you want to delete the data with the ID ${bar_saved_id} ?`)){
            location.href = `bar_saved_delete.php?bar_saved_id=${bar_saved_id}`
        }
    }

//export CSV
function exportCsv() {
        window.location.href = 'bar_saved_export-csv-api.php';
    }
</script>
<?php include '../parts/html-foot.php' ?>