<style>
/* styles.css */

/* Tiêu đề H2 cho kết quả tìm kiếm */
.search-results-heading {
    font-size: 28px; /* Kích thước chữ lớn hơn */
    font-weight: 700; /* Đậm hơn */
    color: #333; /* Màu chữ tối để dễ đọc */
    text-align: center; /* Căn giữa tiêu đề */
    margin-top: 20px; /* Khoảng cách phía trên */
    margin-bottom: 20px; /* Khoảng cách phía dưới */
    padding: 10px; /* Khoảng cách bên trong */
    border-bottom: 2px solid #c0c0c0; /* Đường viền dưới màu bạch kim */
    background-color: #f5f5f5; /* Nền bạch kim sáng hơn */
    border-radius: 5px; /* Bo tròn góc tiêu đề */
    word-wrap: break-word; /* Tự động xuống hàng khi quá dài */
    word-break: break-word; /* Chia từ nếu cần để xuống hàng */
}



</style>

<!-- search_results.php -->
<section class="categories-page spad">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php
                include_once("connection.php");

                if (isset($_GET['query'])) {
                    $query = $_GET['query'];
                    $query = mysqli_real_escape_string($conn, $query);

                    echo '<h2 class="search-results-heading">Results for "<span>' . htmlspecialchars($query) . '</span>"</h2>';

                    $sql = "SELECT * FROM item WHERE Name LIKE '%$query%'";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        echo '<div class="row" id="product-list">';
                        while ($row = mysqli_fetch_assoc($result)) {
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
                        echo '<p class="no-results">No results found.</p>';
                    }

                    mysqli_close($conn);
                } else {
                    echo '<p class="no-results">Please enter a search query.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</section>


