<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: owner_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<style>
    /* General styling for body */
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f7f7f7;
        color: #333;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    /* Header */
    .header {
        background-color: #d7ccc8; /* Light brown color */
        border-bottom: 1px solid #bdbdbd; /* Light gray bottom border */
        padding: 15px 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    /* Header Title */
    .header-title {
        margin: 0;
        font-size: 3rem; /* Kích thước chữ lớn */
        color: #4e342e; /* Màu nâu đậm */
        text-align: center;
        font-weight: 700;
        letter-spacing: 2px; /* Khoảng cách giữa các ký tự */
        position: relative;
        background: linear-gradient(45deg, #d7ccc8, #d7ccc8); /* Màu nền gradient */
        border-radius: 8px; /* Bo góc nhẹ */
        font-family: monospace;
    }

    /* Đổ bóng cho chữ */
    .header-title::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        z-index: -1;
        transform: scale(1.05);
    }

    .navigation {
        margin-top: 10px;
    }

    .navigation ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
        text-align: center;
    }

    .navigation ul li {
        display: inline;
        margin: 0;
    }

    .navigation .nav-link {
        display: inline-block;
        color: #000; /* Black text color */
        font-size: 1rem;
        text-decoration: none;
        padding: 10px 20px;
        margin: 0;
        border-radius: 4px; /* Slightly rounded corners */
        background-color: #fafafa; /* Neutral brown color */
        transition: all 0.3s ease;
        font-weight: 500;
        border: #fafafa 2px solid;
    }

    .navigation .nav-link.active,
    .navigation .nav-link:hover {
        color: #000; /* White text color on hover or active */
        background-color: #c4c4c4;
        border: #000 2px solid; /* Lighter brown on hover or active */
    }

    .navigation .nav-link-logout {
        display: inline-block;
        color: #000; /* Black text color */
        font-size: 1rem;
        text-decoration: none;
        padding: 10px 20px;
        margin: 0;
        border-radius: 4px; /* Slightly rounded corners */
        background-color: #b0a4a6; /* Neutral brown color */
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .navigation .nav-link-logout.active,
    .navigation .nav-link-logout:hover {
        color: #ffffff; /* White text color on hover or active */
        background-color: #f03a48; /* Lighter brown on hover or active */
    }

    /* Main content */
    main {
        margin-top: 30px;
        flex: 1;
    }

    /* Footer */
    .footer {
        background-color: #d7ccc8; /* Light brown color */
        color: #5d4037; /* Darker brown text color */
        padding: 15px 0;
        width: 100%;
        text-align: center;
    }

    .footer p {
        margin: 0;
        font-size: 0.9rem;
    }

    /* Responsive */
    @media (max-width: 767px) {
        .header-title {
            font-size: 1.5rem;
        }

        .navigation ul li {
            display: block;
            margin: 5px 0;
        }
    }
</style>
<body>

<!-- Header -->
<header class="header">
    <div class="container">
        <h1 class="header-title">MAY Jewellry - Owner</h1>
        <nav class="navigation">
            <ul>
                <li><a class="nav-link" href="?page=owner">Home</a></li>
                <li><a class="nav-link" href="?page=sales_chart">Sales</a></li>
                <li><a class="nav-link" href="?page=order_chart">Order</a></li>
                <li><a class="nav-link" href="?page=product_chart">Product</a></li>
                <li><a class="nav-link" href="?page=user_charts">Account</a></li>
                <li><a class="nav-link-logout" href="owner_logout.php">Logout</a></li> <!-- Updated link for Logout -->
            </ul>
        </nav>
    </div>
</header>

<!-- Main content -->
<main class="container my-4">
<?php
                        if (isset($_GET['page'])) {
                            $page = $_GET['page'];
                            if ($page == "owner") {
                                include_once("owner_index.php");
                            }
                            if ($page == "user_charts") {
                                include_once("user_charts.php");
                            }
                            if ($page == "sales_chart") {
                                include_once("sales_chart.php");
                            }
                            if ($page == "order_chart") {
                                include_once("order_chart.php");
                            }
                            if ($page == "product_chart") {
                                include_once("product_chart.php");
                            }
                        } else {
                            include_once("owner_index.php");
                        }
                        ?>    
</main>

<!-- Footer -->
<footer class="footer">
    <div class="container text-center">
        <p class="mb-0">&copy; 2024 MAY Jewellry. All Rights Reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
</body>
</html>
