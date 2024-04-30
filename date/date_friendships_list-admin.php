

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

$sortColumn = $_GET['sort'] ?? 'friendship_id';
$order = $_GET['order'] ?? 'ASC';

$allowedSortColumns = ['friendship_id', 'user_id1', 'user_id2', 'friendship_status', 'created_at', 'updated_at'];
$allowedOrder = ['ASC', 'DESC'];

$sortColumn = in_array($sortColumn, $allowedSortColumns) ? $sortColumn : 'friendship_id';
$order = in_array(strtoupper($order), $allowedOrder) ? strtoupper($order) : 'ASC';

$t_sql = "SELECT COUNT(1) FROM friendships";
$t_stmt = $pdo->query($t_sql);
$row = $t_stmt->fetch(PDO::FETCH_NUM);
$totalRows = $row[0];

$totalPages = ceil($totalRows / $perPage);

if ($page > $totalPages) {
    // redirect
    header('Location: ?page=' . $totalPages);
    exit;
}

$offset = ($page - 1) * $perPage;

$sql = sprintf(
    "SELECT
        f.friendship_id,
        m1.username AS user_id1,
        m2.username AS user_id2,
        f.friendship_status,
        f.created_at,
        f.updated_at
    FROM
        friendships f
    LEFT JOIN
        member_user m1 ON f.user_id1 = m1.user_id
    LEFT JOIN
        member_user m2 ON f.user_id2 = m2.user_id
    ORDER BY %s %s LIMIT %s, %s",
    $sortColumn,
    $order,
    $offset,
    $perPage
);

$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll();
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
        <h5>Blind Date</h5>
        <!-- add breadcrumb start -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="date_friendships_list.php" class="text-decoration-none">Blind Date</a></li>
                <li class="breadcrumb-item active" aria-current="page">Friendships</li>
            </ol>
        </nav>
        <!-- add breadcrumb end -->


        <div class="row align-items-center">
            <!-- pagination -->
            <?php include '../parts/pagination.php'?>
            <!-- pagination -->

            <!-- Dropdown search (friendship status) -->
            <div class="col-auto dropdown mb-3">
                <button class="btn btn-primary dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    All Friendship Status
                </button>
                <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('')">All Friendship Status</a></li>
                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('accepted')">accepted</a></li>
                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('blocked')">blocked</a></li>
                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('pending')">pending</a></li>
                </ul>
            </div>
            <!-- Dropdown search (friendship status) -->

            <!-- Search bar start-->
            <div class="col-auto me-3">
                <div class="input-group">
                    <input type="text" id="search-input" class="form-control mb-3" placeholder="Search" onkeypress="handleKeyPress(event)">
                    <button type="button" class="btn btn-primary mb-3 me-2" onclick="searchData()"><i class="bi bi-search"></i></button>
                    <!-- Return button  -->
                    <div class="col-auto">
                        <a href="date_friendships_list.php" class="btn btn-primary mb-3"><i class="fa-solid fa-rotate-left"></i></a>
                    </div>
                    <!-- Return button  -->
                </div>
            </div>
            <!-- Search bar end-->


            <!-- add button start -->
            <div class="col-auto">
                <a href="date_friendships_add.php" class="btn btn-primary mb-3"><i class="fa-solid fa-plus"></i></a>
            </div>
            <!-- add end start -->

            <!-- Table button start -->
                <div class="col-auto">
                    <a href="date_statistic_list.php" class="btn btn-primary mb-3"><i class="bi bi-table"></i></i></i></a>
                </div>
            <!-- Table end start -->
        </div>

        <div class="table-responsive">        
            <table class="table table-bordered table-striped table-primary">
            <thead>
                <tr>
                    <th><i class="fa-solid fa-trash-can"></i></th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>friendship_id</span>
                        <a href="?sort=friendship_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a>
                        </div>
                    </th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>user_id1</span>
                        <!-- <a href="?sort=user_id1&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i> 
                        </a> -->
                        </div>
                    </th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>user_id2</span>
                        <!-- <a href="?sort=user_id2&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a> -->
                        </div>
                    </th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>friendship_status</span>
                        <a href="?sort=friendship_status&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i> 
                        </a>
                        </div>
                    </th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>created_at</span>
                        <a href="?sort=created_at&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i> 
                        </a>
                        </div>
                    </th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>updated_at</span>
                        <a href="?sort=updated_at&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
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
                        <a href="javascript: delete_one(<?= $row['friendship_id'] ?>)">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </td>
                    <td><?= $row['friendship_id'] ?></td>
                    <td><?= $row['user_id1'] ?></td>
                    <td><?= $row['user_id2'] ?></td>
                    <td><?= $row['friendship_status'] ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td><?= $row['updated_at'] ?></td>
                    <!-- strip_tags -->
                    <!-- 避免 XSS 攻擊問題 -->
                    <td>
                        <a href="date_friendships_edit.php?friendship_id=<?= $row['friendship_id'] ?>">
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

<!-- No data Modal -->
<div class="modal fade" id="noDataModal" tabindex="-1" aria-labelledby="noDataModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="noDataModalLabel">Result</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" role="alert">
                    No data
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- No data Modal -->

<?php include '../parts/scripts.php' ?>
<script>
    function delete_one(friendship_id){
        if(confirm(`Do you want to delete the data with the ID ${friendship_id} ?`)){
            location.href = `date_friendships_delete.php?friendship_id=${friendship_id}`
        }
    }

    //search keyword
    function searchData() {
        const keyword = document.getElementById('search-input').value;
        console.log(keyword);
        fetch(`date_friendships_search-api.php?keyword=${keyword}`)
            .then(r => r.json())
            .then(result => {
                updateTable(result.data, keyword);
            })
            .catch(error => console.error('Error fetching data:', error));
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
        console.log('Result Data:', data);
        // Check if data is empty
            if (data.length === 0) {
                // Show noDataModal
                noDataModal.show();
                return;
            }

        data.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <a href="javascript: delete_one(${row.friendship_id})">
                        <i class="fa-solid fa-trash-can"></i>
                    </a>
                </td>
                <td>${highlightKeyword(row.friendship_id, keyword)}</td>
                <td>${highlightKeyword(row.user_id1, keyword)}</td>
                <td>${highlightKeyword(row.user_id2, keyword)}</td>
                <td>${highlightKeyword(row.friendship_status, keyword)}</td>
                <td>${highlightKeyword(row.created_at, keyword)}</td>
                <td>${highlightKeyword(row.updated_at, keyword)}</td>
                <td>
                    <a href="date_friendships_edit.php?friendship_id=${row.friendship_id}">
                        <i class="fa-solid fa-file-pen"></i>
                    </a>
                </td>
            `;
            tableBody.appendChild(tr);
        });
    }

    //Dropdown search (friendship status)
    let currentSearchCategory = 'All Friendship Status'; // 當前選擇的搜尋類別

    function setSearchCategory(category) {
        currentSearchCategory = category;
        document.getElementById('searchCategoryDropdown').innerText = category;
    }

    function filterCategory(category) {
        fetch(`date_friendships_filter-api.php?category=${category}`)
            .then(r => r.json())
            .then(result => {
                updateTable(result.data);
                updateDropdownButtonText(category);
        });
    }

    function updateDropdownButtonText(category) {
        let buttonText = 'All Friendship Status'; // Default button text
        if (category) {
            // Check the category and update the button text to English
            switch (category) {
                case 'accepted':
                    buttonText = 'accepted';
                    break;
                case 'blocked':
                    buttonText = 'blocked';
                    break;
                case 'pending':
                    buttonText = 'pending';
                    break;
                default:
                    buttonText = category;
            }
        }
        document.getElementById('categoryDropdown').innerText = buttonText;
    }

    //highlight Keyword
    function highlightKeyword(text, keyword) {
        if (typeof text !== 'string' || !keyword || keyword.trim() === '') {
            return text;
        } //typeof text !== 'string' 確保 text 變數的類型是字串。如果 text 不是字串，就表示無法對其使用 replace 方法，因此直接返回原來的值 text。
        //!keyword: 這部分檢查確保 keyword 變數存在並且不是空值。如果 keyword 不存在或為空，表示沒有要進行關鍵字高亮處理，同樣直接返回原來的值 text。
        //keyword.trim() === '': 這部分檢查確保 keyword 去掉前後空白後是否為空字串。如果 keyword 是只包含空白的字串，也被視為空值，不進行關鍵字高亮處理，同樣直接返回原來的值 text。

        const regex = new RegExp(keyword, 'gi'); // 'gi' 表示全局且不區分大小寫
        const highlightedText = text.replace(regex, match => `<span style="background-color: yellow">${match}</span>`);
        return highlightedText;
    }
const noDataModal = new bootstrap.Modal(document.getElementById('noDataModal'));
</script>
<?php include '../parts/html-foot.php' ?>

