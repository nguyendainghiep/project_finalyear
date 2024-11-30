<?php
include 'connection.php'; // Kết nối đến cơ sở dữ liệu

// Lấy ID sản phẩm từ URL
$item_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($item_id > 0) {
    // Truy vấn dữ liệu chi tiết sản phẩm
    $sql = "
        SELECT 
            i.id, 
            i.Name, 
            c.Name AS CategoryName, 
            m.Metal_name AS MetalName, 
            s.name AS StoneName, 
            sup.Name AS SupplierName, 
            i.Quantity, 
            i.Description, 
            i.Metal_weight,
            i.Stone_weight,
            i.Final_price, 
            i.ReceiptDate,
            img1.name AS Image1, 
            img2.name AS Image2
        FROM 
            item i
        LEFT JOIN 
            category c ON i.Category = c.id
        LEFT JOIN 
            metal m ON i.Metal = m.id
        LEFT JOIN 
            stone s ON i.Stone = s.id
        LEFT JOIN 
            supplier sup ON i.Supplier = sup.id
        LEFT JOIN 
            imgitem img1 ON i.id = img1.itemId AND img1.place = 1
        LEFT JOIN 
            imgitem img2 ON i.id = img2.itemId AND img2.place = 2
        WHERE 
            i.id = $item_id
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
    } else {
        echo "Item not found.";
        exit;
    }
} else {
    echo "Invalid item ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Detail</title>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Item Detail</h2>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($item['Name']); ?></h5>
                        <p class="card-text"><strong>Category:</strong> <?php echo htmlspecialchars($item['CategoryName']); ?></p>
                        <p class="card-text"><strong>Metal:</strong> <?php echo htmlspecialchars($item['MetalName']); ?></p>
                        <p class="card-text"><strong>Stone:</strong> <?php echo htmlspecialchars($item['StoneName']); ?></p>
                        <p class="card-text"><strong>Supplier:</strong> <?php echo htmlspecialchars($item['SupplierName']); ?></p>
                        <p class="card-text"><strong>Quantity:</strong> <?php echo htmlspecialchars($item['Quantity']); ?></p>
                        <p class="card-text"><strong>Metal Weight:</strong> <?php echo htmlspecialchars($item['Metal_weight']); ?> grams</p>
                        <p class="card-text"><strong>Stone Weight:</strong> <?php echo htmlspecialchars($item['Stone_weight']); ?> carats</p>
                        <p class="card-text"><strong>Final Price:</strong> $<?php echo htmlspecialchars($item['Final_price']); ?></p>
                        <p class="card-text"><strong>Description:</strong> <?php echo htmlspecialchars($item['Description']); ?></p>
                        <p class="card-text"><strong>Receipt Date:</strong> <?php echo htmlspecialchars($item['ReceiptDate']); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Product Images</h5>
                        <div class="d-flex">
                            <img src="style/template/img/products/<?php echo htmlspecialchars($item['Image1']); ?>" alt="Image 1" class="img-fluid me-2" style="width: 200px; height: 200px; object-fit: cover;">
                            <img src="style/template/img/products/<?php echo htmlspecialchars($item['Image2']); ?>" alt="Image 2" class="img-fluid" style="width: 200px; height: 200px; object-fit: cover;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
 
        <div class="mt-3">
            <a href="javascript:history.back()" class="btn btn-secondary">Back to Item Management</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
