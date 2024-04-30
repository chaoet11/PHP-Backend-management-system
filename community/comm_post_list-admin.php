<?php 
    require '../parts/db_connect.php';
    $pageName = 'list';
    $title = 'List';


    $perPage = 20;

    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    if($page < 1){
        // redirect
        header('Location: ?page=1');
        exit;
    }

    $t_sql = "SELECT COUNT(1) FROM comm_post";
    $t_stmt = $pdo->query($t_sql);
    $row = $t_stmt->fetch(PDO::FETCH_NUM);
    // $row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

    // print_r($row); 
    // exit; #直接離開程式
    $totalRows = $row[0]; # 取得總筆數
    $totalPages = 0; # 預設值
    $rows = []; # 預設值

    // 假設默認排序是按 post_id 升序
    $sortColumn = $_GET['sort'] ?? 'post_id';
    $order = $_GET['order'] ?? 'ASC';

    // 確保只允許特定的列名和排序方向
    $allowedSortColumns = ['post_id', 'context', 'created_at', 'updated_at', 'username'];
    $allowedOrder = ['ASC', 'DESC'];
    $sortColumn = in_array($sortColumn, $allowedSortColumns) ? $sortColumn : 'post_id';
    $order = in_array(strtoupper($order), $allowedOrder) ? strtoupper($order) : 'ASC';

    if($totalRows > 0) {
        $totalPages = ceil($totalRows / $perPage);

        if($page > $totalPages) {
            // redirect
            header('Location: ?page='. $totalPages);
            exit;
        }

        // $sql = sprintf("SELECT * FROM comm_post ORDER BY post_id DESC
        //             LIMIT %s, %s", ($page-1)*$perPage, $perPage);

        $sql = sprintf(
            "SELECT p.*, u.username, ph.comm_photo_id, ph.photo_name, ph.img 
            FROM comm_post AS p
            JOIN member_user AS u ON p.user_id = u.user_id
            LEFT JOIN comm_photo AS ph ON p.post_id = ph.post_id
            ORDER BY %s %s LIMIT %s, %s",
            $sortColumn, $order, ($page-1)*$perPage, $perPage);
        
        $stmt = $pdo->query($sql); 
        $rows = $stmt->fetchAll(); 
        
        foreach ($rows as $i => $row) {
            if (isset($row['img']) && $row['img']) {
                $rows[$i]['img'] = 'data:image/jpeg;base64,' . base64_encode($row['img']);
            }
        }      
    }

?>

<?php include '../parts/html-head.php'?>

<style>
    .ellipsis:hover {
    color: #bc955c !important;
}
</style>

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
                <li class="breadcrumb-item"><a href="comm_post_list.php" class="text-decoration-none">Community</a></li>
                <li class="breadcrumb-item active" aria-current="page">Post</li>
            </ol>
        </nav>
        <!-- add breadcrumb end -->

        <div class="row align-items-center">
            <!-- pagination -->
            <?php include '../parts/pagination.php'?>
            <!-- pagination -->

            <!-- Dropdown for category selection -->
            <div class="col-auto dropdown mb-3">
                <button class="btn btn-primary dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    All Categories
                </button>
                <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('')">All Categories</a></li>
                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('酒吧')">Bars</a></li>
                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('電影')">Movies</a></li>
                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('約會')">Dating</a></li>
                </ul>
            </div>
            <!-- Dropdown for category selection -->


            <div class="col-auto d-flex align-items-center">
                <!-- Search Category Dropdown -->
                <div class="dropdown me-1 mb-3">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="searchCategoryButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Option
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="searchCategoryButton">
                        <li><a class="dropdown-item" href="#" onclick="setSearchCategory('all')">All</a></li>
                        <li><a class="dropdown-item" href="#" onclick="setSearchCategory('post_id')">Post ID</a></li>
                        <li><a class="dropdown-item" href="#" onclick="setSearchCategory('context')">Context</a></li>
                        <li><a class="dropdown-item" href="#" onclick="setSearchCategory('created_at')">Created At</a></li>
                        <li><a class="dropdown-item" href="#" onclick="setSearchCategory('updated_at')">Updated At</a></li>
                        <li><a class="dropdown-item" href="#" onclick="setSearchCategory('username')">Username</a></li>
                        <!-- <li><a class="dropdown-item" href="#" onclick="setSearchCategory('comm_photo_id')">Photo ID</a></li>
                        <li><a class="dropdown-item" href="#" onclick="setSearchCategory('photo_name')">Photo Name</a></li> -->
                    </ul>
                </div>
                <!-- Search Category Dropdown -->

                <!-- Search bar -->
                <div class="input-group">
                    <input type="text" id="search-input" class="form-control mb-3" placeholder="Search" onkeypress="handleKeyPress(event)">
                    <button type="button" class="btn btn-primary mb-3" onclick="searchData()"><i class="bi bi-search"></i></button>
                    <!-- Return button  -->
                    <div class="col-auto ms-1">
                        <a href="comm_post_list.php" class="btn btn-primary mb-3"><i class="fa-solid fa-rotate-left"></i></a>
                    </div>
                    <!-- Return button  -->
                </div>
                <!-- Search bar -->

                <div class="col-auto d-flex align-items-center">
                <!-- add button start -->
                <div class="col-auto ms-4 me-1">
                    <a href="comm_post_add.php" class="btn btn-primary mb-3"><i class="fa-solid fa-plus"></i></a>
                </div>
                <!-- add end start -->

                <!-- Export CSV Button -->
                <div class="col-auto me-3">
                    <button type="button" class="btn btn-primary mb-3" onclick="exportCsv()"><i class="bi bi-file-earmark-arrow-down-fill"></i></button>
                </div>
                <!-- Export CSV Button -->
                </div>

                <!-- Pie chart button  -->
                <div class="col-auto me-1">
                    <a href="comm_statistic_piechart.php" class="btn btn-primary mb-3"><i class="bi bi-pie-chart-fill"></i></a>
                    <!-- Pie chart button  -->
                </div>

                <!-- Bar chart button start -->
                <div class="col-auto">
                    <a href="comm_statistic_chart.php" class="btn btn-primary mb-3"><i class="bi bi-bar-chart-fill"></i></a>
                </div>
                <!-- Bar chart end start -->
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-primary">
            <thead>
                <tr>
                    <th><i class="fa-solid fa-trash-can"></i></th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>post_id</span>
                        <a href="?sort=post_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a>
                        </div>
                    </th>
                    <th>image</th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>context</span>
                        <a href="?sort=context&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
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
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>username</span>
                        <a href="?sort=username&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i> 
                        </a>
                        </div>
                    </th>
                    <!-- <th>
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                            <span>comm_photo_id</span>
                            <a href="?sort=comm_photo_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                <i class="fa-solid fa-sort" style="color: #e6ded3"></i>
                            </a>
                        </div>
                    </th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <span>photo_name</span>
                            <a href="?sort=photo_name&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                <i class="fa-solid fa-sort" style="color: #e6ded3"></i>
                            </a>
                        </div>
                    </th> -->
                    <th><i class="fa-solid fa-file-pen"></i></th>
                    <th><i class="bi bi-info-circle-fill"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($rows as $row): ?>
                <tr>
                    <td>
                        <a href="javascript: delete_one(<?= $row['post_id'] ?>)">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </td>
                    <td><?= $row['post_id'] ?></td>
                    <td>
                        <img src="<?= $row['img'] ?>" alt="Photo" style="width: 160px; height: 100px; cursor: pointer;" onclick="showImageModal(this.src)">
                    </td>
                    <td>
                        <span class="all-content"><?= $row['context'] ?></span>
                    </td>
                    <td><?= $row['created_at'] ?></td>
                    <td><?= $row['updated_at'] ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td> <!-- 顯示 Username -->
                    <!-- <td><?= $row['comm_photo_id'] ?></td>
                    <td><?= $row['photo_name'] ?></td> -->
                    <!-- strip_tags -->
                    <!-- 避免 XSS 攻擊問題 -->
                    <td>
                        <a href="comm_post_edit.php?post_id=<?= $row['post_id'] ?>">
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

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="width: 700px; height: auto">
        <div class="modal-content" style="background-color: rgba(0, 62, 82, 0.9); border-radius: 15px;">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Image View</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="display: flex; justify-content: center; align-items: center; height: 80vh;">
                <img id="modalImage" src="" style="max-height: 100%; max-width: 100%;" alt="Preview" />
            </div>
        </div>
    </div>
</div>
<!-- Image Modal -->

<!-- Detail Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="display: flex; align-items: center; justify-content: center; position: fixed; top: 0; right: 0; bottom: 0; left: 0;">
        <div class="modal-content" style="background-color: rgba(0, 62, 82, 0.9); border-radius: 15px; width: 1300px;">
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
<!-- Detail Modal -->

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
    let currentSearchCategory = 'all'; // 當前選擇的搜尋類別

    function delete_one(post_id){
        if(confirm(`Do you want to delete the data with the ID ${post_id} ?`)){
            location.href = `comm_post_delete.php?post_id=${post_id}`
        }
    }

    function setSearchCategory(category) {
        currentSearchCategory = category;
        // 更新下拉式選單按鈕的文字
        document.getElementById('searchCategoryButton').innerText = category.charAt(0).toUpperCase() + category.slice(1); // 使首字母大寫
    }

    function searchData() {
        const keyword = document.getElementById('search-input').value;
        fetch(`comm_post_search-api.php?keyword=${keyword}&category=${currentSearchCategory}`)
            .then(r => r.json())
            .then(result => {
                updateTable(result.data, keyword); // 將關鍵字作為第二個參數傳遞
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

        // console.log('Result Data:', data);
         // Check if data is empty
            if (data.length === 0) {
                // Show noDataModal
            noDataModal.show();
            return;
        }

        data.forEach(row => {
            // Check if image data exists and encode it for display
            const imgSrc = row.img ? row.img : '';

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <a href="javascript: delete_one(${row.post_id})">
                        <i class="fa-solid fa-trash-can"></i>
                    </a>
                </td>
                <td>${highlightKeyword(row.post_id.toString(), keyword)}</td>
                <td>
                    <img src="${imgSrc}" alt="Photo" style="width: 160px; height: 100px;" onclick="showImageModal('${imgSrc}')">
                </td>
                <td>${highlightKeyword(row.context, keyword)}</td>
                <td>${highlightKeyword(row.created_at, keyword)}</td>
                <td>${highlightKeyword(row.updated_at, keyword)}</td>
                <td>${highlightKeyword(row.username, keyword)}</td>
                <td>
                    <a href="comm_post_edit.php?post_id=${row.post_id}">
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

            const contentSpans = tr.querySelectorAll('.all-content');
            // console.log(contentSpan);
            contentSpans.forEach(contentSpan => {
                handleContent(contentSpan);
            });
        });
    }

    function filterCategory(category) {
        fetch(`comm_post_filter-api.php?category=${category}`)
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
                case '酒吧':
                    buttonText = 'Bars';
                    break;
                case '電影':
                    buttonText = 'Movies';
                    break;
                case '約會':
                    buttonText = 'Dating';
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

    function highlightKeyword(text, keyword) {
        if (typeof text !== 'string' || !keyword || keyword.trim() === '') {
            return text;
        } 

        const regex = new RegExp(keyword, 'gi'); // 'gi' 表示全局且不區分大小寫
        const highlightedText = text.replace(regex, match => `<span style="background-color: yellow">${match}</span>`);
        return highlightedText;
    }

    function showImageModal(src) {
        // 設置模態框中的圖片源
        document.getElementById('modalImage').src = src;
        // 顯示模態框
        var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
        myModal.show();
    }

    function showDetailsModal(data) {
        const modalBody = document.querySelector('#detailsModal .modal-body');
        modalBody.innerHTML = `
            <div class="d-flex flex-row justify-content-center align-items-start" style="display: flex; flex-direction: row; align-items: start; justify-content: center;">
                <img src="${data.img}" alt="Photo" style="margin-bottom: 20px; width: 600px; height: 400px">
                <table class="table table-bordered table-responsive ms-3" style="border-color: #e6ded3; width: 400px; height: 400px">
                    <tbody>
                        <tr>
                            <th scope="row" style="background-color: rgba(255, 255, 255, 0.3)";>Post ID</th>
                            <td style="background-color: rgba(255, 255, 255, 0.3)">${data.post_id}</td>
                        </tr>
                        <tr>
                            <th scope="row">Context</th>
                            <td>${data.context}</td>
                        </tr>
                        <tr>
                            <th scope="row" style="background-color: rgba(255, 255, 255, 0.3);">Created At</th>
                            <td style="background-color: rgba(255, 255, 255, 0.3)">${data.created_at}</td>
                        </tr>
                        <tr>
                            <th scope="row">Updated At</th>
                            <td>${data.updated_at}</td>
                        </tr>
                        <tr>
                            <th scope="row" style="background-color: rgba(255, 255, 255, 0.3)">Username</th>
                            <td style="background-color: rgba(255, 255, 255, 0.3)">${data.username}</td>
                        </tr>
                        <tr>
                            <th scope="row">Photo Name</th>
                            <td>${data.photo_name}</td>
                        </tr>
                        <tr>
                            <th scope="row" style="background-color: rgba(255, 255, 255, 0.3);">Photo ID</th>
                            <td style="background-color: rgba(255, 255, 255, 0.3)">${data.comm_photo_id}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
        var myModal = new bootstrap.Modal(document.getElementById('detailsModal'));
        myModal.show();
    }

    function exportCsv() {
        window.location.href = 'comm_post_export-csv-api.php'; // Assuming this is your PHP script for exporting CSV
    }

    //read more...

    function handleContent(content) {
        let originalContent = content.innerHTML;
        let maxLength = 30;

        // 檢查文字數量
        if (originalContent.length > maxLength) {
            let lessContent = originalContent.substring(0, maxLength - 1) + '<span class="ellipsis" style="color: #003e52; font-weight: bold; font-size: 12px; cursor: pointer;"> <i class="bi bi-arrow-right-circle-fill"></i> READ MORE </span>';
            content.innerHTML = lessContent;

            // 點擊 read more
            content.addEventListener('click', function expandContent() {
                content.innerHTML = originalContent;
                content.removeEventListener('click', expandContent);
            });
        }
    }

    // 載入完成後再執行
    document.addEventListener('DOMContentLoaded', function () {
        let allContents = document.querySelectorAll('.all-content');
        allContents.forEach(handleContent);
    });

    const noDataModal = new bootstrap.Modal(document.getElementById('noDataModal'));
</script>
<?php include '../parts/html-foot.php' ?>