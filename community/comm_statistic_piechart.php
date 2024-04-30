<?php include '../parts/html-head.php' ?>
<?php
require '../parts/db_connect.php'; // 引入資料庫連線檔案

// 設定每頁顯示的資料數量
$perPage = 15;

// 計算總頁數（假設總共有300筆資料）
$totalRows = 300; // 這個值實際上應該透過查詢資料庫獲得
$totalPages = ceil($totalRows / $perPage);

// 從GET參數獲取當前頁碼，如果未設置，則默認為第1頁
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
// 確保當前頁碼不小於1且不大於總頁數
$page = max($page, 1);
$page = min($page, $totalPages);

// 計算查詢的起始點
$offset = ($page - 1) * $perPage;

$sql_pie_chart = "SELECT 
    SUM(CASE WHEN context LIKE '%#電影%' THEN 1 ELSE 0 END) AS movie_count,
    SUM(CASE WHEN context LIKE '%#酒吧%' THEN 1 ELSE 0 END) AS bar_count,
    SUM(CASE WHEN context LIKE '%#約會%' THEN 1 ELSE 0 END) AS date_count
    FROM comm_post";

$stmt = $pdo->query($sql_pie_chart);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$data = [
    'labels' => ['Movie', 'Bar', 'Dating'],
    'datasets' => [
        [
            'data' => [$row['movie_count'], $row['bar_count'], $row['date_count']],
            'backgroundColor' => ['rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(255, 206, 86, 0.5)'],
            'borderColor' => ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)'],
            'borderWidth' => 1
        ]
    ]
];
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
                    <a href="comm_statistic_chart.php" class="btn btn-primary mb-3 me-2"><i class="bi bi-bar-chart-fill"></i></a>
                    <!-- Pie chart button  -->

                    <!-- table button start -->
                    <a href="comm_statistic_list.php" class="btn btn-primary mb-3 me-4"><i class="bi bi-table"></i></a>
                    <!-- table end start -->

                    <!-- Return button  -->
                    <a href="comm_post_list.php" class="btn btn-primary mb-3"><i class="fa-solid fa-rotate-left"></i></a>
                    <!-- Return button  -->
                </div>
            </div>

            <!-- Pie chart -->
            <div class="row justify-content-center">
                <div style="width: 45%;">
                    <canvas id="myPieChart"></canvas>
                </div>
            </div>
            <!-- Pie chart -->

        </div>
    </div>
</div>

<?php include '../parts/scripts.php' ?>
<script>
    // Pie Chart
    const pieData = <?php echo json_encode($data); ?>;
    const pieConfig = {
        type: 'pie',
        data: pieData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: ''
                }
            }
        }
    };

    const myPieChart = new Chart(
        document.getElementById('myPieChart'),
        pieConfig
    );
</script>
<?php include '../parts/html-foot.php' ?>
