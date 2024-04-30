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

$t_sql = "SELECT COUNT(1) FROM admin_user";
$t_stmt = $pdo->query($t_sql);
$row = $t_stmt->fetch(PDO::FETCH_NUM);
// $row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

// print_r($row); 
// exit; #直接離開程式
$totalRows = $row[0]; # 取得總筆數
$totalPages = 0; # 預設值
$rows = []; # 預設值

// 假設默認排序是按 user_id 升序
$sortColumn = $_GET['sort'] ?? 'admin_user_id';
$order = $_GET['order'] ?? 'ASC';
// 確保只允許特定的列名和排序方向
$allowedSortColumns = ['admin_user_id', 'admin_account', 'admin_password_hash', 'admin_email', 'admin_permission'];
$allowedOrder = ['ASC', 'DESC'];
$sortColumn = in_array($sortColumn, $allowedSortColumns) ? $sortColumn : 'admin_user_id';
$order = in_array(strtoupper($order), $allowedOrder) ? strtoupper($order) : 'ASC';

if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);

    if ($page > $totalPages) {
        // redirect
        header('Location: ?page=' . $totalPages);
        exit;
    }

    $sql = sprintf("SELECT * FROM admin_user ORDER BY  %s %s
                    LIMIT %s, %s", $sortColumn, $order, ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();

    foreach ($rows as &$row) {
        $row['base64Image'] = base64_encode($row['avatar_img']);
    }
    unset($row); // 避免後續影響
}
?>

<?php include '../parts/html-head.php' ?>
<style>
    .modal-sm {
        max-width: 300px; /* 設定最大寬度 */
    }

    .modal-sm .modal-content {
        height: 300px; /* 設定內容高度 */
        overflow-y: auto; /* 如果內容高於模態框高度，添加滾動條 */
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
            <h5>Account Center</h5>
            <!-- add breadcrumb start -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="member_admin_user_list.php" class="text-decoration-none">Account Center</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Admin User</li>
                </ol>
            </nav>
            <!-- add breadcrumb end -->


            <div class="d-flex position-relative" style="overflow:auto;">
                <!-- pagination -->
                <?php include '../parts/pagination.php' ?>
                <!-- pagination -->
                <!-- add button start -->
                <div class="col-auto relative-absolute end-100">
                    <a href="member_admin_user_add.php" class="btn btn-primary mb-3"><i class="fa-solid fa-plus"></i></a>
                </div>
                <!-- add end start -->
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-primary me-5 ">
                    <thead>
                        <tr>
                            <th><i class="fa-solid fa-trash-can"></i></th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>admin_user_id</span>
                                    <a href="?sort=admin_user_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>

                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>admin_name</span>
                                    <a href="?sort=admin_account&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>

                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>avatar</span>
                                </div>
                            </th>

                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>admin_password_hash</span>
                                    <a href="?sort=admin_password_hash&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>admin_email</span>
                                    <a href="?sort=admin_email&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                                        <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                                    </a>
                                </div>
                            </th>
                            <th>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span>admin_status</span>
                                    <a href="?sort=admin_permission&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
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
                                    <a href="javascript: delete_one(<?= $row['admin_user_id'] ?>)">
                                        <i class="fa-solid fa-trash-can "></i>
                                    </a>
                                </td>
                                <td><?= $row['admin_user_id'] ?></td>
                                <td><?= $row['admin_account'] ?></td>
                                <td class="text-center">
                                    <img class="rounded-circle" style="width: 30px;height:30px; cursor: pointer;" onclick="showImageModal(this.src)" src="<?= ($row['base64Image'] ? 'data:image/jpeg;base64,' . $row['base64Image'] : $row['google_avatar_url']) ?>">
                                </td>
                                <td>
                                    <span class="all-content">
                                        <?= $row['admin_password_hash'] ?>
                                    </span>
                                </td>
                                <td><?= $row['admin_email'] ?></td>
                                <td class="text-center"><?= ($row['admin_permission'] == 1) ? '<i class="fa-solid fa-user-check bg-success text-light rounded-circle p-1" style="width=30px;height:30px;line-height:20px;"></i>' : '<i class="fa-solid fa-user-xmark bg-danger text-light rounded-circle" style="line-height:20px;padding: 4px 4px;"></i>'; ?></td>
                                <!-- strip_tags -->
                                <!-- 避免 XSS 攻擊問題 -->
                                <td>
                                    <a href="member_admin_user_edit.php?admin_user_id=<?= $row['admin_user_id'] ?>">
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

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-sm modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Image View</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="display: flex; justify-content: center; align-items: center; height: 80vh;">
                <img id="modalImage" src="" style="max-height: 100%; max-width: 100%;width:200px;" alt="Preview" />
            </div>
        </div>
    </div>
</div>
<!-- Image Modal -->


<?php include '../parts/scripts.php' ?>
<script>
    function delete_one(admin_user_id) {
        if (confirm(`Do you want to delete the data with the ${admin_user_id} ?`)) {
            location.href = `member_admin_user_delete.php?admin_user_id=${admin_user_id}`
        }
    }

    function updateTable(data) {
        const tableBody = document.querySelector('table tbody');
        tableBody.innerHTML = '';

        data.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${row.admin_user_id}</td>
                <td>${row.admin_account}</td>
                <td class="text-center"><img class="rounded-circle" style="width: 30px;height:30px;" src="<?= $row['google_avatar_url'] ?>" ></td>
                <td><?= $row['admin_password_hash'] ?></td>
                <td><?= $row['admin_email'] ?></td>
                <td class="text-center"><?= ($row['admin_permission'] == 1) ? '<i class="fa-solid fa-user-check bg-success text-light rounded-circle p-1" style="width=30px;height:30px;line-height:20px;"></i>' : '<i class="fa-solid fa-user-xmark bg-danger text-light rounded-circle" style="line-height:20px;padding: 4px 4px;"></i>'; ?></td>
            `;
            tableBody.appendChild(tr);
        });
    }

    //read more...
    document.addEventListener('DOMContentLoaded', function() {
        // 文字數量顯示限制
        let maxLength = 1;
        let allContents = document.querySelectorAll('.all-content');

        function createExpand(originalContent) {
            return function expandContent() {
                this.innerHTML = originalContent;
                this.removeEventListener('click', expandContent);
            };
        }

        //處理可展開的內容
        function handleContent(content) {
            let originalContent = content.innerHTML;

            //檢查文字數量
            if (originalContent.length > maxLength) {
                let lessContent = originalContent.substring(0, maxLength - 1) + '<span class="ellipsis" style="color: #003e52; font-weight: bold; font-size: 12px"> Expand</span>';
                content.innerHTML = lessContent;

                // 點擊read more...
                content.addEventListener('click', createExpand(originalContent));
            }
        }

        allContents.forEach(handleContent);
    });

    function showImageModal(src) {
        // 設置模態框中的圖片源
        document.getElementById('modalImage').src = src;
        document.querySelector('.modal-dialog').style.marginTop = '200px';
        // 顯示模態框
        var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
        myModal.show();
    }

    // function showImageModal(src) {
    //     // 設置模態框中的圖片源
    //     document.getElementById('modalImage').src = src;

    //     // 選擇模態框並根據需要設置大小類
    //     var modalDialog = document.querySelector('.modal-dialog');
    //     modalDialog.classList.remove('modal-dialog-lg', 'modal-dialog-sm'); // 先移除可能存在的其他大小類

    //     // 根據圖片大小來判斷添加哪個大小類
    //     if (document.getElementById('modalImage').naturalWidth > 400) {
    //         modalDialog.classList.add('modal-dialog-lg');
    //     } else {
    //         modalDialog.classList.add('modal-dialog-sm');
    //     }

    //     // 顯示模態框
    //     var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
    //     myModal.show();
    // }
</script>
<?php include '../parts/html-foot.php' ?>