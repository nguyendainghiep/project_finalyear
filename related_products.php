
<!-- related_products.php -->
<?php

// Assume you have the current product's category ID and product ID
$currentProductId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the category ID of the current product
$sql_category = "SELECT Category FROM item WHERE id = $currentProductId";
$result_category = mysqli_query($conn, $sql_category);
$currentCategoryId = mysqli_fetch_assoc($result_category)['Category'];

// Fetch related products from the same category, excluding the current product, limit to 4
$sql_related = "SELECT * FROM item WHERE Category = $currentCategoryId AND id != $currentProductId LIMIT 4";
$result_related = mysqli_query($conn, $sql_related);

if ($result_related && mysqli_num_rows($result_related) > 0) {
    echo '<div class="row">';
    while ($row = mysqli_fetch_assoc($result_related)) {
        $itemId = $row['id'];
        $sql_images = "SELECT name AS img_name FROM imgitem WHERE itemId = $itemId AND place = 1 LIMIT 1";
        $result_images = $conn->query($sql_images);
        $img_name = $result_images->num_rows > 0 ? $result_images->fetch_assoc()['img_name'] : 'default.jpg';

        echo '<div class="col-lg-3 col-sm-6">';
        echo '    <div class="single-product-item">';
        echo '        <figure>';
        echo '            <a href="?page=detail_item&id=' . htmlspecialchars($row["id"]) . '"><img src="style/template/img/products/' . htmlspecialchars($img_name) . '" alt=""></a>';
        echo '            <div class="p-status">new</div>';
        echo '        </figure>';
        echo '        <div class="product-text">';
        echo '            <a href="?page=detail_item&id=' . htmlspecialchars($row["id"]) . '">';
        echo '                <h6>' . htmlspecialchars($row["Name"]) . '</h6>';
        echo '            </a>';
        echo '            <p>$' . htmlspecialchars($row['Final_price']) . '</p>';
        echo '        </div>';
        echo '    </div>';
        echo '</div>';
    }
    echo '</div>';
} else {
    echo '<p>No related products found.</p>';
}

mysqli_close($conn);
?>
