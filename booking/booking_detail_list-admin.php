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

    $t_sql = "SELECT COUNT(1) FROM booking_detail";
    $t_stmt = $pdo->query($t_sql);
    $row = $t_stmt->fetch(PDO::FETCH_NUM);
    // $row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

    // print_r($row); 
    // exit; #直接離開程式
    $totalRows = $row[0]; # 取得總筆數
    $totalPages = 0; # 預設值
    $rows = []; # 預設值



    // 假設默認排序是按 booking_detail_id 升序
    $sortColumn = $_GET['sort'] ?? 'booking_detail_id';
    $order = $_GET['order'] ?? 'ASC';

    // 確保只允許特定的列名和排序方向
    $allowedSortColumns = ['booking_detail_id', 'booking_id', 'seat_id', 'booking_type'];
    $allowedOrder = ['ASC', 'DESC'];
    $sortColumn = in_array($sortColumn, $allowedSortColumns) ? $sortColumn : 'booking_detail_id';
    $order = in_array(strtoupper($order), $allowedOrder) ? strtoupper($order) : 'ASC'; 



    if($totalRows > 0) {
        $totalPages = ceil($totalRows / $perPage);

        if($page > $totalPages) {
            // redirect
            header('Location: ?page='. $totalPages);
            exit;
        }

         // 修改 SQL 查詢以包含排序和JOIN參數
         $sql = sprintf("SELECT p.*, u.booking_id FROM booking_detail AS p
         JOIN booking_system AS u ON p.booking_id = u.booking_id
         ORDER BY p.%s %s LIMIT %s, %s", 
         $sortColumn, $order, ($page-1)*$perPage, $perPage);
         
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
        <h5>Booking System</h5>
        <!-- add breadcrumb start -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="booking_detail_list.php" class="text-decoration-none">Booking System</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
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
                    <input type="text" id="search-input" class="form-control mb-3" placeholder="Search">
                    <button type="button" class="btn btn-primary mb-3 me-2" onclick="searchData()">
                    <i class="bi bi-search"></i>
                    </button>
                    <!-- Return button  -->
                    <div class="col-auto">
                        <a href="booking_detail_list.php" class="btn btn-primary mb-3">
                        <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    </div>
                    <!-- Return button  -->
                </div>
            </div>
            <!-- Search bar -->



        

            <!-- add button start -->
            <div class="col-auto">
                <a href="booking_detail_add.php" class="btn btn-primary mb-3"><i class="fa-solid fa-plus"></i></a>
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
                        <span>booking_detail_id</span>
                        <a href="?sort=booking_detail_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a>
                        </div>
                    </th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>booking_id</span>
                        <a href="?sort=booking_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i> 
                        </a>
                        </div>
                    </th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>seat_id</span>
                        <a href="?sort=seat_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a>
                        </div>
                    </th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>booking_type</span>
                        <a href="?sort=booking_type&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i> 
                        </a>
                        </div>
                    </th>
                    

                    <!-- <th>booking_detail_id</th>
                    <th>booking_id</th>
                    <th>seat_id</th>
                    <th>booking_type</th> -->
                    <th><i class="fa-solid fa-file-pen"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($rows as $row): ?>
                <tr>
                    <td>
                        <a href="javascript: delete_one(<?= $row['booking_detail_id'] ?>)">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </td>
                    <td><?= $row['booking_detail_id'] ?></td>
                    <td><?= $row['booking_id'] ?></td>
                    <td><?= $row['seat_id'] ?></td>
                    <td><?= $row['booking_type'] ?></td>
                    <td>
                        <a href="booking_detail_edit.php?booking_detail_id=<?= $row['booking_detail_id'] ?>">
                            <i class="fa-solid fa-file-pen"></i>
                        </a>
                    </td>
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
    let currentSearchCategory = ''; // 當前選擇的搜尋類別

    function setSearchCategory(category) {
        currentSearchCategory = category;
        document.getElementById('searchCategoryDropdown').innerText = category;
    }

    function delete_one(booking_detail_id){
        if(confirm(`是否要刪除編號為 ${booking_detail_id} 的資料?`)){
            location.href = `booking_detail_delete.php?booking_detail_id=${booking_detail_id}`
        }
    }

    function searchData() {
        const keyword = document.getElementById('search-input').value;

        fetch(`booking_detail_search-api.php?keyword=${keyword}`)
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
                <td>
                    <a href="javascript: delete_one(<?= $row['booking_detail_id'] ?>)">
                        <i class="fa-solid fa-trash-can"></i>
                    </a>
                </td>
                <td>${row.booking_detail_id}</td>
                <td>${row.booking_id}</td>
                <td>${row.seat_id}</td>
                <td>${row.booking_type}</td>
                <td>
                    <a href="booking_detail_edit.php?booking_detail_id=<?= $row['booking_detail_id'] ?>">
                        <i class="fa-solid fa-file-pen"></i>
                    </a>
                </td>
                <!-- 其他欄位 -->
            `;
            tableBody.appendChild(tr);
        });
    }
</script>
<?php include '../parts/html-foot.php' ?>