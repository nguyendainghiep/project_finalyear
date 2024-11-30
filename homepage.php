<style>
    .promotion-item {
    position: relative;
    border-radius: 10px;
}

.promotion-item img {
    transition: transform 0.3s ease, filter 0.3s ease;
    width: 100%;
    border-radius: 10px;
}

.promotion-item:hover img {
    transform: scale(1.05);
    filter: brightness(0.8);
}

.promotion-text {

    top: 50%;
    left: 50%;

    color: white;
    text-align: center;

}

.promotion-item:hover .promotion-text {
    opacity: 1;
}

.primary-btn img {
    border-radius: 10px;
}

</style>
<body>

<section class="hero-slider">
        <div class="hero-items owl-carousel">
            <div class="single-slider-item set-bg" data-setbg="style/template/img/slider-3.png">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <h1>2024</h1>
                            <h2>May jewelry.</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="single-slider-item set-bg" data-setbg="style/template/img/slider2.jpg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <h1>2024</h1>
                            <h2>May jewelry.</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="single-slider-item set-bg" data-setbg="style/template/img/slider1.png">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <h1>,</h1>
                            <h2>,</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- Promotions Section Begin -->
<section class="promotions-section spad">
    <div class="container">
        <div class="section-title text-center">
            <h2>Our Promotions</h2>
        </div>
        <div class="row">
            <div class="col-lg-4 col-sm-6">
                <div class="promotion-item">
                    <img src="style/template/img/promo1.jpg" alt="">
                    <div class="promotion-text">
                        <h4>Summer Sale</h4>
                        <p>Up to 10% off on selected items!</p>
                        <a href="?page=blog_sale" style="padding: 0; width:250px; height:fit-content;" class="primary-btn"><img src="style/template/img/sales.jpg" alt=""></a>
                    </div>
                </div>
            </div>
            <!-- Add more promotion items as needed -->
            <div class="col-lg-4 col-sm-6">
                <div class="promotion-item">
                    <img src="style/template/img/promo1.jpg" alt="">
                    <div class="promotion-text">
                        <h4>Free consultation</h4>
                        <p>We always support you warmly.</p>
                        <?php
                                    if (isset($_SESSION['username'])) {
                        echo '<a href="?page=cus_chat&id='. $userId. '" style="padding: 0; width:250px; height:fit-content;" class="primary-btn"><img src="style/template/img/phone.jpg" alt=""></a>';
                    } else {
                        echo '<a href="login.php" style="padding: 0; width:250px; height:fit-content;" class="primary-btn"><img src="style/template/img/phone.jpg" alt=""></a>';

                    }
                                    ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="promotion-item">
                    <img src="style/template/img/promo1.jpg" alt="">
                    <div class="promotion-text">
                        <h4>Crafted upon request</h4>
                        <p>Give us your preferences.</p>
                        <a href="?page=customize_order" style="padding: 0; width:250px; height:fit-content;" class="primary-btn"><img src="style/template/img/carft.jpg" alt=""></a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Promotions Section End -->

<!-- Latest Product Begin -->
<section class="latest-products spad">
    <div class="container">
        <div class="product-filter">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="section-title">
                        <h2>Newest Products</h2>
                    </div>
                </div>
            </div>
        </div>
        <?php
        include_once("connection.php");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL statement to fetch data from item table, limit 8 products
        $sql = "SELECT item.*, imgitem.name as image_name, imgitem.place
                FROM item
                LEFT JOIN imgitem ON imgitem.itemId = item.id AND imgitem.place = 1
                ORDER BY item.ReceiptDate DESC
                LIMIT 8";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $count = 0; // Counter for the number of displayed products
            echo '<div class="row">'; // Start a new row
            
            // Loop through the results and display each product
            while ($row = $result->fetch_assoc()) {
                // Close the previous row and start a new one if 4 products are displayed
                if ($count % 4 == 0 && $count != 0) {
                    echo '</div>'; // Close the old row
                    echo '<div class="row">'; // Open a new row
                }

                // Handle images
                $image_src = 'style/template/img/products/default.jpg'; // Default image
                if (!empty($row['image_name'])) {
                    $image_src = 'style/template/img/products/' . htmlspecialchars($row['image_name']);
                }

                // Build HTML for each product
                echo '<div class="col-lg-3 col-sm-6 mix all dresses bags">';
                echo '<div class="single-product-item">';
                echo '<figure>';
                echo '<a href="?page=detail_item&id='. htmlspecialchars($row['id']) .'"><img src="'. $image_src .'" alt=""></a>';
                echo '<div class="p-status">new</div>'; // Or 'sale' depending on the case
                echo '</figure>';
                echo '<div class="product-text">';
                echo '<h6>' . htmlspecialchars($row['Name']) . '</h6>';
                echo '<p>$' . htmlspecialchars($row['Final_price']) . '</p>'; // Product price (needs adjustment)
                echo '</div>';
                echo '</div>';
                echo '</div>';
                
                $count++; // Increment the counter for displayed products
            }
            
            // Close the last row if needed
            if ($count % 4 != 0) {
                echo '</div>';
            }
        } else {
            echo "No products available.";
        }

        // Close connection
        $conn->close();
        ?>
    </div>
</section>
<!-- Latest Product End -->

<!-- Lookbok Section Begin -->
<section class="lookbok-section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 offset-lg-1">
                <div class="lookbok-left">
                    <div class="section-title">
                        <h2>2023 <br />#NiceCollection</h2>
                    </div>
                    <p>Discover the allure of our exquisite jewelry collection, 
                        where craftsmanship meets elegance in every piece. 
                        Each item is meticulously curated to reflect timeless beauty
                         and sophistication. From shimmering diamonds set in intricate
                          designs to lustrous pearls that exude classic charm, our
                           collection offers something extraordinary for every occasion.
                            Whether adorning yourself for a special event or searching for
                             the perfect gift, our jewelry embodies luxury and craftsmanship that
                              transcends trends, ensuring you feel both stunning and timeless.</p>
                    <a href="?page=blog" class="primary-btn look-btn">See More</a>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1">
                <div class="lookbok-pic">
                    <img src="style/template/img/nicecollect.jpg" alt="">
                    <div class="pic-text">MayJw.</div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Lookbok Section End -->

<!-- Logo Section Begin -->
<div class="logo-section spad">
    <div class="logo-items owl-carousel">
        <!-- Add logo items here -->
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var myCarousel = document.querySelector('#customCarouselExample');
        var carousel = new bootstrap.Carousel(myCarousel, {
            interval: 5000, // Change slide every 5 seconds
            ride: 'carousel'
        });
    });
</script>
</body>
</html>
