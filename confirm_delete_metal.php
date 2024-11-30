<?php
include 'connection.php'; // Kết nối đến cơ sở dữ liệu

// Lấy ID và bảng từ URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$table = isset($_GET['table']) ? $conn->real_escape_string($_GET['table']) : '';

if ($id <= 0 || empty($table)) {
    echo "<div class='alert alert-danger' role='alert'>Invalid ID or table.</div>";
    exit();
}

// Truy vấn thông tin chi tiết từ bảng tương ứng
$sql = "SELECT * FROM $table WHERE Id = $id";
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
    <div class="container">
        <div class="confirm-delete">
            <h3 class="text-center mb-4">Confirm Delete</h3>
            <p>Are you sure you want to delete the following record?</p>
            <table class="table table-bordered">
                <tr><th>ID</th><td><?php echo htmlspecialchars($row['Id']); ?></td></tr>
                <tr><th>Metal Name</th><td><?php echo htmlspecialchars($row['Metal_name']); ?></td></tr>
                <tr><th>Date</th><td><?php echo htmlspecialchars($row['Date']); ?></td></tr>
                <tr><th>Cost Price</th><td><?php echo htmlspecialchars($row['Cost_price']); ?></td></tr>
                <tr><th>Selling Price</th><td><?php echo htmlspecialchars($row['Selling_price']); ?></td></tr>
            </table>
            <form method="post" action="?page=delete_success">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="hidden" name="table" value="<?php echo htmlspecialchars($table); ?>">
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <a href="#" class="btn btn-secondary" onclick="window.history.back(); return false;">Cancel</a>
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
