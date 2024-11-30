<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Order Form</title>
    <style>


        .form-header {
            border-bottom: 3px solid #888396;
            margin-bottom: 20px;
            padding-bottom: 12px;
            text-align: center;
        }

        .form-header h2 {
            margin: 0;
            color: #242633;
            font-size: 24px;
            font-weight: bold;
        }

        h4 {
            margin-bottom: 15px;
            color: #333;
            font-size: 20px;
        }

        .mb-3 {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: .5rem;
            font-weight: 600;
            color: #555;
        }

        .form-control, .form-select {
            display: block;
            width: 100%;
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #ffffff;
            border: 1px solid #ced4da;
            border-radius: .375rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .form-control:focus, .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 .2rem rgba(38, 143, 255, .25);
        }

        textarea.form-control {
            resize: vertical;
        }

        .btn-primary {
            font-weight: bolder;
            background-color: #c4ccc5; /* Xám sáng nhẹ */
  color: #5a5c5a;
  border: 2px solid #c4ccc5; /* Đường viền xám sáng */
        }

        .btn-primary:hover {
            background-color: #2d8544;
  box-shadow: 0px 15px 20px rgba(46, 229, 157, 0.4);
  color: #fff;
  border: 2px solid #2d8544;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: .375rem;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .input-group {
            display: flex;
            align-items: center;
        }

        .input-group-text {
            background-color: #e9ecef;
            border: 1px solid #ced4da;
            border-radius: .375rem;
            padding: .375rem .75rem;
            font-size: 1rem;
            color: #495057;
        }
        .btn-submit {
  display: flex;
  justify-content: center; /* Căn giữa theo chiều ngang */
  align-items: center; /* Căn giữa theo chiều dọc (nếu cần) */
}
.back-link {
  display: inline-flex;
  align-items: center;
  color: black; /* Màu sắc của liên kết */
  text-decoration: none; /* Bỏ gạch dưới */
  font-weight: bold; /* Đậm chữ */
  font-size: 16px; /* Kích thước chữ */
}

.back-link i {
  margin-right: 8px; /* Khoảng cách giữa biểu tượng và chữ */
}

.back-link:hover {
  color: #0056b3; /* Màu sắc của liên kết khi hover *//* Gạch dưới khi hover */
}
    </style>
</head>
<body>
    <?php
    include 'connection.php'; // Connect to the database

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get data from form
        $userid = $_SESSION['userid']; // Or get from session or login user
        $category = $_POST['jewelry_type'];
        $body_size = $_POST['body_size'];
        $metal = $_POST['material'];
        $metal_weight = $_POST['material_weight'];
        $stone = isset($_POST['gemstone_type']) && $_POST['gemstone_type'] !== '' ? $_POST['gemstone_type'] : NULL;
        $stone_weight = $_POST['gemstone_size'];
        $description = $_POST['description'];
        $machining = $_POST['crafting'];
        $ma_description = $_POST['notes'];

        // Prepare SQL statement to insert data
        $sql = "INSERT INTO personal_order (userid, category, body_size, metal, metal_weight, stone, stone_weight, description, machining, ma_description)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iidiiissss", $userid, $category, $body_size, $metal, $metal_weight, $stone, $stone_weight, $description, $machining, $ma_description);

        // Execute SQL statement
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Order placed successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }
    }
    ?>

    <div class="container">
        <div class="form-header">
            <h2>Custom Order Form</h2>
        </div>
        <form action="" method="post">

            <h4>Product Information</h4>
            <div class="mb-3">
                <label for="jewelry_type" class="form-label">Type of Jewelry*</label>
                <select class="form-select" id="jewelry_type" name="jewelry_type" required>
                    <option value="1">Ring</option>
                    <option value="2">Necklace</option>
                    <option value="3">Bracelet</option>
                    <option value="4">Earrings</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="fit" class="form-label">Fit*</label>
                <select class="form-select" id="fit" name="fit" required>
                    <option value="loose">Loose</option>
                    <option value="fitting">Fitting</option>
                    <option value="tight">Tight</option>
                    <option value="None">None</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="body_size" class="form-label">Body Size (Earring does not need to type here.)</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="body_size" name="body_size">
                    <span class="input-group-text">cm</span>
                </div>
            </div>

            <div class="mb-3">
                <label for="material" class="form-label">Type of Material*</label>
                <select class="form-select" id="material" name="material" required>
                    <?php
                    // Get material data from the metal table
                    $sql = "SELECT * FROM metal";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Check if 'name' contains 'bars'
                            if (strpos($row['Metal_name'], 'bars') === false) {
                                echo "<option value='" . htmlspecialchars($row['Id']) . "'>" . htmlspecialchars($row['Metal_name']) . "</option>";
                            }
                        }
                    } else {
                        echo "<option value=''>No data available</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="material_weight" class="form-label">Material Weight*</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="material_weight" name="material_weight">
                    <span class="input-group-text">carat</span>
                </div>
            </div>

            <div class="mb-3">
                <label for="gemstone_type" class="form-label">Type of Gemstone</label>
                <select class="form-select" id="gemstone_type" name="gemstone_type">
                    <?php
                    // Get gemstone data from the stone table
                    $sql = "SELECT * FROM stone";
                    $result = $conn->query($sql);
                    echo "<option value=''>None</option>";
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No data available</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="gemstone_size" class="form-label">Gemstone Size</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="gemstone_size" name="gemstone_size">
                    <span class="input-group-text">carat</span>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description*</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="crafting" class="form-label">Crafting</label>
                <select class="form-select" id="crafting" name="crafting" required>
                    <option value="NULL">None</option>
                    <option value="Carve">Carve</option>
                    <option value="Metal plating">Metal plating</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Additional Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
            </div>

            <div class="mb-3 btn-submit">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <div class="mb-3 btn-submit">
            <a href="javascript:history.back()" class="back-link">
            <i class="fa-solid fa-caret-left"></i> Back</a>
            </div>

        </form>
    </div>
</body>
</html>
