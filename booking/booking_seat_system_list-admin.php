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

$t_sql = "SELECT COUNT(1) FROM booking_seat_system";
$t_stmt = $pdo->query($t_sql);
$row = $t_stmt->fetch(PDO::FETCH_NUM);
// $row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

// print_r($row); 
// exit; #直接離開程式
$totalRows = $row[0]; # 取得總筆數
$totalPages = 0; # 預設值
$rows = []; # 預設值

$sort = $_GET['sort'] ?? 'asc';
$sortToggle = $sort === 'asc' ? 'desc' : 'asc';
$sortSql = $sort === 'asc' ? 'ASC' : 'DESC';

if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);

    if ($page > $totalPages) {
        // redirect
        header('Location: ?page=' . $totalPages);
        exit;
    }

    // $sql = sprintf("SELECT * FROM comm_post ORDER BY post_id DESC
    //             LIMIT %s, %s", ($page-1)*$perPage, $perPage);
    $sql = sprintf("SELECT * FROM booking_seat_system ORDER BY seat_id %s LIMIT %s, %s", $sortSql, ($page - 1) * $perPage, $perPage);
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
            <h5>Booking System</h5>
            <!-- add breadcrumb start -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="booking_seat_system_list.php" class="text-decoration-none">Booking System</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Seat System</li>
                </ol>
            </nav>
            <!-- add breadcrumb end -->

            <div class="row align-items-center">
                <!-- pagination -->
                <?php include '../parts/pagination.php' ?>
                <!-- pagination -->

                <!-- add button start -->
                <div class="col-auto">
                    <a href="booking_seat_system_add.php" class="btn btn-primary mb-3"><i class="fa-solid fa-plus"></i></a>
                </div>
                <!-- add end start -->
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-primary">
                    <thead>
                        <tr>
                            <th><i class="fa-solid fa-trash-can"></i></th>
                            <!-- <th>post_id</th> -->
                            <th>seat_id
                                <a href="?sort=<?= $sortToggle ?>&page=<?= $page ?>"><i class="bi bi-filter"></i></a>
                            </th>
                            <th>seat_uuid</th>
                            <th>seat_status</th>
                            <th>movie_id</th>
                            <th><i class="fa-solid fa-file-pen"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row) : ?>
                            <tr>
                                <td>
                                    <a href="javascript: delete_one(<?= $row['seat_id'] ?>)">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </td>
                                <td><?= $row['seat_id'] ?></td>
                                <td><?= $row['seat_uuid'] ?></td>
                                <td><?= $row['seat_status'] ?></td>
                                <td><?= $row['movie_id'] ?></td>
                                <!-- strip_tags -->
                                <!-- 避免 XSS 攻擊問題 -->
                                <td>
                                    <a href="booking_seat_system_edit.php?seat_id=<?= $row['seat_id'] ?>">
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
    function delete_one(seat_id) {
        if (confirm(`Do you want to delete the data with the ID ${seat_id} ?`)) {
            location.href = `booking_seat_system_delete.php?seat_id=${seat_id}`
        }
    }
</script>
<?php include '../parts/html-foot.php' ?>