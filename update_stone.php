<?php
include 'connection.php'; // Kết nối đến cơ sở dữ liệu

// Kiểm tra nếu ID đá được truyền qua URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid stone ID.");
}

$stone_id = (int)$_GET['id'];

// Xử lý form khi người dùng gửi dữ liệu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $name = $conn->real_escape_string($_POST['name']);
    $cost_price = (int)$_POST['cost_price'];
    $selling_price = (int)$_POST['selling_price'];
    $detail = $conn->real_escape_string($_POST['detail']);

    // Thực thi câu lệnh SQL để cập nhật dữ liệu vào bảng stone
    $sql = "UPDATE stone SET name='$name', cost_price=$cost_price, selling_price=$selling_price, detail='$detail' WHERE id=$stone_id";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success' role='alert'>Stone updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $conn->error . "</div>";
    }
}

// Lấy thông tin đá từ cơ sở dữ liệu
$sql = "SELECT * FROM stone WHERE id=$stone_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $stone = $result->fetch_assoc();
} else {
    die("Stone not found.");
}
?>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Update Stone</h2>
        <form method="post" class="mt-4">
            <div class="mb-3">
                <label for="name" class="form-label">Stone Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($stone['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="cost_price" class="form-label">Cost Price ($)</label>
                <input type="number" class="form-control" id="cost_price" name="cost_price" value="<?php echo htmlspecialchars($stone['cost_price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="selling_price" class="form-label">Selling Price ($)</label>
                <input type="number" class="form-control" id="selling_price" name="selling_price" value="<?php echo htmlspecialchars($stone['selling_price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="detail" class="form-label">Detail</label>
                <textarea class="form-control" id="detail" name="detail" rows="4" required><?php echo htmlspecialchars($stone['detail']); ?></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Update Stone</button>
                <a href="?page=stone" class="btn btn-secondary">Back to List</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
