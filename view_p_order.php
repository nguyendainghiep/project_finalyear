<?php
include 'connection.php'; // Kết nối đến cơ sở dữ liệu

// Lấy ID từ URL
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Kiểm tra xem ID có hợp lệ không
if ($order_id <= 0) {
    die('Invalid order ID.');
}

try {
    // Truy vấn dữ liệu từ bảng personal_order
    $order_info = $conn->query("SELECT * FROM personal_order WHERE id = $order_id")->fetch_assoc();
    if (!$order_info) {
        throw new Exception('Order not found.');
    }

    // Truy vấn thông tin từ các bảng liên quan
    $category_query = $conn->query("SELECT * FROM category WHERE id = {$order_info['category']}")->fetch_assoc();
    $metal_query = $conn->query("SELECT * FROM metal WHERE id = {$order_info['metal']}")->fetch_assoc();
    $user_query = $conn->query("SELECT * FROM user WHERE id = {$order_info['userid']}")->fetch_assoc();

    // Kiểm tra nếu stone không phải là NULL trước khi truy vấn
    $stone_query = null;
    if (!empty($order_info['stone'])) {
        $stone_query = $conn->query("SELECT * FROM stone WHERE id = {$order_info['stone']}")->fetch_assoc();
    }

} catch (Exception $e) {
    die($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Personal Order</title>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Order Details for Order ID: <?php echo htmlspecialchars($order_id); ?></h2>

        <!-- Thông tin đơn hàng -->
        <div class="mb-4">
            <h4>Order Information</h4>
            <ul class="list-group">
                <li class="list-group-item"><strong>User:</strong> <?php echo htmlspecialchars($user_query['FirstName']); ?> <?php echo htmlspecialchars($user_query['LastName']); ?></li>
                <li class="list-group-item"><strong>Category:</strong> <?php echo htmlspecialchars($category_query['Name']); ?></li>

                <li class="list-group-item"><strong>Body Size:</strong> <?php echo htmlspecialchars($order_info['body_size']); ?> Cm</li>
                <li class="list-group-item"><strong>Metal:</strong> <?php echo htmlspecialchars($metal_query['Metal_name']); ?></li>
                <li class="list-group-item"><strong>Metal Weight:</strong> <?php echo htmlspecialchars($order_info['metal_weight']); ?> Ct</li>
                
                <!-- Kiểm tra nếu stone_weight không NULL trước khi hiển thị -->
                                 <!-- Kiểm tra nếu stone_query không NULL trước khi hiển thị -->
                <li class="list-group-item"><strong>Stone:</strong> <?php echo $stone_query ? htmlspecialchars($stone_query['name']) : 'N/A'; ?></li>
                <li class="list-group-item"><strong>Stone Weight:</strong> <?php echo !empty($order_info['stone_weight']) ? htmlspecialchars($order_info['stone_weight']) . ' Ct' : 'N/A'; ?></li>

                <li class="list-group-item"><strong>Description:</strong> <?php echo htmlspecialchars($order_info['description']); ?></li>
                <li class="list-group-item"><strong>Machining:</strong> <?php echo htmlspecialchars($order_info['machining']); ?></li>
                <li class="list-group-item"><strong>Machining Description:</strong> <?php echo htmlspecialchars($order_info['ma_description']); ?></li>
            </ul>
        </div>

        <a href="?page=personal_order" class="btn btn-primary mt-4">Back to Orders</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
