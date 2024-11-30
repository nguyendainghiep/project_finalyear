<?php
include 'connection.php'; // Kết nối đến cơ sở dữ liệu

// Xử lý form khi người dùng gửi dữ liệu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $metal_name = $conn->real_escape_string($_POST['metal_name']);
    $cost_price = (int)$_POST['cost_price'];
    $selling_price = (int)$_POST['selling_price'];

    // Thực thi câu lệnh SQL để thêm dữ liệu vào bảng metal
    $sql = "INSERT INTO metal (Metal_name, Date, Cost_price, Selling_price) VALUES ('$metal_name', NOW(), $cost_price, $selling_price)";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success' role='alert'>New metal added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $conn->error . "</div>";
    }
}
?>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Add New Metal</h2>
        <form method="post" class="mt-4">
            <div class="mb-3">
                <label for="metal_name" class="form-label">Metal Name</label>
                <input type="text" class="form-control" id="metal_name" name="metal_name" required>
            </div>
            <div class="mb-3">
                <label for="cost_price" class="form-label">Cost Price ($)</label>
                <input type="number" class="form-control" id="cost_price" name="cost_price" required>
            </div>
            <div class="mb-3">
                <label for="selling_price" class="form-label">Selling Price ($)</label>
                <input type="number" class="form-control" id="selling_price" name="selling_price" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Add Metal</button>
                <a href="?page=metal" class="btn btn-secondary">Back to List</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
