<?php
include 'connection.php'; // Kết nối đến cơ sở dữ liệu

// Lấy ID từ URL
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Kiểm tra xem ID có hợp lệ không
if ($order_id <= 0) {
    die('Invalid order ID.');
}

// Truy vấn dữ liệu từ bảng order_detail dựa trên OrderId
$sql = "SELECT od.*, i.Name as ItemName 
        FROM order_detail od 
        JOIN item i ON od.ItemId = i.id 
        WHERE od.OrderId = $order_id";

$result = $conn->query($sql);

// Truy vấn thông tin đơn hàng từ bảng order
$order_info = $conn->query("SELECT * FROM `order` WHERE id = $order_id")->fetch_assoc();
?>



<body>
    <div class="container mt-4">
        <h2 class="text-center">Order Details for Order ID: <?php echo htmlspecialchars($order_id); ?></h2>

        <!-- Thông tin đơn hàng -->
        <div class="mb-4">
            <h4>Order Information</h4>
            <ul class="list-group">
                <li class="list-group-item"><strong>User ID:</strong> <?php echo htmlspecialchars($order_info['userID']); ?></li>
                <li class="list-group-item"><strong>Order Date:</strong> <?php echo htmlspecialchars($order_info['OrderDate']); ?></li>
                <li class="list-group-item"><strong>Status:</strong> <?php echo htmlspecialchars($order_info['Status']); ?></li>
                <li class="list-group-item"><strong>Total:</strong> <?php echo htmlspecialchars($order_info['Total']); ?></li>
            </ul>
        </div>

        <!-- Chi tiết đơn hàng -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Order Details</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $total = $row['Quantity'] * $row['Price'];
                                    echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['ItemName']}</td>
                                        <td>{$row['Quantity']}</td>
                                        <td>{$row['Price']}</td>
                                        <td>$total</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>No details found for this order.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <button class="btn btn-primary mt-4" onclick="history.back()">Back to Orders</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
