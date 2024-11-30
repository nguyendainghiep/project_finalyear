<?php
include 'connection.php'; // Kết nối đến cơ sở dữ liệu

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$table = isset($_GET['table']) ? $conn->real_escape_string($_GET['table']) : '';
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Initialize redirect URL variable
$redirect_url = '';

// Kiểm tra nếu ID tồn tại trong bảng order_detail
$sql_check = "SELECT * FROM order_detail WHERE itemId = $id";
$result_check = $conn->query($sql_check);

if ($result_check === false) {
    die('Error checking order_detail: ' . $conn->error);
}

// Truy vấn thông tin chi tiết của sản phẩm
$sql_item = "SELECT * FROM $table WHERE id = $id";
$result_item = $conn->query($sql_item);

if ($result_item === false) {
    die('Error fetching item details: ' . $conn->error);
}

$item = $result_item->fetch_assoc();

// Lấy tên từ các bảng category, stone, metal, và supplier
$sql_category = "SELECT Name FROM category WHERE id = " . (int)$item['Category'];
$result_category = $conn->query($sql_category);
$category_name = ($result_category && $result_category->num_rows > 0) ? $result_category->fetch_assoc()['Name'] : 'Unknown';

$sql_stone = "SELECT name FROM stone WHERE id = " . (int)$item['Stone'];
$result_stone = $conn->query($sql_stone);
$stone_name = ($result_stone && $result_stone->num_rows > 0) ? $result_stone->fetch_assoc()['name'] : 'Unknown';

$sql_metal = "SELECT Metal_name FROM metal WHERE id = " . (int)$item['Metal'];
$result_metal = $conn->query($sql_metal);
$metal_name = ($result_metal && $result_metal->num_rows > 0) ? $result_metal->fetch_assoc()['Metal_name'] : 'Unknown';

$sql_supplier = "SELECT Name FROM supplier WHERE id = " . (int)$item['Supplier'];
$result_supplier = $conn->query($sql_supplier);
$supplier_name = ($result_supplier && $result_supplier->num_rows > 0) ? $result_supplier->fetch_assoc()['Name'] : 'Unknown';

if ($result_check->num_rows > 0) {
    // Nếu có bản ghi trong bảng order_detail
    if ($action === 'continue') {
        // Xóa bản ghi trong order_detail và img_item
        if ($conn->query("DELETE FROM order_detail WHERE itemId = $id") === false) {
            die('Error deleting from order_detail: ' . $conn->error);
        }
        if ($conn->query("DELETE FROM imgitem WHERE itemId = $id") === false) {
            die('Error deleting from imgitem: ' . $conn->error);
        }
        if ($conn->query("DELETE FROM $table WHERE id = $id") === false) {
            die('Error deleting from item: ' . $conn->error);
        }
        $redirect_url = '?page=item_delete_success'; // Đặt URL để chuyển hướng
    }
} else {
    // Nếu không có bản ghi trong bảng order_detail
    if ($action === 'confirm') {
        // Xóa bản ghi trong img_item và item
        if ($conn->query("DELETE FROM imgitem WHERE itemId = $id") === false) {
            die('Error deleting from imgitem: ' . $conn->error);
        }
        if ($conn->query("DELETE FROM $table WHERE id = $id") === false) {
            die('Error deleting from item: ' . $conn->error);
        }
        $redirect_url = '?page=item_delete_success'; // Đặt URL để chuyển hướng
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Delete</title>
    <?php if ($redirect_url): ?>
        <!-- Meta tag to refresh and redirect -->
        <meta http-equiv="refresh" content="0;url=<?php echo htmlspecialchars($redirect_url); ?>">
    <?php endif; ?>
    <style>
        .confirm-delete {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 1rem rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="confirm-delete">
            <h3 class="text-center mb-4">Confirm Delete</h3>
            <p>Are you sure you want to delete the following record?</p>
            <table class="table table-bordered">
                <tr><th>ID</th><td><?php echo htmlspecialchars($item['id']); ?></td></tr>
                <tr><th>Name</th><td><?php echo htmlspecialchars($item['Name']); ?></td></tr>
                <tr><th>Category</th><td><?php echo htmlspecialchars($category_name); ?></td></tr>
                <tr><th>Quantity</th><td><?php echo htmlspecialchars($item['Quantity']); ?></td></tr>
                <tr><th>Description</th><td><?php echo htmlspecialchars($item['Description']); ?></td></tr>
                <tr><th>Metal</th><td><?php echo htmlspecialchars($metal_name); ?></td></tr>
                <tr><th>Metal Weight</th><td><?php echo htmlspecialchars($item['Metal_weight']); ?></td></tr>
                <tr><th>Stone</th><td><?php echo htmlspecialchars($stone_name); ?></td></tr>
                <tr><th>Stone Weight</th><td><?php echo htmlspecialchars($item['Stone_weight']); ?></td></tr>
                <tr><th>Final Price</th><td><?php echo htmlspecialchars($item['Final_price']); ?></td></tr>
                <tr><th>Supplier</th><td><?php echo htmlspecialchars($supplier_name); ?></td></tr>
                <tr><th>Machining Cost</th><td><?php echo htmlspecialchars($item['Machining_cost']); ?></td></tr>
                <tr><th>Receipt Date</th><td><?php echo htmlspecialchars($item['ReceiptDate']); ?></td></tr>
            </table>
            <div class="alert alert-warning" role="alert">
                <?php if ($result_check->num_rows > 0): ?>
                    <p>The item is referenced in the order details. Do you want to continue deleting?</p>
                    <a href="?page=confirm_delete_item&id=<?php echo $id; ?>&table=<?php echo urlencode($table); ?>&action=continue" class="btn btn-danger">Continue Deleting</a>
                    <a href="?page=item" class="btn btn-secondary">Back</a>
                <?php else: ?>
                    <p>The item is not referenced in any order details. Are you sure you want to delete it?</p>
                    <a href="?page=confirm_delete_item&id=<?php echo $id; ?>&table=<?php echo urlencode($table); ?>&action=confirm" class="btn btn-danger">Confirm</a>
                    <a href="?page=item" class="btn btn-secondary">Cancel</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>

