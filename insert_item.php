<?php
include_once("connection.php"); // Kết nối cơ sở dữ liệu

// Lấy dữ liệu từ các bảng để hiển thị trong form
$categories = mysqli_query($conn, 'SELECT id, Name FROM category');
$metals = mysqli_query($conn, 'SELECT Id, Metal_name, Selling_price FROM metal');
$stones = mysqli_query($conn, 'SELECT id, name, Selling_price FROM stone');
$suppliers = mysqli_query($conn, 'SELECT id, Name FROM supplier');

// Xử lý khi form được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['Name'];
    $category = $_POST['Category'];
    $quantity = $_POST['Quantity'];
    $description = $_POST['Description'];
    $metal = $_POST['Metal'];
    $metal_weight = floatval($_POST['Metal_weight']);
    $stone = isset($_POST['Stone']) ? $_POST['Stone'] : null; // Thay đổi để cho phép NULL
    $stone_weight = isset($_POST['Stone_weight']) ? floatval($_POST['Stone_weight']) : 0;
    $supplier = $_POST['Supplier'];
    $machining_cost = floatval($_POST['Machining_cost']);

    // Lấy giá bán từ bảng metal
    $metal_query = mysqli_prepare($conn, 'SELECT Selling_price FROM metal WHERE Id = ?');
    mysqli_stmt_bind_param($metal_query, 'i', $metal);
    mysqli_stmt_execute($metal_query);
    mysqli_stmt_bind_result($metal_query, $metal_selling_price);
    mysqli_stmt_fetch($metal_query);
    mysqli_stmt_close($metal_query);

    // Chuyển đổi giá bán metal thành số thực (float)
    $metal_selling_price = floatval($metal_selling_price);

    // Tính toán giá trị Final_price
    $stone_selling_price = 0; // Nếu không chọn stone, giá trị stone_selling_price là 0
    if ($stone) {
        // Lấy giá bán từ bảng stone
        $stone_query = mysqli_prepare($conn, 'SELECT Selling_price FROM stone WHERE id = ?');
        mysqli_stmt_bind_param($stone_query, 'i', $stone);
        mysqli_stmt_execute($stone_query);
        mysqli_stmt_bind_result($stone_query, $stone_selling_price);
        mysqli_stmt_fetch($stone_query);
        mysqli_stmt_close($stone_query);
    }

    // Chuyển đổi giá bán stone thành số thực (float)
    $stone_selling_price = floatval($stone_selling_price);

    // Tính toán Final_price
    $final_price = ($metal_selling_price * $metal_weight) + ($stone_selling_price * $stone_weight) + $machining_cost;

    // Chèn thông tin sản phẩm vào bảng `item`
    $sql = "INSERT INTO item (Name, Category, Quantity, Description, Metal, Metal_weight, Stone, Stone_weight, Supplier, Machining_cost, Final_price, ReceiptDate) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind parameters with correct type definition
        mysqli_stmt_bind_param($stmt, 'siisisiiidd', $name, $category, $quantity, $description, $metal, $metal_weight, $stone, $stone_weight, $supplier, $machining_cost, $final_price);
    
        if (mysqli_stmt_execute($stmt)) {
            $itemId = mysqli_insert_id($conn);
    
            // Xử lý upload ảnh
            $upload_dir = 'style/template/img/products/';
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $errors = [];
    
            // Xử lý ảnh 1
            if (isset($_FILES['Image1']) && $_FILES['Image1']['error'] == 0) {
                if (in_array($_FILES['Image1']['type'], $allowed_types)) {
                    $img_path = $upload_dir . basename($_FILES['Image1']['name']);
                    if (move_uploaded_file($_FILES['Image1']['tmp_name'], $img_path)) {
                        $sql_img = "INSERT INTO imgitem (itemId, name, place) VALUES (?, ?, 1)";
                        if ($stmt_img = mysqli_prepare($conn, $sql_img)) {
                            mysqli_stmt_bind_param($stmt_img, 'is', $itemId, $_FILES['Image1']['name']);
                            mysqli_stmt_execute($stmt_img);
                            mysqli_stmt_close($stmt_img);
                        } else {
                            $errors[] = "Error preparing image insert statement for image 1: " . mysqli_error($conn);
                        }
                    } else {
                        $errors[] = "Error uploading image 1.";
                    }
                } else {
                    $errors[] = "Invalid file type for image 1.";
                }
            }
    
            // Xử lý ảnh 2
            if (isset($_FILES['Image2']) && $_FILES['Image2']['error'] == 0) {
                if (in_array($_FILES['Image2']['type'], $allowed_types)) {
                    $img_path = $upload_dir . basename($_FILES['Image2']['name']);
                    if (move_uploaded_file($_FILES['Image2']['tmp_name'], $img_path)) {
                        $sql_img = "INSERT INTO imgitem (itemId, name, place) VALUES (?, ?, 2)";
                        if ($stmt_img = mysqli_prepare($conn, $sql_img)) {
                            mysqli_stmt_bind_param($stmt_img, 'is', $itemId, $_FILES['Image2']['name']);
                            mysqli_stmt_execute($stmt_img);
                            mysqli_stmt_close($stmt_img);
                        } else {
                            $errors[] = "Error preparing image insert statement for image 2: " . mysqli_error($conn);
                        }
                    } else {
                        $errors[] = "Error uploading image 2.";
                    }
                } else {
                    $errors[] = "Invalid file type for image 2.";
                }
            }
    
            if (empty($errors)) {
                $message = "Insert successful!";
            } else {
                $message = "Insert successful, but errors occurred: " . implode(", ", $errors);
            }
        } else {
            $message = "Error executing query: " . mysqli_error($conn);
        }
    } else {
        $message = "Error preparing statement: " . mysqli_error($conn);
    }
}    

// Chuyển hướng đến trang thông báo thành công
if (isset($message)) {
    echo '<div class="container mt-5"><div class="alert alert-success">' . htmlspecialchars($message) . '</div></div>';
}
?>




<body>
    <div class="container mt-5">
        <h2>Insert New Item</h2>
        <form method="post" enctype="multipart/form-data">
            <!-- Existing form fields -->
            <div class="mb-3">
                <label for="Name" class="form-label">Item Name</label>
                <input type="text" class="form-control" id="Name" name="Name" required>
            </div>
            <div class="mb-3">
                <label for="Category" class="form-label">Category</label>
                <select class="form-select" id="Category" name="Category" required>
                    <option value="">Select a category</option>
                    <?php while ($row = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['Name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="Quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="Quantity" name="Quantity" required>
            </div>
            <div class="mb-3">
                <label for="Description" class="form-label">Description</label>
                <textarea class="form-control" id="Description" name="Description" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="Metal" class="form-label">Metal</label>
                <select class="form-select" id="Metal" name="Metal" required>
                    <option value="None">Select metal</option>
                    <?php while ($row = mysqli_fetch_assoc($metals)): ?>
                        <option value="<?= htmlspecialchars($row['Id']) ?>"><?= htmlspecialchars($row['Metal_name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="Metal_weight" class="form-label">Metal Weight</label>
                <input type="number" step="0.01" class="form-control" id="Metal_weight" name="Metal_weight" required>
            </div>
            <div class="mb-3">
                <label for="Stone" class="form-label">Stone</label>
                <select class="form-select" id="Stone" name="Stone">
                    <option value="">Select stone</option>
                    <?php while ($row = mysqli_fetch_assoc($stones)): ?>
                        <option value="<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="Stone_weight" class="form-label">Stone Weight</label>
                <input type="number" step="0.01" class="form-control" id="Stone_weight" name="Stone_weight">
            </div>
            <div class="mb-3">
                <label for="Supplier" class="form-label">Supplier</label>
                <select class="form-select" id="Supplier" name="Supplier" required>
                    <option value="">Select supplier</option>
                    <?php while ($row = mysqli_fetch_assoc($suppliers)): ?>
                        <option value="<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['Name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="Machining_cost" class="form-label">Machining Cost</label>
                <input type="number" step="0.01" class="form-control" id="Machining_cost" name="Machining_cost" required>
            </div>
            <div class="mb-3">
                <label for="Image1" class="form-label">Image 1</label>
                <input type="file" class="form-control" id="Image1" name="Image1">
            </div>
            <div class="mb-3">
                <label for="Image2" class="form-label">Image 2</label>
                <input type="file" class="form-control" id="Image2" name="Image2">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
