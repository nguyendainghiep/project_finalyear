<?php
// Include the database connection file
include 'connection.php';

// Get the userid from the session
$sessionUserId = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;

// Ensure the session userid is set before executing the query
if ($sessionUserId !== null) {
    // Query to fetch data from the order table
    $sql = "
        SELECT o.id, o.userID as userid, o.OrderDate as order_date, o.Status as status, o.Total as total
        FROM `order` o
        WHERE o.userID = ? AND o.Status = 'complete'
    ";

    // Prepare and execute the query
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $sessionUserId);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        die("Error preparing the SQL statement.");
    }
} else {
    die("User not logged in.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .table-container {
            width: 90%;
            margin: 20px auto;
            height:auto;
            min-height: 58.1%;
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
        .table .btn {
            margin: 0;
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

    <h1 style="font-size: 50px;">History Orders</h1>
    <div class="table-container">
        <a href="?page=cart"><button class="btn btn-customizeod"><i class="fa-solid fa-caret-left"></i> Back To Cart</button></a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Order Date</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stt = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $stt . "</td>";
                        echo "<td>" . $row["order_date"] . "</td>";
                        echo "<td>$" . $row["total"] . "</td>";

                        echo "<td><a href='?page=view_detail&id=" . $row["id"] . "' class='btn btn-customizeod'>Detail</a></td>";
                        echo "</tr>";
                        $stt++;
                    }
                } else {
                    echo "<tr><td colspan='5'>No orders found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
