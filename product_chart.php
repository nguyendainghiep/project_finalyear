

<?php
// Include the database connection file
include 'connection.php';

// Query data for item statistics by category
$sqlCategoryStats = "SELECT c.Name AS CategoryName, COUNT(i.id) AS NumberOfItems
                     FROM `item` i
                     JOIN `category` c ON i.Category = c.id
                     GROUP BY c.Name
                     ORDER BY NumberOfItems DESC";

$resultCategoryStats = $conn->query($sqlCategoryStats);

// Initialize array to store data for category statistics
$dataCategoryStats = [];

if ($resultCategoryStats->num_rows > 0) {
    while ($row = $resultCategoryStats->fetch_assoc()) {
        $dataCategoryStats[] = [$row['CategoryName'], (int)$row['NumberOfItems']];
    }
}

// Query data for item statistics by metal
$sqlMetalStats = "SELECT m.Metal_name AS MetalName, COUNT(i.id) AS NumberOfItems
                  FROM `item` i
                  JOIN `metal` m ON i.Metal = m.Id
                  GROUP BY m.Metal_name
                  ORDER BY NumberOfItems DESC";

$resultMetalStats = $conn->query($sqlMetalStats);

// Initialize array to store data for metal statistics
$dataMetalStats = [];

if ($resultMetalStats->num_rows > 0) {
    while ($row = $resultMetalStats->fetch_assoc()) {
        $dataMetalStats[] = [$row['MetalName'], (int)$row['NumberOfItems']];
    }
}

// Query data for top 10 products by quantity sold
$sqlTopProducts = "SELECT i.Name AS ItemName, SUM(od.Quantity) AS TotalQuantity
                   FROM `order_detail` od
                   JOIN `order` o ON od.OrderId = o.id
                   JOIN `item` i ON od.ItemId = i.id
                   WHERE o.Status = 'complete'
                   GROUP BY i.Name
                   ORDER BY TotalQuantity DESC
                   LIMIT 10";

$resultTopProducts = $conn->query($sqlTopProducts);

// Initialize array to store data for top 10 products
$dataTopProducts = [];

if ($resultTopProducts->num_rows > 0) {
    while ($row = $resultTopProducts->fetch_assoc()) {
        $dataTopProducts[] = [$row['ItemName'], (int)$row['TotalQuantity']];
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
            // Create the data table for category statistics
            var categoryData = new google.visualization.DataTable();
            categoryData.addColumn('string', 'Category');
            categoryData.addColumn('number', 'Number of Items');

            // Add data rows for category statistics from PHP
            categoryData.addRows(<?php echo json_encode($dataCategoryStats); ?>);

            // Set options for the category chart
            var categoryOptions = {
                title: 'Item Count by Category',
                pieHole: 0.4
            };

            // Instantiate and draw the category chart.
            var categoryChart = new google.visualization.PieChart(document.getElementById('category_chart_div'));
            categoryChart.draw(categoryData, categoryOptions);

            // Create the data table for metal statistics
            var metalData = new google.visualization.DataTable();
            metalData.addColumn('string', 'Metal');
            metalData.addColumn('number', 'Number of Items');

            // Add data rows for metal statistics from PHP
            metalData.addRows(<?php echo json_encode($dataMetalStats); ?>);

            // Set options for the metal chart
            var metalOptions = {
                title: 'Item Count by Metal',
                pieHole: 0.4
            };

            // Instantiate and draw the metal chart.
            var metalChart = new google.visualization.PieChart(document.getElementById('metal_chart_div'));
            metalChart.draw(metalData, metalOptions);

            // Create the data table for top 10 products
            var topProductsData = new google.visualization.DataTable();
            topProductsData.addColumn('string', 'Product');
            topProductsData.addColumn('number', 'Total Quantity Sold');

            // Add data rows for top 10 products from PHP
            topProductsData.addRows(<?php echo json_encode($dataTopProducts); ?>);

            // Set options for the top 10 products chart
            var topProductsOptions = {
                title: 'Top 10 Products by Quantity Sold',
                hAxis: {
                    title: 'Product',
                    slantedText: true,
                    slantedTextAngle: 45,
                    textStyle: {
                        fontSize: 12
                    }
                },
                vAxis: {title: 'Total Quantity Sold'},
                legend: 'none',
                chartArea: {
                    width: '70%',
                    height: '88%'
                }
            };

            // Instantiate and draw the top 10 products chart.
            var topProductsChart = new google.visualization.BarChart(document.getElementById('top_products_chart_div'));
            topProductsChart.draw(topProductsData, topProductsOptions);
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
        
        .chart-labels {
            font-size: 14px;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div id="charts-container">
    <div class="chart-container">
            <div class="chart-title">Top 10 Products by Quantity Sold</div>
            <div id="top_products_chart_div" style="width: 1400px; height: 700px;"></div>
        </div>
        <div class="chart-container">
            <div class="chart-title">Item Count by Category</div>
            <div id="category_chart_div" style="width: 600px; height: 500px;"></div>
        </div>
        <div class="chart-container">
            <div class="chart-title">Item Count by Metal</div>
            <div id="metal_chart_div" style="width: 600px; height: 500px;"></div>
        </div>
    </div>
</body>
</html>
