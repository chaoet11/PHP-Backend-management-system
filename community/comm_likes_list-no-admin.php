<?php
include '../parts/db_connect.php';
$pageName = 'list';
$title = 'List';

$perPage = 20;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    // redirect
    header('Location: ?page=1');
    exit;
}

$t_sql = "SELECT COUNT(1) FROM comm_likes";
$t_stmt = $pdo->query($t_sql);
$row = $t_stmt->fetch(PDO::FETCH_NUM);
// $row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

// print_r($row); 
// exit; #直接離開程式
$totalRows = $row[0]; # 取得總筆數
$totalPages = 0; # 預設值
$rows = []; # 預設值

// 假設默認排序是按 comm_likes_id 升序
$sortColumn = $_GET['sort'] ?? 'comm_likes_id';
$order = $_GET['order'] ?? 'ASC';

// 確保只允許特定的列名和排序方向
$allowedSortColumns = ['comm_likes_id', 'post_id', 'user_id'];
$allowedOrder = ['ASC', 'DESC'];
$sortColumn = in_array($sortColumn, $allowedSortColumns) ? $sortColumn : 'comm_likes_id';
$order = in_array(strtoupper($order), $allowedOrder) ? strtoupper($order) : 'ASC';

if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);

    if ($page > $totalPages) {
        // redirect
        header('Location: ?page=' . $totalPages);
        exit;
    }

    $sql = sprintf("SELECT * FROM comm_likes ORDER BY %s %s LIMIT %s, %s", $sortColumn, $order, ($page - 1) * $perPage, $perPage);
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
        <?php include '../parts/sidebar.php' ?>
        <!-- sidebar -->

        <div class="col-12 col-md-8 col-lg-10">
            <h5>Community</h5>
            <!-- add breadcrumb start -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="comm_likes_list.php" class="text-decoration-none">Community</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Likes</li>
                </ol>
            </nav>
            <!-- add breadcrumb end -->

            <div class="row align-items-center">
                <!-- pagination -->

                <?php include '../parts/pagination.php' ?>

                <!-- pagination -->

                <!-- Search bar -->
                <div class="col-auto me-3">
                    <div class="input-group">
                        <input type="text" id="search-input" class="form-control mb-3" placeholder="Search">
                        <button type="button" class="btn btn-primary mb-3 me-2" onclick="searchData()"><i class="bi bi-search"></i></button>
                        <!-- Return button  -->
                        <div class="col-auto">
                            <a href="comm_likes_list.php" class="btn btn-primary mb-3"><i class="fa-solid fa-rotate-left"></i></a>
                        </div>
                        <!-- Return button  -->
                    </div>
                </div>
                <!-- Search bar -->
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-primary">
                    <thead>
                        <tr>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>comm_likes_id</span>
                                    <a href="?sort=comm_likes_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>post_id</span>
                                    <a href="?sort=post_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>user_id</span>
                                    <a href="?sort=user_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row) : ?>
                            <tr>
                                <td><?= $row['comm_likes_id'] ?></td>
                                <td><?= $row['post_id'] ?></td>
                                <td><?= $row['user_id'] ?></td>
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
    function delete_one(comm_likes_id) {
        if (confirm(`Do you want to delete the data with the ID ${comm_likes_id} ?`)) {
            location.href = `comm_likes_delete.php?comm_likes_id=${comm_likes_id}`
        }
    }

    function searchData() {
        const keyword = document.getElementById('search-input').value;

        fetch(`comm_likes_search-api.php?keyword=${keyword}`)
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
                <td>${row.comm_likes_id}</td>
                <td>${row.post_id}</td>
                <td>${row.user_id}</td>
                <!-- 其他欄位 -->
            `;
            tableBody.appendChild(tr);
        });
    }
</script>
<?php include '../parts/html-foot.php' ?>