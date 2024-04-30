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

$t_sql = "SELECT COUNT(1) FROM trip_plans";
$t_stmt = $pdo->query($t_sql);
$row = $t_stmt->fetch(PDO::FETCH_NUM);
// $row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

// print_r($row); 
// exit; #直接離開程式
$totalRows = $row[0]; # 取得總筆數
$totalPages = 0; # 預設值
$rows = []; # 預設值

// 假設默認排序是按 trip_plan_id 升序
$sortColumn = $_GET['sort'] ?? 'trip_plan_id';
$order = $_GET['order'] ?? 'ASC';

// 確保只允許特定的列名和排序方向
$allowedSortColumns = ['trip_plan_id', 'user_id', 'trip_title', 'trip_content', 'trip_description', 'trip_notes', 'trip_date', 'trip_draft', 'created_at'];
$allowedOrder = ['ASC', 'DESC'];
$sortColumn = in_array($sortColumn, $allowedSortColumns) ? $sortColumn : 'trip_plan_id';
$order = in_array(strtoupper($order), $allowedOrder) ? strtoupper($order) : 'ASC';

if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);

    if ($page > $totalPages) {
        // redirect
        header('Location: ?page=' . $totalPages);
        exit;
    }

    $sql = sprintf(
        "SELECT c.*, u.username FROM trip_plans AS c 
    JOIN member_user AS u ON c.user_id = u.user_id
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
            <h5>Trip Planning</h5>
            <!-- add breadcrumb start -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="trip_plan_list.php" class="text-decoration-none">Trip Planning</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Plan</li>
                </ol>
            </nav>
            <!-- add breadcrumb end -->

            <div class="row align-items-center">
                <!-- pagination -->
                <?php include '../parts/pagination.php' ?>
                <!-- pagination -->




                <div class="col-auto d-flex align-items-center">


                    <!-- Search bar -->
                    <div class="input-group me-3">
                        <input type="text" id="search-input" class="form-control mb-3" placeholder="Search" onkeypress="handleKeyPress(event)">
                        <button type="button" class="btn btn-primary mb-3" onclick="searchData()"><i class="bi bi-search"></i></button>
                        <!-- Return button  -->
                        <div class="col-auto ms-1">
                            <a href="./trip_plan_list.php" class="btn btn-primary mb-3"><i class="fa-solid fa-rotate-left"></i></a>
                        </div>
                        <!-- Return button  -->
                    </div>
                    <!-- Search bar -->

                    <!-- add button start -->
                    <div class="col-auto">
                        <a href="trip_plan_add.php" class="btn btn-primary mb-3"><i class="fa-solid fa-plus"></i></a>
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
                                        <span>trip_plan_id</span>
                                        <a href="?sort=trip_plan_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                        </a>
                                    </div>
                                </th>
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
                                        <span>trip_title</span>
                                        <a href="?sort=trip_title&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                        </a>
                                    </div>
                                </th>
                                <th>
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <span>trip_content</span>
                                        <a href="?sort=trip_content&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                        </a>
                                    </div>
                                </th>
                                <th>
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <span>trip_description</span>
                                        <a href="?sort=trip_description&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                        </a>
                                    </div>
                                </th>
                                <th>
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <span>trip_notes</span>
                                        <a href="?sort=trip_notes&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                        </a>
                                    </div>
                                </th>
                                <th>
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <span>trip_date</span>
                                        <a href="?sort=trip_date&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                        </a>
                                    </div>
                                </th>
                                <th>
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <span>trip_draft</span>
                                        <a href="?sort=trip_draft&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
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
                                <th><i class="fa-solid fa-file-pen"></i></th>
                                <th><i class="bi bi-info-circle-fill"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $row) : ?>
                                <tr>
                                    <td>
                                        <a href="javascript: delete_one(<?= $row['trip_plan_id'] ?>)">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </td>
                                    <td><?= $row['trip_plan_id'] ?></td>
                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                    <td><?= $row['trip_title'] ?></td>
                                    <td><?= $row['trip_content'] ?></td>
                                    <td><?= $row['trip_description'] ?></td>
                                    <td>
                                        <div style="max-height: 3em; overflow: hidden; transition: max-height 0.3s ease-in-out;">
                                            <?= htmlspecialchars($row['trip_notes']) ?>
                                        </div>
                                        <?php if (!empty($row['trip_notes'])) : ?>
                                            <a style="cursor: pointer; color: grey;" onclick="toggleDescription(this)">...more</a>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $row['trip_date'] ?></td>
                                    <td><?= $row['trip_draft'] ?></td>
                                    <td><?= $row['created_at'] ?></td>
                                    <!-- strip_tags -->
                                    <!-- 避免 XSS 攻擊問題 -->
                                    <td>
                                        <a href="trip_plan_edit.php?trip_plan_id=<?= $row['trip_plan_id'] ?>">
                                            <i class="fa-solid fa-file-pen"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);" onclick="showDetailsModal(<?= htmlspecialchars(json_encode($row)) ?>)"><i class="bi bi-info-circle-fill"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="display: flex; align-items: center; justify-content: center; margin: 0 auto;">
            <div class="modal-content" style="background-color: rgba(0, 62, 82, 0.9); border-radius: 15px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Post Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- 填充詳細資訊 -->
                </div>
            </div>
        </div>
    </div>

    <?php include '../parts/scripts.php' ?>
    <script>
        function delete_one(trip_plan_id) {
            if (confirm(`Do you want to delete the data with the ID ${trip_plan_id} ?`)) {
                location.href = `trip_plan_delete.php?trip_plan_id=${trip_plan_id}`
            }
        }

        function searchData() {
            const keyword = document.getElementById('search-input').value;

            fetch(`trip_plan_search-api.php?keyword=${keyword}`)
                .then(r => r.json())
                .then(result => {
                    updateTable(result.data, keyword);
                });
        }

        // press enter to search
        function handleKeyPress(event) {
            if (event.keyCode === 13) { // 13 是 Enter 鍵的 keyCode
                searchData(); // 呼叫 searchData 函數進行搜尋
                event.preventDefault(); // 防止表單提交的預設行為
            }
        }
        // trip_notes內容展開縮放
        function toggleDescription(button) {
            var container = button.previousElementSibling; // 取得描述內容的容器
            container.style.maxHeight = container.style.maxHeight === '3em' ? 'none' : '3em'; // 切換高度
            button.innerText = container.style.maxHeight === '3em' ? '...more' : 'Hide'; // 切換按鈕內容
        }

        function updateTable(data, keyword) {
            const tableBody = document.querySelector('table tbody');
            tableBody.innerHTML = '';

            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
            <td>
                <a href="javascript: delete_one(${row.trip_plan_id})">
                    <i class="fa-solid fa-trash-can"></i>
                </a>
            </td>
            <td>${highlightKeyword(row.trip_plan_id.toString(), keyword)}</td>
            <td>${highlightKeyword(row.username, keyword)}</td>
            <td>${highlightKeyword(row.trip_title, keyword)}</td>
            <td>${highlightKeyword(row.trip_content, keyword)}</td>
            <td>${highlightKeyword(row.trip_description, keyword)}</td>
            <td>${highlightKeyword(row.trip_notes, keyword)}</td>
            <td>${highlightKeyword(row.trip_date, keyword)}</td>
            <td>${highlightKeyword(row.trip_draft, keyword)}</td>
            <td>${highlightKeyword(row.created_at, keyword)}</td>
            <td>
                <a href="trip_trip_plan_edit.php?trip_plan_id=${row.trip_plan_id}">
                    <i class="fa-solid fa-file-pen"></i>
                </a>
            </td>
            <td>
                <a href="javascript:void(0);" class="details-link" data-row='${JSON.stringify(row)}'><i class="bi bi-info-circle-fill"></i></a>
            </td>
        `; //更改<a>之中的內容，讓彈跳視窗正確顯示資訊
                tableBody.appendChild(tr);
            });
            document.querySelectorAll('.details-link').forEach(element => {
                element.addEventListener('click', function() {
                    const rowData = JSON.parse(this.getAttribute('data-row'));
                    showDetailsModal(rowData);
                });
            });
            //為新添加的元素增加監聽事件
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

        function showDetailsModal(data) {
            const modalBody = document.querySelector('#detailsModal .modal-body');
            modalBody.innerHTML = `
            <div class="table-responsive" style="align-items: center; display: flex; flex-direction: column;">
                <table class="table table-bordered" style="border-color: #e6ded3; width: 600px;">
                    <tbody>
                        <tr>
                            <th scope="row" style="background-color: rgba(255, 255, 255, 0.3)";>trip_plan_id</th>
                            <td style="background-color: rgba(255, 255, 255, 0.3)">${data.trip_plan_id}</td>
                        </tr>
                        <tr>
                            <th scope="row">username</th>
                            <td>${data.username}</td>
                        </tr>
                        <tr>
                            <th scope="row" style="background-color: rgba(255, 255, 255, 0.3);">trip_title</th>
                            <td style="background-color: rgba(255, 255, 255, 0.3)">${data.trip_title}</td>
                        </tr>
                        <tr>
                            <th scope="row">trip_content</th>
                            <td>${data.trip_content}</td>
                        </tr>
                        <tr>
                            <th scope="row" style="background-color: rgba(255, 255, 255, 0.3)">trip_description</th>
                            <td style="background-color: rgba(255, 255, 255, 0.3)">${data.trip_description}</td>
                        </tr>
                        <tr>
                            <th scope="row">trip_notes</th>
                            <td>${data.trip_notes}</td>
                        </tr>
                        <tr>
                            <th scope="row" style="background-color: rgba(255, 255, 255, 0.3);">trip_date</th>
                            <td style="background-color: rgba(255, 255, 255, 0.3)">${data.trip_date}</td>
                        </tr>
                        <tr>
                            <th scope="row">trip_draft</th>
                            <td>${data.trip_draft}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
            var myModal = new bootstrap.Modal(document.getElementById('detailsModal'));
            myModal.show();
        }
    </script>




    <?php include '../parts/html-foot.php' ?>