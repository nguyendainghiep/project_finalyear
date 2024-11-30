<?php
include 'connection.php'; // Kết nối đến cơ sở dữ liệu

// Xử lý form khi người dùng gửi dữ liệu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $category_name = $conn->real_escape_string($_POST['category_name']);
    $description = $conn->real_escape_string($_POST['description']);

    // Thực thi câu lệnh SQL để thêm dữ liệu vào bảng category
    $sql = "INSERT INTO category (Name, Description) VALUES ('$category_name', '$description')";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success' role='alert'>New category added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $conn->error . "</div>";
    }
}
?>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Add New Category</h2>
        <form method="post" class="mt-4">
            <div class="mb-3">
                <label for="category_name" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Add Category</button>
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
