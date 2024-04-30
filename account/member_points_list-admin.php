
<?php

require '../parts/db_connect.php';
$pageName = 'list';
$title = 'List';


$perPage = 20;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    // redirect
    header('Location: ?page=1');
    exit;
}

$t_sql = "SELECT 
user_id, 
SUM(points_increase) AS total_points_increase, 
SUM(points_decrease) AS total_points_decrease, 
SUM(points_increase) - SUM(points_decrease) AS total_points

FROM ( SELECT user_id, 
points_increase, 
0 AS points_decrease

FROM member_points_inc  UNION ALL SELECT user_id, 
0 AS points_increase, 
points_decrease

FROM booking_points_dec ) AS combined_points GROUP BY user_id";
$t_stmt = $pdo->query($t_sql);
$row = $t_stmt->fetch(PDO::FETCH_NUM);
// $row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

// 假設默認排序是按 user_id 升序
$sortColumn = $_GET['sort'] ?? 'user_id';
$order = $_GET['order'] ?? 'ASC';
// 確保只允許特定的列名和排序方向
$allowedSortColumns = ['user_id', 'total_points_increase', 'total_points_decrease', 'total_points'];
$allowedOrder = ['ASC', 'DESC'];
$sortColumn = in_array($sortColumn, $allowedSortColumns) ? $sortColumn : 'user_id';
$order = in_array(strtoupper($order), $allowedOrder) ? strtoupper($order) : 'ASC';

// print_r($row); 
// exit; #直接離開程式
$totalRows = $row[0]; # 取得總筆數
$totalPages = 0; # 預設值
$rows = []; # 預設值

if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);

    if ($page > $totalPages) {
        // redirect
        header('Location: ?page=' . $totalPages);
        exit;
    }

    $sql = "SELECT cp.user_id, 
    SUM(points_increase) AS total_points_increase, 
    SUM(points_decrease) AS total_points_decrease, 
    SUM(points_increase) - SUM(points_decrease) AS total_points,
    u.username
    FROM (
        SELECT user_id, points_increase, 0 AS points_decrease
        FROM member_points_inc
        UNION ALL
        SELECT user_id, 0 AS points_increase, points_decrease AS points_decrease
        FROM booking_points_dec
    ) AS cp
    JOIN member_user AS u ON cp.user_id = u.user_id
    GROUP BY cp.user_id
    ORDER BY $sortColumn $order
    LIMIT :offset, :perPage";
    // $stmt = $pdo->query($sql);
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':offset', ($page - 1) * $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll();
}
?>

<?php include '../parts/html-head.php' ?>

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
            <h5>Account Center</h5>
            <!-- add breadcrumb start -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="member_points_list.php" class="text-decoration-none">Account Center</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Member Point</li>
                </ol>
            </nav>
            <!-- add breadcrumb end -->


            <div class="d-flex position-relative" style="overflow:auto;">
                <!-- pagination -->
                <?php include '../parts/pagination.php' ?>
                <!-- pagination -->
                <!-- add button start -->
                <!-- <div class="col-auto relative-absolute end-100">
                    <a href="member_user_add.php" class="btn btn-primary mb-3">新增</a>
                </div> -->
                <!-- add end start -->
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-primary me-5 ">
                    <thead>
                        <tr>
                            <th><i class="fa-solid fa-trash-can"></i></th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>username</span>
                                    <a href="?sort=user_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>total_points_increase</span>
                                    <a href="?sort=total_points_increase&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>total_points_decrease</span>
                                    <a href="?sort=total_points_decrease&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>total_points</span>
                                    <a href="?sort=total_points&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <!-- <th><i class="fa-solid fa-file-pen"></i></th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row) : ?>
                            <tr class="align-middle">
                                <td>
                                    <a href="javascript: delete_one(<?= $row['user_id'] ?>)">
                                        <i class="fa-solid fa-trash-can "></i>
                                    </a>
                                </td>
                                <td><?= $row['username'] ?></td>
                                <td><?= $row['total_points_increase'] ?></td>
                                <td><?= $row['total_points_decrease'] ?></td>
                                <td><?= $row['total_points'] ?></td>
                                <!-- strip_tags -->
                                <!-- 避免 XSS 攻擊問題 -->
                                <!-- <td>
                                    <a href="member_user_edit.php?user_id=<?= $row['user_id'] ?>">
                                        <i class="fa-solid fa-file-pen"></i>
                                    </a>
                                </td> -->
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
    function delete_one(user_id) {
        if (confirm(`Do you want to delete the data with the ${user_id} ?`)) {
            location.href = `member_points_delete.php?user_id=${user_id}`
        }
    }
</script>
<?php include '../parts/html-foot.php' ?>