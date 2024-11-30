<?php
// Include the database connection file
include 'connection.php';

// Get the orderId from the URL
$orderId = isset($_GET['id']) ? (int)$_GET['id'] : null;

if ($orderId !== null) {
    // Query to fetch order details and images
    $sql = "SELECT od.id, od.Quantity as quantity, od.Price as price, od.ItemId as item_id, i.name as image_name, it.Name as itemname
        FROM order_detail od
        JOIN imgitem i ON od.ItemId = i.itemId AND i.place = 1
        JOIN item it ON od.ItemId = it.id
        WHERE od.OrderId = ?";

    // Prepare and execute the query for order details
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        die("Error preparing the SQL statement.");
    }

    // Query to fetch total value
    $total_sql = "SELECT Total as total FROM `order` WHERE id = ?";
    if ($total_stmt = $conn->prepare($total_sql)) {
        $total_stmt->bind_param("i", $orderId);
        $total_stmt->execute();
        $total_result = $total_stmt->get_result();
        $total_row = $total_result->fetch_assoc();
        $total = $total_row['total'];
    } else {
        die("Error preparing the total SQL statement.");
    }
} else {
    die("Invalid order ID.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .table-container {
            width: 90%;
            margin: 20px auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .table {
            border-collapse: collapse;
        }

        .table thead th {
            background-color: #c0c6cf;
            color: black;
            text-align: center;
        }

        .table tbody td {
            text-align: center;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table tbody tr:hover {
            background-color: #e9ecef;
        }

        .table img {
            max-width: 100px;
        }

        .btn {
            font-weight: bold;
            background-color: transparent;
        }

        .btn:hover {
            color: #205db3;
        }
    </style>
</head>

<body>

    <h1 style="font-size: 50px;">Order Details</h1>
    <div class="table-container">
        <a href="?page=history_order"><button class="btn btn-customizeod"><i class="fa-solid fa-caret-left"></i> Back to Orders</button></a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stt = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $stt . "</td>";
                        echo "<td><img src='style/template/img/products/" . $row["image_name"] . "' alt='Item Image' style='height: 50px; width: auto;'></td>";
                        echo "<td>" . $row["itemname"] . "</td>";
                        echo "<td>" . $row["quantity"] . "</td>";
                        echo "<td>$" . $row["price"] . "</td>";
                        echo "</tr>";
                        $stt++;
                    }
                } else {
                    echo "<tr><td colspan='4'>No details found</td></tr>";
                }
                $conn->close();
                ?>
    <tr>
        <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
        <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
    </tr>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>