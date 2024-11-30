<?php
// Kết nối cơ sở dữ liệu
include 'connection.php'; // Đảm bảo tệp connection.php chứa thông tin kết nối

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Bắt đầu giao dịch
    mysqli_begin_transaction($conn);

    try {
        foreach ($_POST['id'] as $index => $id) {
            // Lấy dữ liệu cũ
            $query = "SELECT * FROM metal WHERE Id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $metal = mysqli_fetch_assoc($result);

            if ($metal) {
                // Chèn dữ liệu cũ vào metal_history
                $query = "INSERT INTO metal_history (metal_id, Metal_name, Date, Cost_price, Selling_price) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "issii", $metal['Id'], $metal['Metal_name'], $metal['Date'], $metal['Cost_price'], $metal['Selling_price']);
                mysqli_stmt_execute($stmt);

                // Cập nhật dữ liệu mới vào bảng metal
                $metal_name = $_POST['metal_name'][$index];
                $cost_price = intval($_POST['cost_price'][$index]);
                $selling_price = intval($_POST['selling_price'][$index]);
                $date = date('Y-m-d H:i:s');

                $query = "UPDATE metal SET Metal_name = ?, Date = ?, Cost_price = ?, Selling_price = ? WHERE Id = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "ssiis", $metal_name, $date, $cost_price, $selling_price, $id);
                mysqli_stmt_execute($stmt);
            }
        }

        // Cam kết giao dịch
        mysqli_commit($conn);
        echo "<div class='alert alert-success mt-3'>Update successful!</div>";
    } catch (Exception $e) {
        // Rollback giao dịch nếu có lỗi
        mysqli_rollback($conn);
        echo "<div class='alert alert-danger mt-3'>Error: " . $e->getMessage() . "</div>";
    }
}

// Lấy toàn bộ dữ liệu từ bảng metal để hiển thị trong form
$query = "SELECT * FROM metal";
$result = mysqli_query($conn, $query);
$metals = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Metal Prices</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 900px;
            margin-top: 30px;
        }
        .form-control {
            border-radius: 0.2rem;
            border-color: #ced4da;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.075);
        }
        .card {
            border: 1px solid #dee2e6;
            border-radius: 0.2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background-color: #ffffff;
            margin-bottom: 1rem;
        }
        .card-title {
            font-size: 1.25rem;
            color: #333;
            margin-bottom: 1rem;
        }
        .card-body {
            padding: 1rem;
        }
        .alert {
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="mb-4 text-center">Update Metal Prices</h2>
    <form action="" method="POST">
        <?php foreach ($metals as $metal): ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">ID: <?php echo htmlspecialchars($metal['Id']); ?></h5>
                    <input type="hidden" name="id[]" value="<?php echo htmlspecialchars($metal['Id']); ?>">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="metal_name_<?php echo htmlspecialchars($metal['Id']); ?>" class="form-label">Metal Name</label>
                            <input type="text" class="form-control" id="metal_name_<?php echo htmlspecialchars($metal['Id']); ?>" name="metal_name[]" value="<?php echo htmlspecialchars($metal['Metal_name']); ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="cost_price_<?php echo htmlspecialchars($metal['Id']); ?>" class="form-label">Cost Price ($)</label>
                            <input type="number" class="form-control" id="cost_price_<?php echo htmlspecialchars($metal['Id']); ?>" name="cost_price[]" value="<?php echo htmlspecialchars($metal['Cost_price']); ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="selling_price_<?php echo htmlspecialchars($metal['Id']); ?>" class="form-label">Selling Price  ($)</label>
                            <input type="number" class="form-control" id="selling_price_<?php echo htmlspecialchars($metal['Id']); ?>" name="selling_price[]" value="<?php echo htmlspecialchars($metal['Selling_price']); ?>" required>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="text-center">
            <button type="submit" class="btn btn-success">Update All</button>
        </div>
    </form>
</div>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
