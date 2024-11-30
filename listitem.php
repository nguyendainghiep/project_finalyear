<?php
include 'connection.php';
$categoryOption = isset($_GET['category']) ? intval($_GET['category']) : 0;
$categoryQuery = '';
if (!empty($categoryOption)) {
    $categoryQuery = ' WHERE Category = ' . intval($categoryOption);
}
// Get total number of filtered items
$sql_count = "SELECT COUNT(*) as total FROM item" . $categoryQuery;
$total_result = $conn->query($sql_count);
if (!$total_result) {
    die("Total query failed: " . $conn->error);
}

// Sorting
$sortOption = isset($_GET['sort']) ? $_GET['sort'] : '';
$sortQuery = '';
if ($sortOption === 'newest') {
    $sortQuery = ' ORDER BY ReceiptDate DESC';
} elseif ($sortOption === 'oldest') {
    $sortQuery = ' ORDER BY ReceiptDate ASC';
} elseif ($sortOption === 'price_asc') {
    $sortQuery = ' ORDER BY Final_price ASC';
} elseif ($sortOption === 'price_desc') {
    $sortQuery = ' ORDER BY Final_price DESC';
}

// Category Filtering

// Get all categories
$sql_categories = "SELECT id, Name FROM category";
$result_categories = $conn->query($sql_categories);

if (!$result_categories) {
    die("Category query failed: " . $conn->error);
}


// Get items for current page
$sql_items = "SELECT * FROM item" . $categoryQuery . $sortQuery;
$result_items = $conn->query($sql_items);

if (!$result_items) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="style/template/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .product-controls li.active {
            font-weight: bold;
        }

        .product-controls li {
            cursor: pointer;
        }

        .single-product-item {
            margin-bottom: 30px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            padding: 20px 0;
            align-items: center;
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
    <section class="page-add" style="margin-bottom:20px">
        <div class="container">
            <div class="product-filter" style="margin-bottom:20px">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="section-title">
                            <h2>- List of Products</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="categories-page spad">
        <div class="container">
            <div class="categories-controls">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="categories-filter">
                            <div class="cf-left">
                                <form action="#">
                                    <select class="sort" id="sort-select">
                                        <option value="">Sort by</option>
                                        <option value="newest">Newest</option>
                                        <option value="oldest">Oldest</option>
                                        <option value="price_asc">Price: Low to High</option>
                                        <option value="price_desc">Price: High to Low</option>
                                    </select>
                                </form>
                            </div>
                            <div style="margin-left: 20px;" class="cf-left">
                                <form action="#">
                                    <select class="sort" id="category-select">
                                        <option value="">All Categories</option>
                                        <?php
                                        if ($result_categories->num_rows > 0) {
                                            while ($category = $result_categories->fetch_assoc()) {
                                                echo '<option value="' . htmlspecialchars($category['id']) . '">' . htmlspecialchars($category['Name']) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="product-list">
                <?php
                if ($result_items->num_rows > 0) {
                    while ($item = $result_items->fetch_assoc()) {
                        $itemId = $item['id'];
                        $sql_images = "SELECT name AS img_name FROM imgitem WHERE itemId = $itemId AND place = 1 LIMIT 1";
                        $result_images = $conn->query($sql_images);
                        $img_name = $result_images->num_rows > 0 ? $result_images->fetch_assoc()['img_name'] : 'default.jpg'; // Default image if none found

                        $itemCategoryId = htmlspecialchars($item['Category']);

                        echo '<div class="col-lg-3 col-md-3 single-product-item" data-type="' . $itemCategoryId . '">';
                        echo '    <a href="?page=detail_item&id=' . htmlspecialchars($item['id']) . '"><div class="single-product-item">';
                        echo '        <figure>';
                        echo '            <img src="style/template/img/products/' . htmlspecialchars($img_name) . '" alt="">';
                        echo '            <div class="p-status">new</div>';
                        echo '        </figure>';
                        echo '        <div class="product-text">';
                        echo '            <h6>' . htmlspecialchars($item["Name"]) . '</h6>';
                        echo '            <p>$' . htmlspecialchars($item['Final_price']) . '</p>'; // Update this with actual price if available
                        echo '        </div>';
                        echo '    </div></a>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="col-lg-12" id="no-products">No products found.</div>';
                }

                $conn->close();
                ?>
            </div>
            <nav>

            </nav>

        </div>
    </section>

    <script>
        $(document).ready(function() {
            function loadProducts(sortOption, categoryOption) {
                $.ajax({
                    url: 'index.php?page=listitem',
                    type: 'GET',
                    data: {
                        sort: sortOption,
                        category: categoryOption
                    },
                    success: function(data) {
                        var productData = $(data).find('#product-list').html();
                        var totalProducts = $(data).find('#total-products').text();
                        $('#product-list').html(productData);
                        $('#total-products').text(totalProducts);
                    }
                });
            }

            loadProducts('', '');

            $('#sort-select').change(function() {
                var sortOption = $(this).val();
                var categoryOption = $('#category-select').val();
                loadProducts(sortOption, categoryOption);
            });

            $('#category-select').change(function() {
                var categoryOption = $(this).val();
                var sortOption = $('#sort-select').val();
                loadProducts(sortOption, categoryOption);
            });
        });
    </script>
</body>

</html>