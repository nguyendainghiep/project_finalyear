<?php
include 'connection.php'; // Kết nối đến cơ sở dữ liệu

// Kiểm tra nếu ID danh mục được truyền qua URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid category ID.");
}

$category_id = (int)$_GET['id'];

// Xử lý form khi người dùng gửi dữ liệu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $category_name = $conn->real_escape_string($_POST['category_name']);
    $description = $conn->real_escape_string($_POST['description']);

    // Thực thi câu lệnh SQL để cập nhật dữ liệu vào bảng category
    $sql = "UPDATE category SET Name='$category_name', Description='$description' WHERE id=$category_id";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success' role='alert'>Category updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $conn->error . "</div>";
    }
}

// Lấy thông tin danh mục từ cơ sở dữ liệu
$sql = "SELECT * FROM category WHERE id=$category_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $category = $result->fetch_assoc();
} else {
    die("Category not found.");
}
?>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Update Category</h2>
        <form method="post" class="mt-4">
            <div class="mb-3">
                <label for="category_name" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" value="<?php echo htmlspecialchars($category['Name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($category['Description']); ?></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Update Category</button>
                <a href="?page=category" class="btn btn-secondary">Back to List</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
