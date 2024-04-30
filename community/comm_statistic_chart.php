<?php include '../parts/html-head.php' ?>
<?php
require '../parts/db_connect.php';

// 撰寫一條SQL查詢語句來計算總行數
$sql = "SELECT COUNT(*) AS total FROM member_user";

// 使用PDO執行查詢
$stmt = $pdo->query($sql);

// 獲取查詢結果
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// 從查詢結果中提取總行數
$totalRows = $row['total'];

// 計算總頁數，假設每頁顯示15條資料
$perPage = 15;
$totalPages = ceil($totalRows / $perPage);

// 從GET參數獲取當前頁碼，如果未設置，則默認為第1頁
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// 確保當前頁碼不小於1且不大於總頁數
$page = max($page, 1);
$page = min($page, $totalPages);

// 計算查詢的起始點
$offset = ($page - 1) * $perPage;

$sql_bar_chart = "SELECT mu.username, mu.user_id, 
        COUNT(DISTINCT cp.post_id) AS posts_count, 
        COUNT(DISTINCT cl.comm_likes_id) AS likes_count, 
        COUNT(DISTINCT cc.comm_comment_id) AS comments_count, 
        COUNT(DISTINCT cs.comm_saved_id) AS saved_count 
        FROM member_user AS mu 
        LEFT JOIN comm_post AS cp ON mu.user_id = cp.user_id 
        LEFT JOIN comm_likes AS cl ON mu.user_id = cl.user_id 
        LEFT JOIN comm_comment AS cc ON mu.user_id = cc.user_id 
        LEFT JOIN comm_saved AS cs ON mu.user_id = cs.user_id 
        GROUP BY mu.user_id, mu.username
        ORDER BY mu.user_id ASC
        LIMIT $perPage OFFSET $offset";
$stmt = $pdo->query($sql_bar_chart);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <h5>Community</h5>
            <!-- add breadcrumb start -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item "><a href="/Taipei_Date/_index.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="comm_statistic_list.php" class="text-decoration-none">Community</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Statistic</li>
                </ol>
            </nav>
            <!-- add breadcrumb end -->

            <div class="row align-items-center">
                <!-- pagination -->
                <?php include '../parts/pagination.php' ?>
                <!-- pagination -->

                <div class="col-auto d-flex align-items-center">
                    <!-- Pie chart button  -->
                    <a href="comm_statistic_piechart.php" class="btn btn-primary mb-3 me-2"><i class="bi bi-pie-chart-fill"></i></a>
                    <!-- Pie chart button  -->

                    <!-- table button start -->
                    <a href="comm_statistic_list.php" class="btn btn-primary mb-3 me-4"><i class="bi bi-table"></i></a>
                    <!-- table end start -->

                    <!-- Return button  -->
                    <a href="comm_post_list.php" class="btn btn-primary mb-3"><i class="fa-solid fa-rotate-left"></i></a>
                    <!-- Return button  -->
                </div>
            </div>

            <!-- Bar chart -->
            <div style="width: 85%;">
                <canvas id="myBarChart"></canvas>
            </div>
            <!-- 顯示數據 -->
            <?php foreach ($rows as $row): ?>
                <!-- 顯示每項數據 -->
            <?php endforeach; ?>
            <!-- Bar chart -->

        </div>
    </div>
</div>

<?php include '../parts/scripts.php' ?>
<script>
    // Bar Chart
    const barData = {
        labels: <?php echo json_encode(array_column($rows, 'user_id')); ?>,
        datasets: [{
            label: 'Post Count',
            data: <?php echo json_encode(array_column($rows, 'posts_count')); ?>,
            backgroundColor: 'rgba(255, 99, 132, 0.5)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        },
        {
            label: 'Like Count',
            data: <?php echo json_encode(array_column($rows, 'likes_count')); ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        },
        {
            label: 'Comment Count',
            data: <?php echo json_encode(array_column($rows, 'comments_count')); ?>,
            backgroundColor: 'rgba(255, 206, 86, 0.5)',
            borderColor: 'rgba(255, 206, 86, 1)',
            borderWidth: 1
        },
        {
            label: 'Saved Count',
            data: <?php echo json_encode(array_column($rows, 'saved_count')); ?>,
            backgroundColor: 'rgba(75, 192, 192, 0.5)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    };

    const barConfig = {
        type: 'bar',
        data: barData,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: { // Col
                        display: true,
                        text: 'Amount'
                    }
                },
                x: {
                    title: { // Row
                        display: true,
                        text: 'User ID'
                    }
                }
            }
        }
    };

    const myBarChart = new Chart(
        document.getElementById('myBarChart'),
        barConfig
    );
</script>
<?php include '../parts/html-foot.php' ?>
