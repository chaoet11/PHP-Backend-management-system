<?php 
    include '../parts/db_connect.php';
    $pageName = 'list';
    $title = 'List';

    $perPage = 20;

    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    if($page < 1){
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

    if($totalRows > 0) {
        $totalPages = ceil($totalRows / $perPage);

        if($page > $totalPages) {
            // redirect
            header('Location: ?page='. $totalPages);
            exit;
        }

        // $sql = sprintf("SELECT * FROM bars ORDER BY bar_id DESC
        //             LIMIT %s, %s", ($page-1)*$perPage, $perPage);
        //             LIMIT %s, %s", ($page-1)*$perPage, $perPage);
    // 修改 SQL 查詢以包含排序參數
    // $sql = sprintf("SELECT * FROM bars ORDER BY %s %s LIMIT %s, %s", $sortColumn, $order, ($page-1)*$perPage, $perPage);
    
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
                <li class="breadcrumb-item"><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="bars_list.php" class="text-decoration-none">Bar</a></li>
                <li class="breadcrumb-item active" aria-current="page">Bars</li>
            </ol>
        </nav>
        <!-- add breadcrumb end -->

        <div class="row align-items-center">
            <!-- pagination -->

            <?php include '../parts/pagination.php'?>

            <!-- pagination -->

            <!-- Search bar -->
            <div class="col-auto me-3">
                <div class="input-group">
                    <input type="text" id="search-input" class="form-control mb-3" placeholder="Search" onkeypress="handleKeyPress(event)">
                    <button type="button" class="btn btn-primary mb-3 me-2" onclick="searchData()"><i class="bi bi-search"></i></button>
                    <!-- Return button  -->
                    <div class="col-auto">
                        <a href="bars_list.php" class="btn btn-primary mb-3"><i class="fa-solid fa-rotate-left"></i></a>
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
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rows as $row): ?>
                    <tr>
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
// bar_description內容展開縮放
    function toggleDescription(button) {
    var container = button.previousElementSibling; // 取得描述內容的容器
    container.style.maxHeight = container.style.maxHeight === '3em' ? 'none' : '3em'; // 切換高度
    button.innerText = container.style.maxHeight === '3em' ? '...more' : 'Hide'; // 切換按鈕內容
}


   function searchData() {
        const keyword = document.getElementById('search-input').value;

        fetch(`bars_search-api.php?keyword=${keyword}`)
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
            tr.innerHTML = `
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
        `;
            tableBody.appendChild(tr);
        });
    }

    // press enter to search
    function handleKeyPress(event) {
        if (event.keyCode === 13) { // 13 是 Enter 鍵的 keyCode
            searchData(); // 呼叫 searchData 函數進行搜尋
            event.preventDefault(); // 防止表單提交的預設行為
        }
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
</script>
<?php include '../parts/html-foot.php' ?>

