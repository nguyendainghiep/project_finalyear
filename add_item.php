<?php
include 'connection.php'; // Kết nối đến cơ sở dữ liệu

// Xử lý form khi người dùng gửi dữ liệu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $name = $conn->real_escape_string($_POST['name']);
    $category = (int)$_POST['category'];
    $quantity = (int)$_POST['quantity'];
    $description = $conn->real_escape_string($_POST['description']);
    $metal = (int)$_POST['metal'];
    $metal_weight = (double)$_POST['metal_weight'];
    $stone = isset($_POST['stone']) && $_POST['stone'] !== '' ? (int)$_POST['stone'] : NULL;
    $stone_weight = isset($_POST['stone_weight']) && $_POST['stone_weight'] !== '' ? (double)$_POST['stone_weight'] : NULL;
    
    $supplier = (int)$_POST['supplier'];
    $machining_cost = (int)$_POST['machining_cost'];
    $receipt_date = $_POST['receipt_date'];

    // Lấy giá bán của kim loại
    $metal_result = $conn->query("SELECT Selling_price FROM metal WHERE Id = $metal");
    $metal_row = $metal_result->fetch_assoc();
    $metal_selling_price = $metal_row['Selling_price'];

  // Lấy giá bán của đá quý (nếu có)
if ($stone !== NULL) {
    $stone_result = $conn->query("SELECT Selling_price FROM stone WHERE id = $stone");
    if ($stone_result && $stone_result->num_rows > 0) {
        $stone_row = $stone_result->fetch_assoc();
        $stone_selling_price = $stone_row['Selling_price'];
    } else {
        $stone_selling_price = 0;
    }
} else {
    $stone_selling_price = 0;
}

    // Tính toán giá cuối cùng
    $final_price = ($stone_selling_price * $stone_weight) + ($metal_selling_price * $metal_weight) + $machining_cost;

    // Xử lý ảnh
    $img1 = $_FILES['img1']['name'];
    $img2 = $_FILES['img2']['name'];
    $img1_tmp = $_FILES['img1']['tmp_name'];
    $img2_tmp = $_FILES['img2']['tmp_name'];
    
    $target_dir = "style/template/img/products/";
    $img1_target = $target_dir . basename($img1);
    $img2_target = $target_dir . basename($img2);

    // Di chuyển ảnh từ thư mục tạm đến thư mục đích
    move_uploaded_file($img1_tmp, $img1_target);
    move_uploaded_file($img2_tmp, $img2_target);

    // Thực thi câu lệnh SQL để thêm dữ liệu vào bảng item
    $sql = "INSERT INTO item (Name, Category, Quantity, Description, Metal, Metal_weight, Stone, Stone_weight, Final_price, Supplier, Machining_cost, ReceiptDate) 
        VALUES ('$name', $category, $quantity, '$description', $metal, $metal_weight, " . ($stone !== NULL ? $stone : 'NULL') . ", " . ($stone_weight !== NULL ? $stone_weight : 'NULL') . ", $final_price, $supplier, $machining_cost, '$receipt_date')";

    if ($conn->query($sql) === TRUE) {
        // Lấy ID của sản phẩm mới thêm vào
        $itemId = $conn->insert_id;
        
        // Thực thi câu lệnh SQL để thêm ảnh vào bảng imgitem
        $sql_img1 = "INSERT INTO imgitem (name, place, itemId) VALUES ('$img1', 1, $itemId)";
        $sql_img2 = "INSERT INTO imgitem (name, place, itemId) VALUES ('$img2', 2, $itemId)";
        
        $conn->query($sql_img1);
        $conn->query($sql_img2);

        echo "<div class='alert alert-success' role='alert'>New item added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Item</title>

</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Add New Item</h2>
        <form method="post" enctype="multipart/form-data" class="mt-4">
            <div class="mb-3">
                <label for="name" class="form-label">Item Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select id="category" name="category" class="form-select" required>
                    <?php
                    $result = $conn->query("SELECT id, Name FROM category");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['Name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="metal" class="form-label">Metal</label>
                <select id="metal" name="metal" class="form-select" required>
                    <?php
                    $result = $conn->query("SELECT Id, Metal_name FROM metal");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['Id']}'>{$row['Metal_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="metal_weight" class="form-label">Metal Weight (Ct)</label>
                <input type="number" step="0.01" class="form-control" id="metal_weight" name="metal_weight" required>
            </div>
            <div class="mb-3">
                <label for="stone" class="form-label">Stone</label>
                <select id="stone" name="stone" class="form-select">
                    <option value="">None</option>
                    <?php
                    $result = $conn->query("SELECT id, Name FROM stone");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['Name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="stone_weight" class="form-label">Stone Weight (Ct)</label>
                <input type="number" step="0.01" class="form-control" id="stone_weight" name="stone_weight">
            </div>
            <div class="mb-3">
                <label for="supplier" class="form-label">Supplier</label>
                <select id="supplier" name="supplier" class="form-select" required>
                    <?php
                    $result = $conn->query("SELECT id, Name FROM supplier");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['Name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="machining_cost" class="form-label">Machining Cost ($)</label>
                <input type="number" class="form-control" id="machining_cost" name="machining_cost" required>
            </div>
            <div class="mb-3">
                <label for="receipt_date" class="form-label">Receipt Date</label>
                <input type="date" class="form-control" id="receipt_date" name="receipt_date" required>
            </div>
            <div class="mb-3">
                <label for="img1" class="form-label">Image 1</label>
                <input type="file" class="form-control" id="img1" name="img1" required>
            </div>
            <div class="mb-3">
                <label for="img2" class="form-label">Image 2</label>
                <input type="file" class="form-control" id="img2" name="img2" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Add Item</button>
                <a href="?page=item" class="btn btn-secondary">Back to List</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
