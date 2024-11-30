<?php
include 'connection.php'; // Kết nối đến cơ sở dữ liệu

// Lấy ID của sản phẩm từ URL
$item_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Biến để lưu thông báo
$update_message = '';

// Kiểm tra nếu form đã được submit để cập nhật thông tin sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_info'])) {
    // Lấy dữ liệu từ form
    $name = $conn->real_escape_string($_POST['name']);
    $category = (int)$_POST['category'];
    $quantity = (int)$_POST['quantity'];
    $metal = (int)$_POST['metal'];
    $metal_weight = (float)$_POST['metal_weight'];
    $stone = (int)$_POST['stone'];
    $stone_weight = (float)$_POST['stone_weight'];
    $machining_cost = (int)$_POST['machining_cost'];
    $description = $conn->real_escape_string($_POST['description']);
    $supplier = (int)$_POST['supplier'];

    // Tính toán lại giá cuối cùng
    $final_price = ($metal_weight * $metal + $stone_weight * $stone + $machining_cost);

    // Cập nhật thông tin sản phẩm
    $sql = "UPDATE item SET 
            Name = '$name',
            Category = $category,
            Quantity = $quantity,
            Metal = $metal,
            Metal_weight = $metal_weight,
            Stone = $stone,
            Stone_weight = $stone_weight,
            Machining_cost = $machining_cost,
            Description = '$description',
            Supplier = $supplier,
            Final_price = $final_price
            WHERE id = $item_id";

    if ($conn->query($sql) === TRUE) {
        $update_message = "<div class='alert alert-success'>Item updated successfully.</div>";
    } else {
        $update_message = "<div class='alert alert-danger'>Error updating item: " . $conn->error . "</div>";
    }
}

// Kiểm tra nếu form đã được submit để cập nhật hình ảnh
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_images'])) {
    // Xử lý upload ảnh cho place 1 và place 2
    $upload_dir = 'style/template/img/products/';

    for ($place = 1; $place <= 2; $place++) {
        $image_field = 'image_' . $place;
        if (!empty($_FILES[$image_field]['name'])) {
            $image_name = basename($_FILES[$image_field]['name']);
            $target_file = $upload_dir . $image_name;

            if (move_uploaded_file($_FILES[$image_field]['tmp_name'], $target_file)) {
                // Cập nhật đường dẫn ảnh trong cơ sở dữ liệu
                $sql_img = "UPDATE imgitem SET name = '$image_name' WHERE ItemId = $item_id AND place = $place";
                $conn->query($sql_img);
            } else {
                echo "Error uploading image $place.";
            }
        }
    }
}

// Lấy thông tin sản phẩm từ cơ sở dữ liệu
$sql = "SELECT * FROM item WHERE id = $item_id";
$item_result = $conn->query($sql);
$item = $item_result->fetch_assoc();

// Lấy thông tin hình ảnh từ cơ sở dữ liệu
$sql_img = "SELECT * FROM imgitem WHERE itemId = $item_id ORDER BY place ASC";
$img_result = $conn->query($sql_img);
$images = [];
while ($img_row = $img_result->fetch_assoc()) {
    $images[$img_row['place']] = $img_row['name'];
}

// Lấy danh sách các danh mục, kim loại, đá quý và nhà cung cấp
$categories = $conn->query("SELECT id, Name FROM category")->fetch_all(MYSQLI_ASSOC);
$metals = $conn->query("SELECT id, Metal_name FROM metal")->fetch_all(MYSQLI_ASSOC);
$stones = $conn->query("SELECT id, name FROM stone")->fetch_all(MYSQLI_ASSOC);
$suppliers = $conn->query("SELECT id, Name FROM supplier")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Item</title>  
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <!-- Cột bên trái - Form cập nhật thông tin sản phẩm -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3>Update Item Information</h3>
                        <?php echo $update_message; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($item['Name']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select id="category" name="category" class="form-control" required>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $item['Category'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['Name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo htmlspecialchars($item['Quantity']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="metal" class="form-label">Metal</label>
                                <select id="metal" name="metal" class="form-control" required>
                                    <?php foreach ($metals as $metal): ?>
                                        <option value="<?php echo $metal['id']; ?>" <?php echo $metal['id'] == $item['Metal'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($metal['Metal_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="metal_weight" class="form-label">Metal Weight (Ct)</label>
                                <input type="number" step="0.01" class="form-control" id="metal_weight" name="metal_weight" value="<?php echo htmlspecialchars($item['Metal_weight']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="stone" class="form-label">Stone</label>
                                <select id="stone" name="stone" class="form-control">
                                    <?php foreach ($stones as $stone): ?>
                                        <option value="<?php echo $stone['id']; ?>" <?php echo $stone['id'] == $item['Stone'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($stone['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="stone_weight" class="form-label">Stone Weight (Ct)</label>
                                <input type="number" step="0.01" class="form-control" id="stone_weight" name="stone_weight" value="<?php echo htmlspecialchars($item['Stone_weight']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="machining_cost" class="form-label">Machining Cost ($)</label>
                                <input type="number" class="form-control" id="machining_cost" name="machining_cost" value="<?php echo htmlspecialchars($item['Machining_cost']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="supplier" class="form-label">Supplier</label>
                                <select id="supplier" name="supplier" class="form-control" required>
                                    <?php foreach ($suppliers as $supplier): ?>
                                        <option value="<?php echo $supplier['id']; ?>" <?php echo $supplier['id'] == $item['Supplier'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($supplier['Name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($item['Description']); ?></textarea>
                            </div>
                            <button type="submit" name="update_info" class="btn btn-primary">Update Information</button>
                        </form>
                    </div>
                </div>

            </div>

            <!-- Cột bên phải - Form cập nhật hình ảnh -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3>Update Item Images</h3>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="image_1" class="form-label">Image 1</label>
                                <input type="file" class="form-control" id="image_1" name="image_1">
                                <?php if (isset($images[1])): ?>
                                    <img src="style/template/img/products/<?php echo htmlspecialchars($images[1]); ?>" alt="Image 1" class="img-thumbnail mt-2" width="200">
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="image_2" class="form-label">Image 2</label>
                                <input type="file" class="form-control" id="image_2" name="image_2">
                                <?php if (isset($images[2])): ?>
                                    <img src="style/template/img/products/<?php echo htmlspecialchars($images[2]); ?>" alt="Image 2" class="img-thumbnail mt-2" width="200">
                                <?php endif; ?>
                            </div>
                            <button type="submit" name="update_images" class="btn btn-primary">Update Images</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        <div class="text-center"><a href="?page=item" class="btn btn-secondary mt-3">Back to List</a>
</div>
    </div>

</body>

</html>

<?php
$conn->close();
?>
