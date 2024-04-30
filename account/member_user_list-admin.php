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

$t_sql = "SELECT COUNT(1) FROM member_user";
$t_stmt = $pdo->query($t_sql);
$row = $t_stmt->fetch(PDO::FETCH_NUM);

$totalRows = $row[0]; # 取得總筆數
$totalPages = 0; # 預設值
$rows = []; # 預設值

// 假設默認排序是按 user_id 升序
$sortColumn = $_GET['sort'] ?? 'user_id';
$order = $_GET['order'] ?? 'ASC';
// 確保只允許特定的列名和排序方向
$allowedSortColumns = ['user_id', 'username', 'account', 'email', 'password_hash', 'profile_picture_url', 'gender', 'user_active', 'birthday', 'mobile', 'profile_content', 'created_at', 'updated_at'];
$allowedOrder = ['ASC', 'DESC'];
$sortColumn = in_array($sortColumn, $allowedSortColumns) ? $sortColumn : 'user_id';
$order = in_array(strtoupper($order), $allowedOrder) ? strtoupper($order) : 'ASC';

if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);

    if ($page > $totalPages) {
        // redirect
        header('Location: ?page=' . $totalPages);
        exit;
    }
    $sql = sprintf("SELECT member_user.*,member_gender.gender_type FROM member_user JOIN member_gender ON member_user.gender = member_gender.gender ORDER BY  %s %s
                    LIMIT %s, %s", $sortColumn, $order, ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}
?>

<?php include  '../parts/html-head.php' ?>

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
            <h5>Account Center</h5>
            <!-- add breadcrumb start -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="member_user_list.php" class="text-decoration-none">Account Center</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Member User</li>
                </ol>
            </nav>
            <!-- add breadcrumb end -->


            <div class="d-flex position-relative align-items-center" style="overflow:auto;">
                <!-- pagination -->
                <?php include  '../parts/pagination.php' ?>
                <!-- pagination -->

                <!-- Search bar -->
                <div class="col-auto me-3">
                    <div class="input-group">
                        <input type="text" id="search-input" class="form-control mb-3" placeholder="Search">
                        <button type="button" class="btn btn-primary mb-3 me-1" onclick="searchData()"> <i class="bi bi-search"></i></button>
                        <!-- Return button  -->
                        <div class="col-auto">
                            <a href="member_user_list.php" class="btn btn-primary mb-3"><i class="fa-solid fa-rotate-left"></i></a>
                        </div>
                        <!-- Return button  -->
                    </div>
                </div>
                <!-- Search bar -->

                <!-- add button start -->
                <div class="col-auto relative-absolute end-100">
                    <a href="member_user_add.php" class="btn btn-primary mb-3 me-1"><i class="fa-solid fa-plus"></i></a>
                </div>
                <!-- add end start -->

                <!-- Export CSV Button -->
                <div class="col-auto">
                    <button type="button" class="btn btn-primary mb-3" onclick="exportCsv()"><i class="bi bi-file-earmark-arrow-down-fill"></i></button>
                </div>
                <!-- Export CSV Button -->

            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-primary me-5 ">
                    <thead>
                        <tr>
                            <th><i class="fa-solid fa-trash-can"></i></th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>user_id</span>
                                    <a href="?sort=user_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
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
                                    <span>account</span>
                                    <a href="?sort=account&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>email</span>
                                    <a href="?sort=email&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>email</span>
                                    <a href="?sort=password_hash&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>profile_picture_url</span>
                                    <a href="?sort=profile_picture_url&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between; white-space: nowrap;">
                                    <span>gender</span>
                                    <a href="?sort=gender&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>user_active</span>
                                    <a href="?sort=user_active&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>birthday</span>
                                    <a href="?sort=birthday&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>mobile</span>
                                    <a href="?sort=mobile&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>profile_content</span>
                                    <a href="?sort=profile_content&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>created_at_at</span>
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
                        <?php foreach ($rows as $row) : ?>
                            <tr class="align-middle">
                                <td>
                                    <a href="javascript: delete_one(<?= $row['user_id'] ?>)">
                                        <i class="fa-solid fa-trash-can "></i>
                                    </a>
                                </td>
                                <td><?= $row['user_id'] ?></td>
                                <td><?= $row['username'] ?></td>
                                <td><?= $row['account'] ?></td>
                                <td><?= $row['email'] ?></td>
                                <td>
                                    <span class="all-content">
                                        <?= $row['password_hash'] ?>
                                    </span>
                                </td>
                                <td><?= $row['profile_picture_url'] ?></td>
                                <td><?= $row['gender_type'] ?></td>
                                <td class="text-center"><?= ($row['user_active'] == 1) ? '<i class="fa-solid fa-user-check bg-success text-light rounded-circle p-1" style="width=30px;height:30px;line-height:20px;"></i>' : '<i class="fa-solid fa-user-xmark bg-danger text-light rounded-circle" style="line-height:20px;padding: 4px 4px;"></i>'; ?></td>
                                <td><?= $row['birthday'] ?></td>
                                <td><?= $row['mobile'] ?></td>
                                <td><?= $row['profile_content'] ?></td>
                                <td><?= $row['created_at'] ?></td>
                                <td><?= $row['updated_at'] ?></td>
                                <!-- strip_tags -->
                                <!-- 避免 XSS 攻擊問題 -->
                                <td>
                                    <a href="member_user_edit.php?user_id=<?= $row['user_id'] ?>">
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
    function delete_one(user_id) {
        if (confirm(`Do you want to delete the data with the ${user_id} ?`)) {
            location.href = `member_user_delete.php?user_id=${user_id}`
        }
    }

    function searchData() {
        const keyword = document.getElementById('search-input').value;

        // 檢查關鍵字是否為空
        if (!keyword) {
            return;
        }

        fetch(`member_user_list_search-api.php?keyword=${keyword}`)
            .then(r => r.json())
            .then(result => {
                // 更新表格內容
                updateTable(result.data, keyword);
            });
    }

    function updateTable(data, keyword) {
        const tableBody = document.querySelector('table tbody');
        tableBody.innerHTML = '';

        data.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="align-middle">
                    <a href="javascript: delete_one(${row.user_id})">
                        <i class="fa-solid fa-trash-can"></i>
                    </a>
                </td>
                <td class="align-middle">${highlightKeyword(row.user_id, keyword)}</td>
                <td class="align-middle">${highlightKeyword(row.username, keyword)}</td>
                <td class="align-middle">${highlightKeyword(row.account, keyword)}</td>
                <td class="align-middle">${highlightKeyword(row.email, keyword)}</td>
                <td class="align-middle">
                    <span class="all-content">
                        ${row.password_hash}
                    </span>
                </td>
                <td class="align-middle">${highlightKeyword(row.profile_picture_url, keyword)}</td>
                <td class="align-middle">${highlightKeyword(row.gender_type, keyword)}</td>
                <td class="text-center">${(row.user_active == 1) ? '<i class="fa-solid fa-user-check bg-success text-light rounded-circle p-1" style="width=30px;height:30px;line-height:20px;"></i>' : '<i class="fa-solid fa-user-xmark bg-danger text-light rounded-circle" style="line-height:20px;padding: 4px 4px;"></i>'}</td>
                <td class="align-middle">${highlightKeyword(row.birthday, keyword)}</td>
                <td class="align-middle">${highlightKeyword(row.mobile, keyword)}</td>
                <td class="align-middle">${highlightKeyword(row.profile_content, keyword)}</td>
                <td class="align-middle">${highlightKeyword(row.created_at, keyword)}</td>
                <td class="align-middle">${highlightKeyword(row.updated_at, keyword)}</td>
                <td class="align-middle">
                    <a href="member_user_edit.php?user_id=${row.user_id}">
                        <i class="fa-solid fa-file-pen"></i>
                    </a>
                </td>
            `;
            tableBody.appendChild(tr);
        });
        // 重新處理read more功能
        handleReadMore();
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

    // 處理可展開的內容
    function handleContent(content) {
        let originalContent = content.innerHTML;
        let maxLength = 1;
        // 檢查文字數量
        if (originalContent.length > maxLength) {
            let lessContent = originalContent.substring(0, maxLength - 1) + '<span class="ellipsis" style="color: #003e52; font-weight: bold; font-size: 12px"> Expand </span>';
            content.innerHTML = lessContent;

            function createExpand(originalContent) {
                return function expandContent() {
                    this.innerHTML = originalContent;
                    this.removeEventListener('click', expandContent);
                };
            }

            // 點擊read more...
            content.addEventListener('click', createExpand(originalContent));
        }
    }

    // 處理read more功能
    function handleReadMore() {
        let allContents = document.querySelectorAll('.all-content');
        allContents.forEach(handleContent);
    }

    // 初始載入時處理read more功能
    document.addEventListener('DOMContentLoaded', function() {
        handleReadMore();
    });

    // 處理read more功能
    function handleReadMore() {
        let allContents = document.querySelectorAll('.all-content');
        allContents.forEach(handleContent);
    }

    function exportCsv() {
        window.location.href = 'member_user_list_export-csv-api.php'; // Assuming this is your PHP script for exporting CSV
    }
</script>
<?php include '../parts/html-foot.php' ?>