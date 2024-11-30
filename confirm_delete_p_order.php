<?php
include 'connection.php'; // Kết nối đến cơ sở dữ liệu

// Lấy ID từ URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Kiểm tra xem ID có hợp lệ không
if ($id <= 0) {
    echo "<div class='alert alert-danger' role='alert'>Invalid order ID.</div>";
    exit();
}

// Truy vấn thông tin đơn hàng từ bảng personal_order
$sql = "SELECT * FROM personal_order WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    echo "<div class='alert alert-danger' role='alert'>No record found.</div>";
    exit();
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Delete Personal Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <div class="container mt-4">
        <div class="confirm-delete">
            <h3 class="text-center mb-4">Confirm Delete Personal Order</h3>
            <p>Are you sure you want to delete the following order?</p>
            <table class="table table-bordered">
                <tr><th>ID</th><td><?php echo htmlspecialchars($row['id']); ?></td></tr>
                <tr><th>User ID</th><td><?php echo htmlspecialchars($row['userid']); ?></td></tr>
                <tr><th>Category</th><td><?php echo htmlspecialchars($row['category']); ?></td></tr>
                <tr><th>Body Size</th><td><?php echo htmlspecialchars($row['body_size']); ?></td></tr>
                <tr><th>Metal</th><td><?php echo htmlspecialchars($row['metal']); ?></td></tr>
                <tr><th>Metal Weight</th><td><?php echo htmlspecialchars($row['metal_weight']); ?></td></tr>
                <tr><th>Stone</th><td><?php echo htmlspecialchars($row['stone']); ?></td></tr>
                <tr><th>Stone Weight</th><td><?php echo htmlspecialchars($row['stone_weight']); ?></td></tr>
                <tr><th>Description</th><td><?php echo htmlspecialchars($row['description']); ?></td></tr>
                <tr><th>Machining</th><td><?php echo htmlspecialchars($row['machining']); ?></td></tr>
                <tr><th>Machining Description</th><td><?php echo htmlspecialchars($row['ma_description']); ?></td></tr>
            </table>
            <form method="post" action="?page=delete_success">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="hidden" name="table" value="personal_order">
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <a href="?page=personal_order" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
