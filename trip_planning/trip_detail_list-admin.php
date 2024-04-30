<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"> -->

<?php

require '../parts/db_connect.php';
$pageName = 'list';
$title = '列表';


$perPage = 20;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    // redirect
    header('Location: ?page=1');
    exit;
}

$t_sql = "SELECT COUNT(1) FROM trip_details";
$t_stmt = $pdo->query($t_sql);
$row = $t_stmt->fetch(PDO::FETCH_NUM);
// $row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

// print_r($row); 
// exit; #直接離開程式
$totalRows = $row[0]; # 取得總筆數
$totalPages = 0; # 預設值
$rows = []; # 預設值

// 假設默認排序是按 trip_detail_id 升序
$sortColumn = $_GET['sort'] ?? 'trip_detail_id';
$order = $_GET['order'] ?? 'ASC';

// 確保只允許特定的列名和排序方向
$allowedSortColumns = ['trip_detail_id', 'trip_plan_id', 'block', 'movie_id', 'bar_id'];
$allowedOrder = ['ASC', 'DESC'];
$sortColumn = in_array($sortColumn, $allowedSortColumns) ? $sortColumn : 'trip_detail_id';
$order = in_array(strtoupper($order), $allowedOrder) ? strtoupper($order) : 'ASC';

if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);

    if ($page > $totalPages) {
        // redirect
        header('Location: ?page=' . $totalPages);
        exit;
    }

    $sql = sprintf("SELECT * FROM trip_details ORDER BY %s %s LIMIT %s, %s", $sortColumn, $order, ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
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
        <?php include  '../parts/sidebar.php' ?>
        <!-- sidebar -->

        <div class="col-12 col-md-8 col-lg-10">
            <h5>Trip Planning</h5>
            <!-- add breadcrumb start -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="trip_detail_list.php" class="text-decoration-none">Trip Planning</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
            <!-- add breadcrumb end -->

            <div class="row align-items-center">
                <!-- pagination -->
                <?php include  '../parts/pagination.php' ?>
                <!-- pagination -->

                <!-- Search bar -->
                <div class="col-auto me-3">
                    <div class="input-group">
                        <input type="text" id="search-input" class="form-control mb-3" placeholder="Search">
                        <button type="button" class="btn btn-primary mb-3 me-2" onclick="searchData()"><i class="bi bi-search"></i></button>
                        <!-- Return button  -->
                        <div class="col-auto">
                            <a href="trip_detail_list.php" class="btn btn-primary mb-3"><i class="fa-solid fa-rotate-left"></i></a>
                        </div>
                        <!-- Return button  -->
                    </div>
                </div>
                <!-- Search bar -->

                <!-- add button start -->
                <div class="col-auto">
                    <a href="trip_detail_add.php" class="btn btn-primary mb-3"><i class="fa-solid fa-plus"></i></a>
                </div>
                <!-- add end start -->
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-primary">
                    <thead>
                        <tr>
                            <th><i class="fa-solid fa-trash-can"></i></th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>trip_detail_id</span>
                                    <a href="?sort=trip_detail_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>trip_plan_id</span>
                                    <a href="?sort=trip_plan_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>block</span>
                                    <a href="?sort=block&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>movie_id</span>
                                    <a href="?sort=movie_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>bar_id</span>
                                    <a href="?sort=bar_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th><i class="fa-solid fa-file-pen"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row) : ?>
                            <tr>
                                <td>
                                    <a href="javascript: delete_one(<?= $row['trip_detail_id'] ?>)">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </td>
                                <td><?= $row['trip_detail_id'] ?></td>
                                <td><?= $row['trip_plan_id'] ?></td>
                                <td><?= $row['block'] ?></td>
                                <td><?= $row['movie_id'] ?></td>
                                <td><?= $row['bar_id'] ?></td>
                                <!-- strip_tags -->
                                <!-- 避免 XSS 攻擊問題 -->
                                <td>
                                    <a href="trip_detail_edit.php?trip_detail_id=<?= $row['trip_detail_id'] ?>">
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
    function delete_one(trip_detail_id) {
        if (confirm(`Do you want to delete the data with the ID ${trip_detail_id} ?`)) {
            location.href = `trip_detail_delete.php?trip_detail_id=${trip_detail_id}`
        }
    }

    function searchData() {
        const keyword = document.getElementById('search-input').value;

        fetch(`trip_detail_search-api.php?keyword=${keyword}`)
            .then(r => r.json())
            .then(result => {
                updateTable(result.data);
            });
    }

    function updateTable(data) {
        const tableBody = document.querySelector('table tbody');
        tableBody.innerHTML = '';

        data.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <a href="javascript: delete_one(${row.trip_detail_id})">
                        <i class="fa-solid fa-trash-can"></i>
                    </a>
                </td>
                <td>${row.trip_detail_id}</td>
                <td>${row.trip_plan_id}</td>
                <td>${row.block}</td>
                <td>${row.movie_id}</td>
                <td>${row.bar_id}</td>
                <td>
                    <a href="trip_trip_detail_edit.php?trip_detail_id=${row.trip_detail_id}">
                        <i class="fa-solid fa-file-pen"></i>
                    </a>
                </td>
            `;
            tableBody.appendChild(tr);
        });
    }
</script>
<?php include '../parts/html-foot.php' ?>