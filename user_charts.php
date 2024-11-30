<?php
// Include the database connection file
include 'connection.php';

// Query data for the line chart
$sqlLineChart = "SELECT DATE_FORMAT(RegisDate, '%Y-%m') AS Month, COUNT(*) AS NumberOfAccounts
                 FROM user
                 WHERE Role = 1
                 GROUP BY DATE_FORMAT(RegisDate, '%Y-%m')
                 ORDER BY DATE_FORMAT(RegisDate, '%Y-%m')";

$resultLineChart = $conn->query($sqlLineChart);

// Initialize array to store data for the line chart
$dataLineChart = [];

if ($resultLineChart->num_rows > 0) {
    while ($row = $resultLineChart->fetch_assoc()) {
        $dataLineChart[] = [$row['Month'], (int)$row['NumberOfAccounts']];
    }
}

// Query data for the pie chart
$sqlPieChart = "SELECT r.Name AS RoleName, COUNT(u.id) AS NumberOfAccounts
                FROM user u
                JOIN role r ON u.Role = r.id
                GROUP BY r.Name";

$resultPieChart = $conn->query($sqlPieChart);

// Initialize array to store data for the pie chart
$dataPieChart = [];

if ($resultPieChart->num_rows > 0) {
    while ($row = $resultPieChart->fetch_assoc()) {
        $dataPieChart[] = [$row['RoleName'], (int)$row['NumberOfAccounts']];
    }
}

// Query data for top 10 users by number of completed orders
$sqlTopCompletedOrders = "SELECT u.id AS UserId, CONCAT(u.FirstName, ' ', u.LastName) AS UserName, COUNT(o.id) AS NumberOfCompletedOrders
                          FROM user u
                          JOIN `order` o ON u.id = o.userID
                          WHERE o.Status = 'complete'
                          GROUP BY u.id
                          ORDER BY NumberOfCompletedOrders DESC
                          LIMIT 10";

$resultTopCompletedOrders = $conn->query($sqlTopCompletedOrders);

// Initialize array to store data for the top completed orders chart
$dataTopCompletedOrders = [];

if ($resultTopCompletedOrders->num_rows > 0) {
    while ($row = $resultTopCompletedOrders->fetch_assoc()) {
        $dataTopCompletedOrders[] = [$row['UserName'], (int)$row['NumberOfCompletedOrders']];
    }
}

// Query data for top 10 users by total spent
$sqlTopTotalSpent = "SELECT u.id AS UserId, CONCAT(u.FirstName, ' ', u.LastName) AS UserName, SUM(o.Total) AS TotalSpent
                     FROM user u
                     JOIN `order` o ON u.id = o.userID
                     WHERE o.Status = 'complete'
                     GROUP BY u.id
                     ORDER BY TotalSpent DESC
                     LIMIT 10";

$resultTopTotalSpent = $conn->query($sqlTopTotalSpent);

// Initialize array to store data for the top total spent chart
$dataTopTotalSpent = [];

if ($resultTopTotalSpent->num_rows > 0) {
    while ($row = $resultTopTotalSpent->fetch_assoc()) {
        $dataTopTotalSpent[] = [$row['UserName'], (float)$row['TotalSpent']];
    }
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Load the Visualization API and the corechart package.
        google.charts.load('current', {'packages':['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawCharts);

        // Callback that creates and populates a data table,
        // then draws the charts.
        function drawCharts() {
            // Create the data table for the line chart
            var lineChartData = new google.visualization.DataTable();
            lineChartData.addColumn('string', 'Month');
            lineChartData.addColumn('number', 'Number of Accounts');

            // Add data rows for the line chart from PHP
            lineChartData.addRows(<?php echo json_encode($dataLineChart); ?>);

            // Set options for the line chart
            var lineChartOptions = {
                title: 'Accounts Created by Month',
                hAxis: {title: 'Month'},
                vAxis: {title: 'Number of Accounts'},
                legend: 'none'
            };

            // Instantiate and draw the line chart.
            var lineChart = new google.visualization.LineChart(document.getElementById('line_chart_div'));
            lineChart.draw(lineChartData, lineChartOptions);

            // Create the data table for the pie chart
            var pieChartData = new google.visualization.DataTable();
            pieChartData.addColumn('string', 'Role');
            pieChartData.addColumn('number', 'Number of Accounts');

            // Add data rows for the pie chart from PHP
            pieChartData.addRows(<?php echo json_encode($dataPieChart); ?>);

            // Set options for the pie chart 
            var pieChartOptions = {
                title: 'Number of Accounts by Role',
                pieHole: 0.4
            };

            // Instantiate and draw the pie chart.
            var pieChart = new google.visualization.PieChart(document.getElementById('pie_chart_div'));
            pieChart.draw(pieChartData, pieChartOptions);

            // Create the data table for top 10 users by number of completed orders
            var topCompletedOrdersData = new google.visualization.DataTable();
            topCompletedOrdersData.addColumn('string', 'User');
            topCompletedOrdersData.addColumn('number', 'Number of Completed Orders');

            // Add data rows for top 10 completed orders from PHP
            topCompletedOrdersData.addRows(<?php echo json_encode($dataTopCompletedOrders); ?>);

            // Set options for the top 10 completed orders chart
            var topCompletedOrdersOptions = {
                title: 'Top 10 Users by Number of Completed Orders',
                hAxis: {
                    title: 'User',
                    slantedText: true,
                    slantedTextAngle: 45
                },
                vAxis: {title: 'Number of Completed Orders'},
                legend: 'none',
                chartArea: {
                    width: '75%',
                    height: '50%'
                }
            };

            // Instantiate and draw the top 10 completed orders chart.
            var topCompletedOrdersChart = new google.visualization.BarChart(document.getElementById('top_completed_orders_chart_div'));
            topCompletedOrdersChart.draw(topCompletedOrdersData, topCompletedOrdersOptions);

            // Create the data table for top 10 users by total spent
            var topTotalSpentData = new google.visualization.DataTable();
            topTotalSpentData.addColumn('string', 'User');
            topTotalSpentData.addColumn('number', 'Total Spent');

            // Add data rows for top 10 total spent from PHP
            topTotalSpentData.addRows(<?php echo json_encode($dataTopTotalSpent); ?>);

            // Set options for the top 10 total spent chart
            var topTotalSpentOptions = {
                title: 'Top 10 Users by Total Spent',
                hAxis: {
                    title: 'User',
                    slantedText: true,
                    slantedTextAngle: 45
                },
                vAxis: {title: 'Total Spent'},
                legend: 'none',
                chartArea: {
                    width: '75%',
                    height: '50%'
                }
            };

            // Instantiate and draw the top 10 total spent chart.
            var topTotalSpentChart = new google.visualization.BarChart(document.getElementById('top_total_spent_chart_div'));
            topTotalSpentChart.draw(topTotalSpentData, topTotalSpentOptions);
        }
    </script>
    <style>
        #charts-container {
            text-align: center;
        }
        
        .chart-container {
            display: inline-block;
            margin: 20px;
            vertical-align: top;
        }
        
        .chart-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div id="charts-container">
        <h1>Users</h1>
        <div id="line_chart_div" style="padding:10px; width: 900px; height: 500px; display: inline-block;"></div>
        <div id="pie_chart_div" style="padding:10px; width: 600px; height: 500px; display: inline-block;"></div>
        <div class="chart-container">
            <div class="chart-title">Top 10 Users by Number of Completed Orders</div>
            <div id="top_completed_orders_chart_div" style="width: 900px; height: 500px;"></div>
        </div>
        <div class="chart-container">
            <div class="chart-title">Top 10 Users by Total Spent</div>
            <div id="top_total_spent_chart_div" style="width: 900px; height: 500px;"></div>
        </div>
    </div>
</body>
</html>
