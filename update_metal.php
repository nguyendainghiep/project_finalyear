<?php
include 'connection.php'; // Kết nối đến cơ sở dữ liệu

// Kiểm tra nếu ID kim loại được truyền qua URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid metal ID.");
}

$metal_id = (int)$_GET['id'];

// Xử lý form khi người dùng gửi dữ liệu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $metal_name = $conn->real_escape_string($_POST['metal_name']);
    $cost_price = (int)$_POST['cost_price'];
    $selling_price = (int)$_POST['selling_price'];

    // Thực thi câu lệnh SQL để cập nhật dữ liệu vào bảng metal
    $sql = "UPDATE metal SET Metal_name='$metal_name', Cost_price=$cost_price, Selling_price=$selling_price WHERE id=$metal_id";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success' role='alert'>Metal updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $conn->error . "</div>";
    }
}

// Lấy thông tin kim loại từ cơ sở dữ liệu
$sql = "SELECT * FROM metal WHERE id=$metal_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $metal = $result->fetch_assoc();
} else {
    die("Metal not found.");
}
?>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Update Metal</h2>
        <form method="post" class="mt-4">
            <div class="mb-3">
                <label for="metal_name" class="form-label">Metal Name</label>
                <input type="text" class="form-control" id="metal_name" name="metal_name" value="<?php echo htmlspecialchars($metal['Metal_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="cost_price" class="form-label">Cost Price ($)</label>
                <input type="number" class="form-control" id="cost_price" name="cost_price" value="<?php echo htmlspecialchars($metal['Cost_price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="selling_price" class="form-label">Selling Price ($)</label>
                <input type="number" class="form-control" id="selling_price" name="selling_price" value="<?php echo htmlspecialchars($metal['Selling_price']); ?>" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Update Metal</button>
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
