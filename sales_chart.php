<?php
// Include the database connection file
include 'connection.php';

// Query data for the line chart
$sqlLineChart = "SELECT DATE_FORMAT(OrderDate, '%Y-%m') AS Month, SUM(Total) AS TotalSales
                 FROM `order`
                 GROUP BY DATE_FORMAT(OrderDate, '%Y-%m')
                 ORDER BY DATE_FORMAT(OrderDate, '%Y-%m')";

$resultLineChart = $conn->query($sqlLineChart);

// Initialize array to store data for the line chart
$dataLineChart = [];

if ($resultLineChart->num_rows > 0) {
    while ($row = $resultLineChart->fetch_assoc()) {
        $dataLineChart[] = [$row['Month'], (float)$row['TotalSales']];
    }
}

// Query data for the metal sales chart (Sales by Metal)
$sqlMetalChart = "SELECT 
                    m.Metal_name AS Metal,
                    SUM(od.Price * od.Quantity) AS TotalSales
                  FROM `order` o
                  JOIN `order_detail` od ON o.id = od.OrderId
                  JOIN `item` i ON od.ItemId = i.id
                  JOIN `metal` m ON i.Metal = m.Id
                  WHERE o.Status = 'complete'
                  GROUP BY m.Metal_name
                  ORDER BY m.Metal_name";

$resultMetalChart = $conn->query($sqlMetalChart);

// Initialize array to store data for the metal sales chart
$dataMetalChart = [];

if ($resultMetalChart->num_rows > 0) {
    while ($row = $resultMetalChart->fetch_assoc()) {
        $metal = $row['Metal'];
        $totalSales = is_numeric($row['TotalSales']) ? (float)$row['TotalSales'] : 0;
        $dataMetalChart[] = [$metal, $totalSales];
    }
}


// Get today's date
$today = date('Y-m-d');

// Query data for today's sales
$sqlTodaySales = "SELECT SUM(Total) AS TotalSales
                  FROM `order`
                  WHERE Status = 'complete' AND DATE(OrderDate) = '$today'";

$resultTodaySales = $conn->query($sqlTodaySales);

// Initialize variable to store today's total sales
$todaySales = 0;

if ($resultTodaySales->num_rows > 0) {
    $row = $resultTodaySales->fetch_assoc();
    $todaySales = is_numeric($row['TotalSales']) ? (float)$row['TotalSales'] : 0;
}

// Query data for sales by selected date
$selectedDate = $today; // Default to today
if (isset($_POST['selected_date'])) {
    $selectedDate = $_POST['selected_date'];
}

// Query data for selected date's sales
$sqlSelectedDateSales = "SELECT SUM(Total) AS TotalSales
                         FROM `order`
                         WHERE Status = 'complete' AND DATE(OrderDate) = '$selectedDate'";

$resultSelectedDateSales = $conn->query($sqlSelectedDateSales);

// Initialize variable to store selected date's total sales
$selectedDateSales = 0;

if ($resultSelectedDateSales->num_rows > 0) {
    $row = $resultSelectedDateSales->fetch_assoc();
    $selectedDateSales = is_numeric($row['TotalSales']) ? (float)$row['TotalSales'] : 0;
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
        google.charts.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // then draws the chart.
        function drawChart() {
            // Create the data table for the line chart
            var lineChartData = new google.visualization.DataTable();
            lineChartData.addColumn('string', 'Month');
            lineChartData.addColumn('number', 'Total Sales');

            // Add data rows for the line chart from PHP
            lineChartData.addRows(<?php echo json_encode($dataLineChart); ?>);

            // Set options for the line chart
            var lineChartOptions = {
                title: 'Monthly Sales Revenue',
                hAxis: {title: 'Month'},
                vAxis: {title: 'Total Sales'},
                legend: 'none'
            };

            // Instantiate and draw the line chart.
            var lineChart = new google.visualization.LineChart(document.getElementById('line_chart_div'));
            lineChart.draw(lineChartData, lineChartOptions);


            var metalChartData = new google.visualization.DataTable();
            metalChartData.addColumn('string', 'Metal');
            metalChartData.addColumn('number', 'Total Sales');

            // Add data rows for the metal sales chart from PHP
            metalChartData.addRows(<?php echo json_encode($dataMetalChart); ?>);

            // Set options for the metal sales chart
            var metalChartOptions = {
                title: 'Sales by Metal',
                hAxis: {title: 'Metal'},
                vAxis: {title: 'Sales'},
                legend: { position: 'top' },
                chartArea: {width: '50%'}
            };

            // Instantiate and draw the metal sales chart.
            var metalChart = new google.visualization.ColumnChart(document.getElementById('metal_chart_div'));
            metalChart.draw(metalChartData, metalChartOptions);


            // Create the data table for the selected date's sales chart
            var salesChartData = new google.visualization.DataTable();
            salesChartData.addColumn('string', 'Date');
            salesChartData.addColumn('number', 'Total Sales');

            // Add data rows for the selected date's sales from PHP
            salesChartData.addRows([
                ['Today', <?php echo json_encode($todaySales); ?>],
                ['Selected Date', <?php echo json_encode($selectedDateSales); ?>]
            ]);

            // Set options for the sales chart
            var salesChartOptions = {
                title: 'Sales by Date',
                hAxis: {title: 'Date'},
                vAxis: {title: 'Sales'},
                legend: { position: 'top' }
            };

            // Instantiate and draw the sales chart.
            var salesChart = new google.visualization.ColumnChart(document.getElementById('sales_chart_div'));
            salesChart.draw(salesChartData, salesChartOptions);
        }

        // Function to handle form submission and update the chart
        function updateChart() {
            document.getElementById('sales_form').submit();
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
        <h1>Sales</h1>
        <div id="line_chart_div" style="padding:10px; width: 900px; height: 500px; display: inline-block;"></div>
    </div>
    <div id="charts-container">
        <h1>Daily Sales</h1>
        <form id="sales_form" method="POST">
            <label for="date_picker">Select Date:</label>
            <input type="date" id="date_picker" name="selected_date" value="<?php echo htmlspecialchars($selectedDate); ?>" onchange="updateChart()">
        </form>
        <div id="sales_chart_div" style="padding:10px; width: 900px; height: 500px; display: inline-block;"></div>
    </div>
    <div id="charts-container">
        <h1>Sales by Metal</h1>
        <div id="metal_chart_div" style="padding:10px; width: 900px; height: 500px; display: inline-block;"></div>
    </div>
</body>
</html>
