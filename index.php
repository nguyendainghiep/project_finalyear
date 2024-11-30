<!DOCTYPE html>
<html lang="zxx">
<style>
    .search-form input[type="text"] {
        width: calc(100% - 100px);
        /* Adjust the width based on your design */
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
        color: #333;
        background-color: #f9f9f9;
        box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.1);
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    .search-form input[type="text"]:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        outline: none;
    }

    .search-form button {
        width: 80px;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: #fff;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.3s;
        margin-left: 10px;
    }

    .search-form button:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Yoga Studio Template">
    <meta name="keywords" content="Yoga, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>May | Jewelry</title>

    <link href="https://fonts.googleapis.com/css?family=Amatic+SC:400,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="style/template/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="style/template/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="style/template/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="style/template/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="style/template/css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="style/template/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="style/template/css/style.css" type="text/css">
</head>
<?php
session_start();
include_once("connection.php");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<body>
    <div id="preloder">
        <div class="loader"></div>
    </div>
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch">+</div>
            <form class="search-model-form" action="index.php" method="GET">
                <input type="text" name="query" id="search-input" placeholder="Search here....." required>
                <input type="hidden" name="page" value="search_item">
            </form>
        </div>
    </div>
    <header class="header-section">
        <div class="container-fluid">
            <div class="inner-header">
                <div class="logo">
                    <a href="./index.php"><img style="width: 200px;height: 50px" src="style/template/img/logo.png" alt=""></a>
                </div>
                <div class="header-right">
                    <img src="style/template/img/icons/search.png" alt="" class="search-trigger">
                    <?php
                    if (isset($_SESSION['userid'])) {
                        echo '<a href="?page=information" style="margin-right: 22px;"><img src="style/template/img/icons/man.png" alt=""></a>';
                    } else {
                        echo '<a href="login.php"style="margin-right: 22px;"><img src="style/template/img/icons/man.png" alt=""></a>';
                    }
                    ?>

                    <?php
                    $itemCount = 0;
                    if (isset($_SESSION['username'])) {
                        $userId = $_SESSION['userid'];
                        $query = "
                            SELECT COUNT(od.id) AS itemCount 
                            FROM order_detail od 
                            JOIN `order` o ON od.OrderId = o.id 
                            WHERE o.userID = ? AND o.Status = 'pending'";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $userId);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($row = $result->fetch_assoc()) {
                            $itemCount = $row['itemCount'];
                        }
                    }

                    if (isset($_SESSION['username'])) {
                        echo '<a href="?page=cart"><img src="style/template/img/icons/bag.png" alt=""><span>' . ($itemCount ?: 0) . '</span></a>';
                    } else {
                        echo '<a href="login.php"><img src="style/template/img/icons/bag.png" alt=""><span>0</span></a>';
                    }
                    ?>
                </div>
                <div class="user-access">
                    <?php
                    if (isset($_SESSION['username'])) {
                        $firstname = $_SESSION['firstname'];
                        echo "<a href='#'>Hi, $firstname</a>";

                        echo "<a href='logout.php' class='in'>Logout</a>";
                    } else {
                        echo "<a href='register.php'>Register</a>";
                        echo "<a href='login.php' class='in'>Sign in</a>";
                    }
                    ?>
                </div>
                <nav class="main-menu mobile-menu">
                    <ul>
                        <li><a href="./index.php">Home</a></li>
                        <li><a href="#">Shop</a>
                            <ul class="sub-menu">
                                <li><a href="?page=listitem">Product Page</a></li>
                                <li>
                                    <?php
                                    if (isset($_SESSION['username'])) {
                                        echo '<a href="?page=cart">Shopping Cart</a>';
                                    } else {
                                        echo '<a href="login.php">Shopping Cart</a>';
                                    }
                                    ?>
                                </li>
                                <li>
                                    <?php
                                    if (isset($_SESSION['username'])) {
                                        echo '<a href="?page=customize_order">Request Custom Item</a>';
                                    } else {
                                        echo '<a href="login.php">Request Custom Item</a>';
                                    }
                                    ?> </li>
                                <li><a href="?page=metalprice">Metal Price</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Categories</a>
                            <ul class="sub-menu">
                                <?php
                                include_once("connection.php");

                                $sql = "SELECT id, Name FROM category";
                                $result = mysqli_query($conn, $sql);

                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<li><a href="?page=find_category&id=' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['Name']) . '</a></li>';
                                    }
                                } else {
                                    echo '<li><a href="#">No Categories Available</a></li>';
                                }
                                ?>
                            </ul>
                        </li>
                        <li><a href="?page=about">About</a></li>
                        <li>
                            <?php
                            if (isset($_SESSION['username'])) {
                                echo '<a href="?page=cus_chat&id=' . $userId . '">Chat</a>';
                            } else {
                                echo '<a href="login.php">Chat</a>';
                            }
                            ?>
                        </li>
                        <li><a href="?page=blog">Blog</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <!-- Header Info Begin -->

    <?php
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
        if ($page == "listitem") {
            include_once("listitem.php");
        }
        if ($page == "checkout") {
            include_once("check_out.php");
        }
        if ($page == "detail_item") {
            include_once("product_page.php");
        }
        if ($page == "cart") {
            include_once("shopping_cart.php");
        }
        if ($page == "metalprice") {
            include_once("metal_price.php");
        }
        if ($page == "addtocart") {
            include_once("add_to_cart.php");
        }
        if ($page == "insertitem") {
            include_once("insert_item.php");
        }
        if ($page == "search_item") {
            include_once("search.php");
        }
        if ($page == "payment") {
            include_once("payment.php");
        }
        if ($page == "order_complete") {
            include_once("order_complete.php");
        }
        if ($page == "customize_order") {
            include_once("customize_order.php");
        }
        if ($page == "about") {
            include_once("about.php");
        }
        if ($page == "information") {
            include_once("information.php");
        }
        if ($page == "personal_order_cart") {
            include_once("customize_odcart.php");
        }
        if ($page == "history_order") {
            include_once("history_order.php");
        }
        if ($page == "about") {
            include_once("about.php");
        }
        if ($page == "blog") {
            include_once("blog.php");
        }
        if ($page == "delete_cart_item") {
            include_once("delete_cart_item.php");
        }
        if ($page == "update_info") {
            include_once("update_info.php");
        }
        if ($page == "change_password") {
            include_once("change_password.php");
        }
        if ($page == "view_detail") {
            include_once("view_detail.php");
        }
        if ($page == "find_category") {
            include_once("find_category.php");
        }
        if ($page == "cus_chat") {
            include_once("cus_chat.php");
        }
        if ($page == "blog1") {
            include_once("blog1.php");
        }
        if ($page == "blog2") {
            include_once("blog2.php");
        }
        if ($page == "blog_sale") {
            include_once("blog_sale.php");
        }
    } else {
        include_once("homepage.php");
    }
    ?>


    <!-- Footer Section Begin -->
    <footer class="footer-section" style="padding-top:40px; padding-bottom:20px;">
        <div class="container">
            <div class="social-links">
                <a href="https://www.instagram.com/" class="instagram"><i class="fa fa-instagram"></i><span>instagram</span></a>
                <a href="https://www.pinterest.com/" class="pinterest"><i class="fa fa-pinterest"></i><span>pinterest</span></a>
                <a href="https://www.facebook.com/GreenwichVietnam" class="facebook"><i class="fa fa-facebook"></i><span>facebook</span></a>
                <a href="https://x.com/" class="twitter"><i class="fa fa-twitter"></i><span>twitter</span></a>
                <a href="https://youtube.com/" class="youtube"><i class="fa fa-youtube"></i><span>youtube</span></a>
            </div>
        </div>

        <div class="container text-center pt-5">
            <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                Copyright &copy;<script>
                    document.write(new Date().getFullYear());
                </script> All rights reserved
                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            </p>
        </div>
    </footer>
    <!-- Footer Section End -->

    <!-- Js Plugins -->
    <script src="style/template/js/jquery-3.3.1.min.js"></script>
    <script src="style/template/js/bootstrap.min.js"></script>
    <script src="style/template/js/jquery.magnific-popup.min.js"></script>
    <script src="style/template/js/masonry.pkgd.min.js"></script>
    <script src="style/template/js/jquery.nice-select.min.js"></script>
    <script src="style/template/js/jquery.slicknav.js"></script>
    <script src="style/template/js/owl.carousel.min.js"></script>
    <script src="style/template/js/main.js"></script>

</body>

</html>