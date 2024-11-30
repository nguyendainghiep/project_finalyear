<?php
include 'connection.php';

$categoryId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$page = isset($_GET['page_cate']) ? max(1, intval($_GET['page_cate'])) : 1;
$items_per_page = 8;
$offset = ($page - 1) * $items_per_page;

// Get category name
$sql_category = "SELECT Name FROM category WHERE id = $categoryId";
$result_category = $conn->query($sql_category);

if (!$result_category) {
    die("Query failed: " . $conn->error);
}

$category = $result_category->fetch_assoc();
$categoryName = $category ? htmlspecialchars($category['Name']) : '';

// Get total number of items in category
$sql_count = "SELECT COUNT(*) as total FROM item WHERE Category = $categoryId";
$total_result = $conn->query($sql_count);
if (!$total_result) {
    die("Total query failed: " . $conn->error);
}

$total_row = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_row / $items_per_page);

// Get items for current page
$sql = "SELECT * FROM item WHERE Category = $categoryId LIMIT $offset, $items_per_page";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Products</title>
    <style>
        /* Pagination styles */
        .pagination {
            display: flex;
            justify-content: center;
            padding: 20px 0;
        }

        .pagination .page-item {
            margin: 0 5px;
        }

        .pagination .page-item .page-link {
            color: #333;
            padding: 8px 12px;
            border: 1px solid #c0c0c0;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .pagination .page-item.active .page-link {
            background-color: #333;
            color: #fff;
            border-color: #333;
        }

        .pagination .page-item .page-link:hover {
            background-color: #555;
            color: #fff;
            border-color: #555;
        }
    </style>
</head>
<body>
    <section class="categories-page spad">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="search-results-heading" style="margin-bottom:20px;"><?php echo $categoryName; ?></h2>

                    <?php
                    echo "<p style='font-weight:bold;'>Total Items: $total_row</p>";

                    if ($result->num_rows > 0) {
                        echo '<div class="row" id="product-list">';
                        while ($row = $result->fetch_assoc()) {
                            $itemId = $row['id'];
                            $sql_images = "SELECT name AS img_name FROM imgitem WHERE itemId = $itemId AND place = 1 LIMIT 1";
                            $result_images = $conn->query($sql_images);
                            $img_name = $result_images->num_rows > 0 ? $result_images->fetch_assoc()['img_name'] : 'default.jpg';

                            echo '<div class="col-lg-3 col-md-4 col-sm-6 mb-4">';
                            echo '    <div class="single-product-item">';
                            echo '        <figure>';
                            echo '            <img src="style/template/img/products/' . htmlspecialchars($img_name) . '" alt="Product Image" class="img-fluid">';
                            echo '            <div class="p-status">New</div>';
                            echo '            <div class="hover-icon">';
                            echo '                <a href="style/template/img/products/' . htmlspecialchars($img_name) . '" class="pop-up"><img src="style/template/img/icons/zoom-plus.png" alt="Zoom"></a>';
                            echo '            </div>';
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
                        echo '<p class="no-results">No products found in this category.</p>';
                    }
                    ?>

                    <!-- Pagination -->
                    <nav>
    <ul class="pagination">
        <?php if ($page > 1) : ?>
            <li class="page-item">
                <a class="page-link" href="?page=find_category&id=<?php echo $categoryId; ?>&page_cate=<?php echo $page - 1; ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                <a class="page-link" href="?page=find_category&id=<?php echo $categoryId; ?>&page_cate=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $total_pages) : ?>
            <li class="page-item">
                <a class="page-link" href="?page=find_category&id=<?php echo $categoryId; ?>&page_cate=<?php echo $page + 1; ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
