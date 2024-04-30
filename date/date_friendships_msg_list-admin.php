

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

$t_sql = "SELECT COUNT(1) FROM friendships_message";
$t_stmt = $pdo->query($t_sql);
$row = $t_stmt->fetch(PDO::FETCH_NUM);
$totalRows = $row[0];

$totalPages = 0;
$rows = [];

$sortColumn = $_GET['sort'] ?? 'message_id';
$order = $_GET['order'] ?? 'ASC';


$allowedSortColumns = ['message_id', 'friendship_id', 'sender_id', 'receiver_id', 'content', 'sended_at'];
$allowedOrder = ['ASC', 'DESC'];

$sortColumn = in_array($sortColumn, $allowedSortColumns) ? $sortColumn : 'message_id';
$order = in_array(strtoupper($order), $allowedOrder) ? strtoupper($order) : 'ASC';

if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);

    if ($page > $totalPages) {
        // redirect
        header('Location: ?page=' . $totalPages);
        exit;
    }

    $offset = ($page - 1) * $perPage;

    $sql = sprintf(
        "SELECT
            fm.friendship_id,
            sender.username AS sender_id,
            receiver.username AS receiver_id,
            fm.message_id,
            fm.content,
            fm.sended_at
        FROM
            friendships_message fm
        LEFT JOIN
            member_user sender ON fm.sender_id = sender.user_id
        LEFT JOIN
            member_user receiver ON fm.receiver_id = receiver.user_id
        ORDER BY %s %s LIMIT %s, %s",
        $sortColumn,
        $order,
        $offset,
        $perPage
    );

    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}
?>

<?php include '../parts/html-head.php'?>
<style>
    .ellipsis:hover {
    cursor: pointer;
    text-decoration: underline; 
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
        <h5>Blind Date</h5>
        <!-- add breadcrumb start -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="date_friendships_msg_list.php" class="text-decoration-none">Blind Date</a></li>
                <li class="breadcrumb-item active" aria-current="page">Friendships Message</li>
            </ol>
        </nav>
        <!-- add breadcrumb end -->


        <div class="row align-items-center">
            <!-- pagination -->
            <?php include '../parts/pagination.php'?>
            <!-- pagination -->

            <!-- Range search start-->
            <div class="col-auto me-3">
                <div class="input-group">
                    <input type="date" id="search-start-time" class="form-control mb-3" placeholder="Search">
                    <input type="date" id="search-end-time" class="form-control mb-3" placeholder="Search">
                    <button type="button" class="btn btn-primary mb-3 me-2" onclick="searchTime()"><i class="bi bi-search"></i></button>
                    <!-- Return button  -->
                    <div class="col-auto">
                        <a href="date_friendships_msg_list.php" class="btn btn-primary mb-3"><i class="fa-solid fa-rotate-left"></i></a>
                    </div>
                    <!-- Return button  -->
                </div>
            </div>
            <!-- Range search end-->
            
            <!-- add button start -->
            <div class="col-auto">
                <a href="date_friendships_msg_add.php" class="btn btn-primary mb-3"><i class="fa-solid fa-plus"></i></a>
            </div>
            <!-- add end start -->

            <!-- Export CSV Button -->
            <div class="col-auto">
                <button type="button" class="btn btn-primary mb-3" onclick="exportCsv()"><i class="bi bi-file-earmark-arrow-down-fill"></i></button>
            </div>
            <!-- Export CSV Button -->
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-primary">
            <thead>
                <tr>
                    <th><i class="fa-solid fa-trash-can"></i></th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>message_id</span>
                        <a href="?sort=message_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a>
                        </div>
                    </th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>friendship_id</span>
                        <a href="?sort=friendship_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i> 
                        </a>
                        </div>
                    </th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>sender_id</span>
                        <!-- <a href="?sort=sender_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a> -->
                        </div>
                    </th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>receiver_id</span>
                        <!-- <a href="?sort=receiver_id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i> 
                        </a> -->
                        </div>
                    </th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>content</span>
                        <!-- <a href="?sort=content&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i> 
                        </a> -->
                        </div>
                    </th>
                    <th>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span>sended_at</span>
                        <a href="?sort=sended_at&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">
                            <i class="fa-solid fa-sort me-auto" style="color: #e6ded3"></i>
                        </a>
                        </div>
                    </th>
                    
                    <th><i class="fa-solid fa-file-pen"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($rows as $row): ?>
                <tr>
                    <td>
                        <a href="javascript: delete_one(<?= $row['message_id'] ?>)">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </td>
                    <td><?= $row['message_id'] ?></td>
                    <td><?= $row['friendship_id'] ?></td>
                    <td><?= $row['sender_id'] ?></td>
                    <td><?= $row['receiver_id'] ?></td>
                    <td>
                        <span class="all-content"><?= $row['content'] ?></span>
                    </td>
                    <td><?= $row['sended_at'] ?></td>
                    <!-- strip_tags -->
                    <!-- 避免 XSS 攻擊問題 -->
                    <td>
                        <a href="date_friendships_msg_edit.php?message_id=<?= $row['message_id'] ?>">
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
    function delete_one(message_id){
        if(confirm(`Do you want to delete the data with the ID ${message_id} ?`)){
            location.href = `date_friendships_msg_delete.php?message_id=${message_id}`
        }
    }

    //search range time
    function searchTime() {
    const startTime = document.getElementById('search-start-time').value;
    const endTime = document.getElementById('search-end-time').value;

    if (!startTime || !endTime) {
        console.error('Both start and end times are required');
        return;
    }

    // Use distinct parameter names for start_date and end_date
    const url = `date_friendships_msg_search-api.php?start_date=${startTime}&end_date=${endTime}`;

    fetch(url)
        .then(response => response.json())
        .then(result => {
            updateTable(result.data);
        })
        .catch(error => console.error('Error fetching data:', error));
}


    function updateTable(data) {
    const tableBody = document.querySelector('table tbody');
    tableBody.innerHTML = '';
    console.log('Result Data:', data);

    // Check if data is empty
    if (data.length === 0) {
        // Show noDataModal
        noDataModal.show();
        return;
    }

    data.forEach(row => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <a href="javascript: delete_one(${row.message_id})">
                    <i class="fa-solid fa-trash-can"></i>
                </a>
            </td>
            <td>${row.message_id}</td>
            <td>${row.friendship_id}</td>
            <td>${row.sender_id}</td>
            <td>${row.receiver_id}</td>
            <td>
                <span class="all-content">${row.content}</span>
            </td>
            <td>${row.sended_at}</td>
            <td>
                <a href="date_friendships_msg_edit.php?message_id=${row.message_id}">
                    <i class="fa-solid fa-file-pen"></i>
                </a>
            </td>
        `;
        tableBody.appendChild(tr);

        // 增加 read more 功能
        const contentSpan = tr.querySelector('.all-content');
        handleContent(contentSpan);
    });
}

function handleContent(content) {
    let originalContent = content.innerHTML;
    let maxLength = 15;

    // 檢查文字數量
    if (originalContent.length > maxLength) {
        let lessContent = originalContent.substring(0, maxLength - 1) + '<span class="ellipsis" style="color: #93939B; font-weight: bold; font-size: 12px; cursor: pointer;"> ...read more</span>';
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

//export CSV
function exportCsv() {
        window.location.href = 'date_friendships_msg_export-csv-api.php';
    }

const noDataModal = new bootstrap.Modal(document.getElementById('noDataModal'));
</script>

<?php include '../parts/html-foot.php' ?>
