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

    $t_sql = "SELECT COUNT(1) FROM bar_pic";
    $t_stmt = $pdo->query($t_sql);
    $row = $t_stmt->fetch(PDO::FETCH_NUM);
    // $row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

    // print_r($row); 
    // exit; #直接離開程式
    $totalRows = $row[0]; # 取得總筆數
    $totalPages = 0; # 預設值
    $rows = []; # 預設值

    // 假設默認排序是按 bar_pic 升序
    $sortColumn = $_GET['sort'] ?? 'bar_pic_id';
    $order = $_GET['order'] ?? 'ASC';

    // 確保只允許特定的列名和排序方向
    $allowedSortColumns = ['bar_pic_id', 'bar_pic_name', 'bar_id'];
    $allowedOrder = ['ASC', 'DESC'];
    $sortColumn = in_array($sortColumn, $allowedSortColumns) ? $sortColumn : 'bar_pic';
    $order = in_array(strtoupper($order), $allowedOrder) ? strtoupper($order) : 'ASC';

    if($totalRows > 0) {
        $totalPages = ceil($totalRows / $perPage);

        if($page > $totalPages) {
            // redirect
            header('Location: ?page='. $totalPages);
            exit;
        }

        // $sql = sprintf("SELECT * FROM bar_pic ORDER BY bar_pic_id DESC
        //             LIMIT %s, %s", ($page-1)*$perPage, $perPage);
        // 修改 SQL 查詢以包含排序參數
        // $sql = sprintf("SELECT * FROM bar_pic ORDER BY %s %s LIMIT %s, %s", $sortColumn, $order, ($page-1)*$perPage, $perPage);

    // 修改 SQL 查詢以包含 JOIN
    $sql = sprintf(
    "SELECT bar_pic.*, bars.bar_name FROM bar_pic
    LEFT JOIN bars ON bar_pic.bar_id = bars.bar_id
    ORDER BY %s %s LIMIT %s, %s",
    $sortColumn,
    $order,
    ($page - 1) * $perPage,
    $perPage
    );
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();

    // 將每一行的圖片數據轉換為 base64
    foreach ($rows as $i => $row) {
    if (isset($row['bar_img'])) { // 檢查是否存在
        $rows[$i]['bar_img'] = 'data:image/jpeg;base64,' . base64_encode($row['bar_img']);
    } else {
        // 設置一個預設值或進行錯誤處理
        $rows[$i]['bar_img'] = 'http://localhost/path/to/default/image.jpg';
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

        <div class="col-12 col-md-8 col-lg-10" >
        <h5>Bar</h5>
        <!-- add breadcrumb start -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="bar_pic_list.php" class="text-decoration-none">Bar</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pic</li>
            </ol>
        </nav>
        <!-- add breadcrumb end -->

        <div class="row align-items-center">
        <!-- pagination -->
        <?php include '../parts/pagination.php'?>
        <!-- pagination -->
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-primary">
            <thead>
                <tr>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>bar_pic_id</span>
                        <a href="?sort=bar_pic_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a>
                        </div>
                    </th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>bar_pic_name</span>
                        <a href="?sort=bar_pic_name&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a>
                        </div>
                    </th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>bar_name</span>
                        <a href="?sort=bar_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a>
                        </div>
                    </th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($rows as $row): ?>
                <tr>
                    <td><?= $row['bar_pic_id'] ?></td>
                    <td><?= $row['bar_pic_name'] ?></td>
                    <!-- <td><?= $row['bar_id'] ?></td> -->
                    <td><?= htmlspecialchars($row['bar_name']) ?></td>
                    <td>
                        <img src="<?= $row['bar_img'] ?>" alt="Photo" style="width: auto; height: 100px; cursor: pointer;" onclick="showImageModal(this.src)">
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

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
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

<?php include '../parts/scripts.php' ?>
<script>
    // function delete_one(bar_pic_id){
    //     if(confirm(`Do you want to delete the data with the ID ${bar_pic_id} ?`)){
    //         location.href = `bar_pic_delete.php?bar_pic_id=${bar_pic_id}`
    //     }
    // }
     // 新增圖片
    function showImageModal(src) {
        // 設置模態框中的圖片源
        document.getElementById('modalImage').src = src;
        // 顯示模態框
        var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
        myModal.show();
    }
</script>
<?php include '../parts/html-foot.php' ?>

