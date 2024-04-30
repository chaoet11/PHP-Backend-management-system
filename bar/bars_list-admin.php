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

$t_sql = "SELECT COUNT(1) FROM bars";
$t_stmt = $pdo->query($t_sql);
$row = $t_stmt->fetch(PDO::FETCH_NUM);
// $row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

// print_r($row); 
// exit; #直接離開程式
$totalRows = $row[0]; # 取得總筆數
$totalPages = 0; # 預設值
$rows = []; # 預設值

// 假設默認排序是按 bar_id 升序
$sortColumn = $_GET['sort'] ?? 'bar_id';
$order = $_GET['order'] ?? 'ASC';

// 確保只允許特定的列名和排序方向
$allowedSortColumns = ['bar_id', 'bar_name', 'bar_city', 'bar_area_id', 'bar_addr', 'bar_opening_time', 'bar_closing_time', 'bar_contact', 'bar_description', 'bar_type_id', 'bar_latitude', 'bar_longtitude'];
$allowedOrder = ['ASC', 'DESC'];
$sortColumn = in_array($sortColumn, $allowedSortColumns) ? $sortColumn : 'bar_id';
$order = in_array(strtoupper($order), $allowedOrder) ? strtoupper($order) : 'ASC';


if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);

    if ($page > $totalPages) {
        // redirect
        header('Location: ?page=' . $totalPages);
        exit;
    }

    // $sql = sprintf("SELECT * FROM bars ORDER BY bar_id DESC
    //             LIMIT %s, %s", ($page-1)*$perPage, $perPage);
    // 修改 SQL 查詢以包含排序參數
    // $sql = sprintf("SELECT * FROM bars ORDER BY %s %s LIMIT %s, %s", $sortColumn, $order, ($page-1)*$perPage, $perPage);
    //     $stmt = $pdo->query($sql); 
    //     $rows = $stmt->fetchAll();
    // 修改 SQL 查詢以包含 JOIN
    $sql = sprintf(
        "SELECT bars.*, bar_area.bar_area_name, bar_type.bar_type_name FROM bars
    LEFT JOIN bar_area ON bars.bar_area_id = bar_area.bar_area_id
    LEFT JOIN bar_type ON bars.bar_type_id = bar_type.bar_type_id
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
            <h5>Bar</h5>
            <!-- add breadcrumb start -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="bars_list.php" class="text-decoration-none">Bar</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Bars</li>
                </ol>
            </nav>
            <!-- add breadcrumb end -->

            <div class="col-auto row align-items-center">
                <div class="row align-items-center">
                    <!-- pagination -->
                    <?php include '../parts/pagination.php' ?>
                    <!-- pagination -->


                    <!-- Dropdown for category selection -->
                    <div class="col-auto dropdown mb-3">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            All Categories
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                            <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('')">All Categories</a></li>
                            <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('1')">運動酒吧 Sport Bar</a></li>
                            <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('2')">音樂酒吧 Music Bar</a></li>
                            <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('3')">異國酒吧 Foreign Bar</a></li>
                            <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('4')">特色酒吧 Specialty Bar</a></li>
                            <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('5')">其他 Other Bar</a></li>
                        </ul>
                    </div>
                    <!-- Dropdown for category selection -->

                    <div class="col-auto d-flex align-items-center">
                        <!-- Search bar -->
                        <div class="col-auto me-2">
                            <div class="input-group">
                                <input type="text" id="search-input" class="form-control mb-3" placeholder="Search" onkeypress="handleKeyPress(event)">
                                <button type="button" class="btn btn-primary mb-3 me-2" onclick="searchData()">
                                    <i class="bi bi-search"></i>
                                </button>
                                <!-- Return button  -->
                                <div class="col-auto">
                                    <a href="bars_list.php" class="btn btn-primary mb-3">
                                        <i class="fa-solid fa-rotate-left"></i>
                                    </a>
                                </div>
                                <!-- Return button  -->
                            </div>
                        </div>
                        <!-- Search bar -->

                        <!-- add button start -->
                        <div class="col-auto">
                            <a href="bars_add.php" class="btn btn-primary mb-3"><i class="fa-solid fa-plus"></i></a>
                        </div>
                        <!-- add end start -->
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-primary">
                        <thead>
                            <tr>
                                <th><i class="fa-solid fa-trash-can"></i></th>
                                <th>
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <span>bar_id</span>
                                        <a href="?sort=bar_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                        </a>
                                    </div>
                                </th>
                                <th>
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <span>bar_name</span>
                                        <a href="?sort=bar_name&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                        </a>
                                    </div>
                                </th>
                                <th>bar_city</th>
                                <th>
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <span>bar_area</span>
                                        <a href="?sort=bar_area_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                        </a>
                                    </div>
                                </th>
                                <th>bar_addr</th>
                                <th>
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <span>bar_opening_time</span>
                                        <a href="?sort=bar_opening_time&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                        </a>
                                    </div>
                                </th>
                                <th>
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <span>bar_closing_time</span>
                                        <a href="?sort=bar_closing_time&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                        </a>
                                    </div>
                                </th>
                                <th>
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <span>bar_contact</span>
                                        <a href="?sort=bar_contact&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                        </a>
                                    </div>
                                </th>
                                <th>bar_description</th>
                                <th>
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <span>bar_type_id</span>
                                        <a href="?sort=bar_type_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                        </a>
                                    </div>
                                </th>
                                <th>
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <span>bar_latitude</span>
                                        <a href="?sort=bar_latitude&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                        </a>
                                    </div>
                                </th>
                                <th>
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <span>bar_longtitude</span>
                                        <a href="?sort=bar_longtitude&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
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
                                        <a href="javascript: delete_one(<?= $row['bar_id'] ?>)">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </td>
                                    <td><?= $row['bar_id'] ?></td>
                                    <td><?= $row['bar_name'] ?></td>
                                    <td><?= $row['bar_city'] ?></td>
                                    <!-- <td><?= $row['bar_area_id'] ?></td> -->
                                    <td><?= htmlspecialchars($row['bar_area_name']) ?></td> <!-- 顯示 bar_area_name -->
                                    <td><?= $row['bar_addr'] ?></td>
                                    <td><?= $row['bar_opening_time'] ?></td>
                                    <td><?= $row['bar_closing_time'] ?></td>
                                    <td><?= $row['bar_contact'] ?></td>
                                    <!-- <td><?= $row['bar_description'] ?></td> -->
                                    <td>
                                        <div style="max-height: 3em; overflow: hidden; transition: max-height 0.3s ease-in-out;">
                                            <?= $row['bar_description'] ?>
                                        </div>
                                        <a style="cursor: pointer; color: grey;" onclick="toggleDescription(this)">...more</a>
                                    </td>
                                    <!-- <td><?= $row['bar_type_id'] ?></td> -->
                                    <td><?= htmlspecialchars($row['bar_type_name']) ?></td>
                                    <td><?= $row['bar_latitude'] ?></td>
                                    <td><?= $row['bar_longtitude'] ?></td>
                                    <!-- strip_tags -->
                                    <!-- 避免 XSS 攻擊問題 -->
                                    <td>
                                        <a href="bars_edit.php?bar_id=<?= $row['bar_id'] ?>">
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

    <!-- Detail Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="display: flex; align-items: center; justify-content: center; position: fixed; top: 0; right: 0; bottom: 0; left: 0;">
            <div class="modal-content" style="background-color: rgba(0, 62, 82, 0.9); border-radius: 15px; width: 1300px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Bar Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- 填充詳細資訊 -->
                </div>
            </div>
        </div>
    </div>
    <!-- Detail Modal -->

    <?php include '../parts/scripts.php' ?>
    <script>
        // search category
        let currentSearchCategory = ''; // 當前選擇的搜尋類別
        function setSearchCategory(category) {
            currentSearchCategory = category;
            document.getElementById('searchCategoryDropdown').innerText = category;
        }
        // search category


        function delete_one(bar_id) {
            if (confirm(`Do you want to delete the data with the ID ${bar_id} ?`)) {
                location.href = `bars_delete.php?bar_id=${bar_id}`
            }
        }

        // bar_description內容展開縮放
        function toggleDescription(button) {
            var container = button.previousElementSibling; // 取得描述內容的容器
            container.style.maxHeight = container.style.maxHeight === '3em' ? 'none' : '3em'; // 切換高度
            button.innerText = container.style.maxHeight === '3em' ? '...more' : 'Hide'; // 切換按鈕內容
        }

        // function searchData() {
        //     const keyword = document.getElementById('search-input').value;

        //     fetch(`bars_search-api.php?keyword=${keyword}`)
        //         .then(r => r.json())
        //         .then(result => {
        //             updateTable(result.data, keyword);
        //             resetDropdown(); // 重置下拉式選單
        //         });
        // }

        // 調整成可以搜尋關鍵字同時再選擇酒吧類別
        function searchData() {
            const keyword = document.getElementById('search-input').value;
            // 假設 currentSearchCategory 是一個全域變數，儲存了當前選擇的類型
            fetch(`bars_search-api.php?keyword=${keyword}&category=${currentSearchCategory}`)
                .then(r => r.json())
                .then(result => {
                    updateTable(result.data, keyword); // 更新表格
                });
        }

        // press enter to search
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
            <td>
                <a href="javascript: delete_one(${row.bar_id})">
                    <i class="fa-solid fa-trash-can"></i>
                </a>
            </td>
            <td>${highlightKeyword(row.bar_id.toString(), keyword)}</td>
            <td>${highlightKeyword(row.bar_name, keyword)}</td>
            <td>${highlightKeyword(row.bar_city, keyword)}</td>
            <td>${highlightKeyword(row.bar_area_name, keyword)}</td>
            <td>${highlightKeyword(row.bar_addr, keyword)}</td>
            <td>${highlightKeyword(row.bar_opening_time, keyword)}</td>
            <td>${highlightKeyword(row.bar_closing_time, keyword)}</td>
            <td>${highlightKeyword(row.bar_contact, keyword)}</td>
            <td>${highlightKeyword(row.bar_description, keyword)}</td>
            <td>${highlightKeyword(row.bar_type_name, keyword)}</td>
            <td>${highlightKeyword(row.bar_latitude, keyword)}</td>
            <td>${highlightKeyword(row.bar_longtitude, keyword)}</td>
            <td>
                <a href="bars_edit.php?bar_id=${row.bar_id}">
                    <i class="fa-solid fa-file-pen"></i>
                </a>
            </td>
                <td>
                    <a href="javascript:void(0);" onclick='showDetailsModal(${JSON.stringify(row)})'>
                        <i class="bi bi-info-circle-fill"></i>
                    </a>
                </td>
        `;
                tableBody.appendChild(tr);
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


        // function filterCategory(category) {
        //     fetch(`bars_filter-api.php?category=${category}`)
        //         .then(response => response.json())
        //         .then(result => {
        //             updateTable(result.data);
        //             updateDropdownButtonText(category); // Updates the dropdown button text
        //         })
        //         .catch(error => console.error('Error:', error));
        // }

        // 調整成可以搜尋關鍵字同時再選擇酒吧類別
        function filterCategory(category) {
            const keyword = document.getElementById('search-input').value; // 獲取當前關鍵字
            fetch(`bars_filter-api.php?category=${category}&keyword=${keyword}`)
                .then(response => response.json())
                .then(result => {
                    updateTable(result.data);
                })
                .catch(error => console.error('Error:', error));
        }


        function updateDropdownButtonText(category) {
            let buttonText = 'All Categories'; // Default button text
            if (category) {
                // Check the category and update the button text to English
                switch (category) {
                    case '1':
                        buttonText = '運動酒吧 Sport Bar';
                        break;
                    case '2':
                        buttonText = '音樂酒吧 Music Bar';
                        break;
                    case '3':
                        buttonText = '異國酒吧 Foreign Bar';
                        break;
                    case '4':
                        buttonText = '特色酒吧 Specialty Bar';
                        break;
                    case '5':
                        buttonText = '其他 Other Bar';
                        break;
                    default:
                        buttonText = category;
                }
            }
            document.getElementById('categoryDropdown').innerText = buttonText;
        }

        function showDetailsModal(data) {
            const modalBody = document.querySelector('#detailsModal .modal-body');
            modalBody.innerHTML = `
            <div class="d-flex flex-row justify-content-center align-items-start" style="display: flex; flex-direction: row; align-items: start; justify-content: center;">
                <table class="table table-bordered table-responsive ms-3" style="border-color: #e6ded3; width: 400px; height: 400px">
                    <tbody>
                        <tr>
                            <th scope="row" style="background-color: rgba(255, 255, 255, 0.3)";>Bar ID</th>
                            <td style="background-color: rgba(255, 255, 255, 0.3)">${data.bar_id}</td>
                        </tr>
                        <tr>
                            <th scope="row">Bar Name</th>
                            <td>${data.bar_name}</td>
                        </tr>
                        <tr>
                            <th scope="row" style="background-color: rgba(255, 255, 255, 0.3);">Bar City</th>
                            <td style="background-color: rgba(255, 255, 255, 0.3)">${data.bar_city}</td>
                        </tr>
                        <tr>
                            <th scope="row">Bar Area</th>
                            <td>${data.bar_area_name}</td>
                        </tr>
                        <tr>
                            <th scope="row" style="background-color: rgba(255, 255, 255, 0.3)">Bar Address</th>
                            <td style="background-color: rgba(255, 255, 255, 0.3)">${data.bar_addr}</td>
                        </tr>
                        <tr>
                            <th scope="row">Opening Time</th>
                            <td>${data.bar_opening_time}</td>
                        </tr>
                        <tr>
                            <th scope="row" style="background-color: rgba(255, 255, 255, 0.3);">Closing Time</th>
                            <td style="background-color: rgba(255, 255, 255, 0.3)">${data.bar_closing_time}</td>
                        </tr>
                        <tr>
                            <th scope="row">Bar Contact</th>
                            <td>${data.bar_contact}</td>
                        </tr>
                        <tr>
                            <th scope="row" style="background-color: rgba(255, 255, 255, 0.3)">Description</th>
                            <td style="background-color: rgba(255, 255, 255, 0.3)">${data.bar_description}</td>
                        </tr>
                        <tr>
                            <th scope="row">Bar Type</th>
                            <td>${data.bar_type_name}</td>
                        </tr>
                        <tr>
                            <th scope="row" style="background-color: rgba(255, 255, 255, 0.3);">Bar Latitude</th>
                            <td style="background-color: rgba(255, 255, 255, 0.3)">${data.bar_latitude}</td>
                        </tr>
                        <tr>
                            <th scope="row">Bar Longtitude</th>
                            <td>${data.bar_longtitude}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
            var myModal = new bootstrap.Modal(document.getElementById('detailsModal'));
            myModal.show();
        }


        function resetDropdown() {
            currentSearchCategory = '';
            document.getElementById('categoryDropdown').innerText = 'All Categories';
        }
    </script>
    <?php include '../parts/html-foot.php' ?>