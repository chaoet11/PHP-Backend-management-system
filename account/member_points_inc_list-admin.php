<?php
require  '../parts/db_connect.php';
$pageName = 'list';
$title = 'List';

$perPage = 20;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

if ($page < 1) {
    // redirect
    header('Location: ?page=1');
    exit;
}

$t_sql = "SELECT COUNT(1) FROM member_points_inc";
$t_stmt = $pdo->query($t_sql);
$row = $t_stmt->fetch(PDO::FETCH_NUM);
// $row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

// 假設默認排序是按 user_id 升序
$sortColumn = $_GET['sort'] ?? 'points_increase_id ';
$order = $_GET['order'] ?? 'ASC';
// 確保只允許特定的列名和排序方向
$allowedSortColumns = ['points_increase_id ', 'user_id ', 'points_increase', 'reason', 'created_at'];
$allowedOrder = ['ASC', 'DESC'];
$sortColumn = in_array($sortColumn, $allowedSortColumns) ? $sortColumn : 'points_increase_id';
$order = in_array(strtoupper($order), $allowedOrder) ? strtoupper($order) : 'ASC';

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

    $sql = sprintf("SELECT mpi.*, u.username 
    FROM member_points_inc AS mpi
    JOIN member_user AS u 
    ON mpi.user_id = u.user_id
    ORDER BY %s %s LIMIT %s, %s", $sortColumn, $order, ($page - 1) * $perPage, $perPage);
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
            <h5>Account Center</h5>
            <!-- add breadcrumb start -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="member_points_list.php" class="text-decoration-none">Account Center</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Member Point Inc</li>
                </ol>
            </nav>
            <!-- add breadcrumb end -->


            <div class="d-flex align-items-center" >
                <!-- pagination -->
                <?php include '../parts/pagination.php' ?>
                <!-- pagination -->

                <!-- Dropdown for category selection -->
                <div class="col-auto dropdown me-3 mb-3">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        All Categories
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('')">All Categories</a></li>
                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('登入簽到')">登入簽到</a></li>
                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('遊玩遊戲')">遊玩遊戲</a></li>
                    </ul>
                </div>
                <!-- Dropdown for category selection -->

                <!-- Search bar -->
                <div class="col-auto me-3">
                    <div class="input-group">
                        <input type="text" id="search-input" class="form-control mb-3" placeholder="Search">
                        <button type="button" class="btn btn-primary mb-3 me-1" onclick="searchData()"> <i class="bi bi-search"></i></button>
                        <!-- Return button  -->
                        <div class="col-auto">
                            <a href="member_points_inc_list.php" class="btn btn-primary mb-3"><i class="fa-solid fa-rotate-left"></i></a>
                        </div>
                        <!-- Return button  -->
                    </div>
                </div>
                <!-- Search bar -->

                <!-- add button start -->
                <div class="col-auto relative-absolute end-100">
                    <a href="member_points_inc_add.php" class="btn btn-primary mb-3"><i class="fa-solid fa-plus"></i></a>
                </div>
                <!-- add end start -->
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-primary me-5 ">
                    <thead>
                        <tr>
                            <th class="text-center"><i class="fa-solid fa-trash-can"></i></th>
                            <th class="text-center">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>points_increase_id</span>
                                    <a href="?sort=points_increase_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th class="text-center">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>username</span>
                                    <a href="?sort=user_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th class="text-center">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>points_increase</span>
                                    <a href="?sort=points_increase&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th class="text-center">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>reason</span>
                                    <a href="?sort=reason&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th class="text-center">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>created_at</span>
                                    <a href="?sort=created_at&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th class="text-center"><i class="fa-solid fa-file-pen"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row) : ?>
                            <tr class="align-middle">
                                <td class="text-center">
                                    <a href="javascript: delete_one(<?= $row['points_increase_id'] ?>)">
                                        <i class="fa-solid fa-trash-can "></i>
                                    </a>
                                </td>
                                <td><?= $row['points_increase_id'] ?></td>
                                <td><?= $row['username'] ?></td>
                                <td><?= $row['points_increase'] ?></td>
                                <td><?= $row['reason'] ?></td>
                                <td><?= $row['created_at'] ?></td>
                                <!-- strip_tags -->
                                <!-- 避免 XSS 攻擊問題 -->
                                <td class="text-center">
                                    <a href="member_points_inc_edit.php?points_increase_id=<?= $row['points_increase_id'] ?>">
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
    function searchData() {
        const keyword = document.getElementById('search-input').value;

        // 檢查關鍵字是否為空
        if (!keyword) {
            return;
        }

        fetch(`member_points_inc_search-api.php?keyword=${keyword}`)
            .then(r => r.json())
            .then(result => {
                updateTable(result.data, keyword);
            });
    }

    function updateTable(data, keyword) {
        const tableBody = document.querySelector('table tbody');
        tableBody.innerHTML = '';

        data.forEach(row => {
            const tr = document.createElement('tr');
            const formattedDate = new Date(row.created_at).toLocaleString();

            // 將 <tr> 元素附加到 tableBody 中
            tableBody.appendChild(tr);

            // 設置 <tr> 的 innerHTML
            tr.innerHTML = `
                <td class="text-center">
                    <a href="javascript: delete_one(<?= $row['points_increase_id'] ?>)">
                        <i class="fa-solid fa-trash-can "></i>
                    </a>
                </td>
                <td class="align-middle">${highlightKeyword(row.points_increase_id.toString(), keyword)}</td>
                <td class="align-middle">${highlightKeyword(row.username, keyword)}</td>
                <td class="align-middle">${highlightKeyword(row.points_increase.toString(), keyword)}</td>
                <td class="align-middle">${highlightKeyword(row.reason, keyword)}</td>
                <td class="align-middle">${highlightKeyword(formattedDate.toString(), keyword)}</td>
                <td class="text-center">
                    <a href="member_points_inc_edit.php?points_increase_id=<?= $row['points_increase_id'] ?>">
                        <i class="fa-solid fa-file-pen"></i>
                    </a>
                </td>
            `;
        });
    }

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

    function filterCategory(category) {
        fetch(`member_points_inc_filter-api.php?category=${category}`)
            .then(r => r.json())
            .then(result => {
                updateTable(result.data);
                updateDropdownButtonText(category);
            });
    }

    function updateDropdownButtonText(category) {
        let buttonText = 'All Categories'; // Default button text
        if (category) {
            // Check the category and update the button text to English
            switch (category) {
                case '登入簽到':
                    buttonText = '登入簽到';
                    break;
                case '遊玩遊戲':
                    buttonText = '遊玩遊戲';
                    break;
                default:
                    buttonText = category;
            }
        }
        document.getElementById('categoryDropdown').innerText = buttonText;
    }

    function resetDropdown() {
        currentSearchCategory = '';
        document.getElementById('categoryDropdown').innerText = 'All Categories';
    }

    function delete_one(points_increase_id) {
        if (confirm(`Do you want to delete the data with the ${points_increase_id} ?`)) {
            location.href = `member_points_inc_delete.php?points_increase_id=${points_increase_id}`
        }
    }
</script>
<?php include '../parts/html-foot.php' ?>