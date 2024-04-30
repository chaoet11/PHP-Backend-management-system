<?php

require '../parts/db_connect.php';
$pageName = 'list';
$title = 'List';

$perPage = 20;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

// 獲取總用戶數
$t_sql = "SELECT COUNT(DISTINCT f.user_id1) FROM friendships AS f";
$t_stmt = $pdo->query($t_sql);
$totalRows = $t_stmt->fetchColumn(); // 获取总行数

$totalPages = ceil($totalRows / $perPage);

// 如果請求頁碼大於總頁數, 重新導向到最後一頁
if ($page > $totalPages && $totalPages > 0) {
    header('Location: ?page=' . $totalPages);
    exit;
}

// 默認排序是user_id1 ASC
$sortColumn = $_GET['sort'] ?? 'user_id1';
$order = $_GET['order'] ?? 'ASC';

// 確保只允許特定的列排序
$allowedSortColumns = ['username', 'user_id1', 'accepted_count', 'blocked_count', 'pending_count'];
$allowedOrder = ['ASC', 'DESC'];
$sortColumn = in_array($sortColumn, $allowedSortColumns) ? $sortColumn : 'user_id1';
$order = in_array(strtoupper($order), $allowedOrder) ? strtoupper($order) : 'ASC';

$offset = ($page - 1) * $perPage;

// 更新分頁後查詢
$sql = sprintf(
    "SELECT 
        mu.username, 
        f.user_id1,
        COUNT(CASE WHEN f.friendship_status = 'accepted' THEN 1 ELSE NULL END) AS accepted_count,
        COUNT(CASE WHEN f.friendship_status = 'blocked' THEN 1 ELSE NULL END) AS blocked_count,
        COUNT(CASE WHEN f.friendship_status = 'pending' THEN 1 ELSE NULL END) AS pending_count
    FROM member_user AS mu 
    LEFT JOIN friendships AS f ON mu.user_id = f.user_id1
    GROUP BY f.user_id1
    ORDER BY %s %s LIMIT %d, %d",
    $sortColumn,
    $order,
    $offset,
    $perPage
);



$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll();

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
            <h5>Blind Date</h5>
            <!-- add breadcrumb start -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="date_statistic_list.php" class="text-decoration-none">Blind Date</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Statistic</li>
                </ol>
            </nav>
            <!-- add breadcrumb end -->

            <div class="row align-items-center">
                <!-- pagination -->
                <?php include '../parts/pagination.php' ?>
                <!-- pagination -->

                <div class="col-auto d-flex align-items-center">
                    <!-- Search bar -->
                    <div class="col-auto me-4">
                        <div class="input-group">
                            <input type="text" id="search-input" class="form-control mb-3" placeholder="Search" onkeypress="handleKeyPress(event)">
                            <button type="button" class="btn btn-primary mb-3 me-2" onclick="searchData()"><i class="bi bi-search"></i></button>
                            <!-- Return button  -->
                            <div class="col-auto">
                                <a href="date_statistic_list.php" class="btn btn-primary mb-3"><i class="fa-solid fa-rotate-left"></i></a>
                            </div>
                            <!-- Return button  -->
                        </div>
                    </div>
                    <!-- Search bar -->

                    
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-primary">
                    <thead>
                        <tr>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>username</span>
                                    <a href="?sort=username&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>accepted_count</span>
                                    <a href="?sort=accepted_count&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>blocked_count</span>
                                    <a href="?sort=blocked_count&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>pending_count</span>
                                    <a href="?sort=pending_count&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row) : ?>
                            <tr>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td><?= htmlspecialchars($row['accepted_count']) ?></td>
                                <td><?= htmlspecialchars($row['blocked_count']) ?></td>
                                <td><?= htmlspecialchars($row['pending_count']) ?></td>
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
    function searchData() {
        const keyword = document.getElementById('search-input').value;

        fetch(`date_statistic_search-api.php?keyword=${keyword}`)
            .then(r => r.json())
            .then(result => {
                updateTable(result.data);
            });
    }

    function handleKeyPress(event) {
        if (event.keyCode === 13) { // 13 是 Enter 鍵的 keyCode
            searchData(); // 呼叫 searchData 函數進行搜尋
            event.preventDefault(); // 防止表單提交的預設行為
        }
    }

    function updateTable(data, keyword) {
        const tableBody = document.querySelector('table tbody');
        tableBody.innerHTML = '';

        data.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${highlightKeyword(row.username, keyword)}</td>
                <td>${highlightKeyword(row.accepted_count, keyword)}</td>
                <td>${highlightKeyword(row.blocked_count, keyword)}</td>
                <td>${highlightKeyword(row.pending_count, keyword)}</td>
            `;
            tableBody.appendChild(tr);
        });
    }

    function highlightKeyword(text, keyword) {
        if (typeof text !== 'string' || !keyword || keyword.trim() === '') {
            return text;
        } 

        const regex = new RegExp(keyword, 'gi'); // 'gi' 表示全局且不區分大小寫
        const highlightedText = text.replace(regex, match => `<span style="background-color: yellow">${match}</span>`);
        
        return highlightedText;
    }
</script>
<?php include '../parts/html-foot.php' ?>