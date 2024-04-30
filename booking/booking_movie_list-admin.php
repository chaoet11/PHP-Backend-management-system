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

    $t_sql = "SELECT COUNT(1) FROM booking_movie";
    $t_stmt = $pdo->query($t_sql);
    $row = $t_stmt->fetch(PDO::FETCH_NUM);
    // $row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

    // print_r($row); 
    // exit; #直接離開程式
    $totalRows = $row[0]; # 取得總筆數
    $totalPages = 0; # 預設值
    $rows = []; # 預設值

    // $sort = $_GET['sort'] ?? 'asc';
    // $sortToggle = $sort === 'asc' ? 'desc' : 'asc';
    // $sortSql = $sort === 'asc' ? 'ASC' : 'DESC';


    // 假設默認排序是按 movie_id 升序
    $sortColumn = $_GET['sort'] ?? 'movie_id';
    $order = $_GET['order'] ?? 'ASC';

    // 確保只允許特定的列名和排序方向
    $allowedSortColumns = ['movie_id', 'title', 'poster_img', 'movie_description', 'movie_rating', 'movie_type_id','movie_img'];
    $allowedOrder = ['ASC', 'DESC'];
    $sortColumn = in_array($sortColumn, $allowedSortColumns) ? $sortColumn : 'movie_id';
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
        // 修改 SQL 查詢以包含排序和JOIN參數
        $sql = sprintf("SELECT p.*, u.movie_type FROM booking_movie AS p
        JOIN booking_movie_type AS u ON p.movie_type_id = u.movie_type_id
        ORDER BY %s %s LIMIT %s, %s", 
        $sortColumn, $order, ($page-1)*$perPage, $perPage);
        
        $stmt = $pdo->query($sql); 
        $rows = $stmt->fetchAll(); 

        foreach ($rows as $i => $row) {
            if (isset($row['movie_img']) && $row['movie_img']) {
                $rows[$i]['movie_img'] = 'data:image/jpeg;base64,' . base64_encode($row['movie_img']);
            }
        }    
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
        <h5>Booking System</h5>
        <!-- add breadcrumb start -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="booking_movie_list.php" class="text-decoration-none">Booking System</a></li>
                <li class="breadcrumb-item active" aria-current="page">Movie</li>
            </ol>
        </nav>
        <!-- add breadcrumb end -->

        <div class="col-auto row align-items-center">
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
                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('1')">Drama</a></li>
                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('2')">Romance</a></li>
                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('3')">Comedy</a></li>
                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('4')">Action</a></li>
                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('5')">Animation</a></li>
                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('6')">Thriller</a></li>
                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterCategory('7')">Mystery</a></li>
                </ul>
            </div>
            <!-- Dropdown for category selection -->

            <div class="col-auto d-flex align-items-center">

                <!-- Search bar -->
                <div class="col-auto me-3">
                    <div class="input-group">
                        <input type="text" id="search-input" class="form-control mb-3" placeholder="Search" onkeypress="handleKeyPress(event)">
                        <button type="button" class="btn btn-primary mb-3 me-2" onclick="searchData()">
                        <i class="bi bi-search"></i>
                        </button>
                        <!-- Return button  -->
                        <div class="col-auto">
                            <a href="booking_movie_list.php" class="btn btn-primary mb-3">
                            <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        </div>
                        <!-- Return button  -->
                    </div>
                </div>
                <!-- Search bar -->


                <!-- add button start -->
                <div class="col-auto">
                    <a href="booking_movie_add.php" class="btn btn-primary mb-3"><i class="fa-solid fa-plus"></i></a>
                </div>
                <!-- add end start -->

                <!-- Export CSV Button -->
                <div class="col-autom ms-1">
                    <button type="button" class="btn btn-primary mb-3" onclick="exportCsv()"><i class="bi bi-file-earmark-arrow-down-fill"></i></button>
                </div>
                <!-- Export CSV Button -->
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-primary">
            <thead>
                <tr>
                    <th><i class="fa-solid fa-trash-can"></i></th>
                    <!-- <th>post_id</th> -->
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
                        <span>title</span>
                        <a href="?sort=title&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a>
                        </div>
                    </th>

                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>poster_img</span>
                        <a href="?sort=poster_img&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a>
                        </div>
                    </th>

                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>movie_description</span>
                        <a href="?sort=movie_description&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a>
                        </div>
                    </th>

                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>movie_rating</span>
                        <a href="?sort=movie_rating&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a>
                        </div>
                    </th>

                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>movie_type</span>
                        <a href="?sort=movie_type_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a>
                        </div>
                    </th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>movie_img</span>
                        <a href="?sort=movie_type_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a>
                        </div>
                    </th>
                    <th><i class="fa-solid fa-file-pen"></i></th>
                    <th><i class="bi bi-info-circle-fill"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($rows as $row): ?>
                <tr>
                    <td>
                        <a href="javascript: delete_one(<?= $row['movie_id'] ?>)">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </td>
                    <td><?= $row['movie_id'] ?></td>
                    <td><?= $row['title'] ?></td>
                    <td><?= $row['poster_img'] ?></td>
                    <!-- <td><?= $row['movie_description'] ?></td> -->
                    <td>
                        <div style="max-height: 3em; overflow: hidden; transition: max-height 0.3s ease-in-out;">
                            <?= $row['movie_description'] ?>
                        </div>
                        <a style="cursor: pointer; color: grey;" onclick="toggleDescription(this)">...more</a>
                    </td>
                    <td><?= $row['movie_rating'] ?></td>
                    <td><?= htmlspecialchars($row['movie_type']) ?></td> 
                    <td>
                        <img src="<?= $row['movie_img'] ?>" alt="Photo" style="width: 70px; height: 100px; cursor: pointer;" onclick="showImageModal(this.src)">
                    </td>
                    <!-- strip_tags -->
                    <!-- 避免 XSS 攻擊問題 -->
                    <td>
                        <a href="booking_movie_edit.php?movie_id=<?= $row['movie_id'] ?>">
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
    <div class="modal-dialog modal-xl" style="width: 450px; height: 700px">
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

<?php include '../parts/scripts.php' ?>
<script>

    let currentSearchCategory = ''; // 當前選擇的搜尋類別

    function setSearchCategory(category) {
        currentSearchCategory = category;
        document.getElementById('searchCategoryDropdown').innerText = category;
    }

    function delete_one(movie_id){
        if(confirm(`是否要刪除編號為 ${movie_id} 的資料?`)){
            location.href = `booking_movie_delete.php?movie_id=${movie_id}`
        }
    }

    function searchData() {
        const keyword = document.getElementById('search-input').value;

        fetch(`booking_movie_search-api.php?keyword=${keyword}`)
            .then(r => r.json())
            .then(result => {
                updateTable(result.data, keyword); // 將關鍵字作為第二個參數傳遞
                resetDropdown(); // 重置下拉式選單
            });
    }

    function handleKeyPress(event) {
        if (event.keyCode === 13) { // 13 是 Enter 鍵的 keyCode
            searchData(); // 呼叫 searchData 函數進行搜尋
            event.preventDefault(); // 防止表單提交的預設行為
        }
    }

    function filterCategory(category) {
        fetch(`booking_movie_filter-api.php?category=${category}`)
            .then(response => response.json())
            .then(result => {
                updateTable(result.data);
                updateDropdownButtonText(category); // Updates the dropdown button text
            })
            .catch(error => console.error('Error:', error));
    }

    function updateTable(data, keyword) {
        const tableBody = document.querySelector('table tbody');
        tableBody.innerHTML = '';

        data.forEach(row => {
            const tr = document.createElement('tr');
            let imgSrc = '';
            if (row.movie_img) {
                imgSrc = `data:image/jpeg;base64,${row.movie_img}`;
            }

            tr.innerHTML = `
                <td>
                    <a href="javascript: delete_one(${row.movie_id})">
                        <i class="fa-solid fa-trash-can"></i>
                    </a>
                </td>
                <td>${highlightKeyword(row.movie_id.toString(), keyword)}</td>
                <td>${highlightKeyword(row.title, keyword)}</td>
                <td>${highlightKeyword(row.poster_img, keyword)}</td>
                <td>${highlightKeyword(row.movie_description, keyword)}</td>
                <td>${highlightKeyword(row.movie_rating, keyword)}</td>
                <td>${highlightKeyword(row.movie_type, keyword)}</td>
                <td>
                    <img src="${row.movie_img}" alt="Photo" style="width: 80px; height: 120px;">
                </td>
                <td>
                    <a href="booking_movie_edit.php?movie_id=${row.movie_id}">
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

    function updateDropdownButtonText(category) {
        let buttonText = 'All Categories'; // Default button text
        if (category) {
            // Check the category and update the button text to English
            switch (category) {
                case '1':
                    buttonText = 'Drama';
                    break;
                case '2':
                    buttonText = 'Romance';
                    break;
                case '3':
                    buttonText = 'Comedy';
                    break;
                case '4':
                    buttonText = 'Action';
                    break;
                case '5':
                    buttonText = 'Animation';
                    break;
                case '6':
                    buttonText = 'Thriller';
                    break;
                case '7':
                    buttonText = 'Mystery';
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


    function showImageModal(src) {
        // 設置模態框中的圖片源
        document.getElementById('modalImage').src = src;
        // 顯示模態框
        var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
        myModal.show();
    }

    function highlightKeyword(text, keyword) {
        if (typeof text !== 'string' || !keyword || keyword.trim() === '') {
            return text;
        }

        const regex = new RegExp(keyword, 'gi'); // 'gi' 表示全局且不區分大小寫
        const highlightedText = text.replace(regex, match => `<span style="background-color: yellow">${match}</span>`);
        return highlightedText;
    }

    function showDetailsModal(data) {
        const modalBody = document.querySelector('#detailsModal .modal-body');
        modalBody.innerHTML = `
            <div class="d-flex flex-row justify-content-center align-items-start" style="display: flex; flex-direction: row; align-items: start; justify-content: center;">
                <img src="${data.movie_img}" alt="Photo" style="margin-bottom: 20px; width: 410px; height: 600px">
                <table class="table table-bordered table-responsive ms-3" style="border-color: #e6ded3; width: 400px; height: 600px">
                    <tbody>
                        <tr>
                            <th scope="row" style="background-color: rgba(255, 255, 255, 0.3)";>Movie ID</th>
                            <td style="background-color: rgba(255, 255, 255, 0.3)">${data.movie_id}</td>
                        </tr>
                        <tr>
                            <th scope="row">Title</th>
                            <td>${data.title}</td>
                        </tr>
                        <tr>
                            <th scope="row" style="background-color: rgba(255, 255, 255, 0.3);">Image Name</th>
                            <td style="background-color: rgba(255, 255, 255, 0.3)">${data.poster_img}</td>
                        </tr>
                        <tr>
                            <th scope="row">Description</th>
                            <td>${data.movie_description}</td>
                        </tr>
                        <tr>
                            <th scope="row" style="background-color: rgba(255, 255, 255, 0.3)">Rating</th>
                            <td style="background-color: rgba(255, 255, 255, 0.3)">${data.movie_rating}</td>
                        </tr>
                        <tr>
                            <th scope="row">Movie Type</th>
                            <td>${data.movie_type}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
        var myModal = new bootstrap.Modal(document.getElementById('detailsModal'));
        myModal.show();
    }

    function exportCsv() {
        window.location.href = 'booking_movie_export-csv-api.php';
    }

    // bar_description內容展開縮放
    function toggleDescription(button) {
    var container = button.previousElementSibling; // 取得描述內容的容器
    container.style.maxHeight = container.style.maxHeight === '3em' ? 'none' : '3em'; // 切換高度
    button.innerText = container.style.maxHeight === '3em' ? '...more' : 'Hide'; // 切換按鈕內容
    }

</script>
<?php include '../parts/html-foot.php' ?>

