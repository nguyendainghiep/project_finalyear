<?php
// Include the database connection file
include 'connection.php';

// Query data for the line chart (completed orders by month)
$sqlLineChart = "SELECT DATE_FORMAT(OrderDate, '%Y-%m') AS Month, COUNT(*) AS NumberOfOrders
                 FROM `order`
                 WHERE Status = 'complete'
                 GROUP BY DATE_FORMAT(OrderDate, '%Y-%m')
                 ORDER BY DATE_FORMAT(OrderDate, '%Y-%m')";

$resultLineChart = $conn->query($sqlLineChart);

// Initialize array to store data for the line chart
$dataLineChart = [];

if ($resultLineChart->num_rows > 0) {
    while ($row = $resultLineChart->fetch_assoc()) {
        $dataLineChart[] = [$row['Month'], (int)$row['NumberOfOrders']];
    }
}

// Query data for the pie chart (order status comparison)
$sqlPieChart = "SELECT Status, COUNT(*) AS NumberOfOrders
                FROM `order`
                WHERE Status IN ('pending', 'complete')
                GROUP BY Status";

$resultPieChart = $conn->query($sqlPieChart);

// Initialize array to store data for the pie chart
$dataPieChart = [];

if ($resultPieChart->num_rows > 0) {
    while ($row = $resultPieChart->fetch_assoc()) {
        $dataPieChart[] = [$row['Status'], (int)$row['NumberOfOrders']];
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
            lineChartData.addColumn('number', 'Number of Orders');

            // Add data rows for the line chart from PHP
            lineChartData.addRows(<?php echo json_encode($dataLineChart); ?>);

            // Set options for the line chart
            var lineChartOptions = {
                title: 'Completed Orders by Month',
                hAxis: {title: 'Month'},
                vAxis: {title: 'Number of Orders'},
                legend: 'none'
            };

            // Instantiate and draw the line chart.
            var lineChart = new google.visualization.LineChart(document.getElementById('line_chart_div'));
            lineChart.draw(lineChartData, lineChartOptions);

            // Create the data table for the pie chart
            var pieChartData = new google.visualization.DataTable();
            pieChartData.addColumn('string', 'Status');
            pieChartData.addColumn('number', 'Number of Orders');

            // Add data rows for the pie chart from PHP
            pieChartData.addRows(<?php echo json_encode($dataPieChart); ?>);

            // Set options for the pie chart
            var pieChartOptions = {
                title: 'Order Status Comparison',
                pieHole: 0.4
            };

            // Instantiate and draw the pie chart.
            var pieChart = new google.visualization.PieChart(document.getElementById('pie_chart_div'));
            pieChart.draw(pieChartData, pieChartOptions);
        }
    </script>
    <style>
        #charts-container {
            text-align: center;
        }
    </style>
</head>
<body>
    <div id="charts-container">
        <h1>Orders</h1>
        <div id="line_chart_div" style="padding:10px; width: 900px; height: 500px; display: inline-block;"></div>
        <div id="pie_chart_div" style="padding:10px; width: 600px; height: 500px; display: inline-block;"></div>
    </div>
</body>
</html>
