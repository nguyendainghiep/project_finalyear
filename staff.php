<?php
session_start();

include_once("connection.php");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$fname = $_SESSION['firstname'];
$lname = $_SESSION['lastname'];
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="style/admin/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="style/admin/assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="style/admin/assets/libs/css/style.css">
    <link rel="stylesheet" href="style/admin/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" href="style/admin/assets/vendor/charts/chartist-bundle/chartist.css">
    <link rel="stylesheet" href="style/admin/assets/vendor/charts/morris-bundle/morris.css">
    <link rel="stylesheet" href="style/admin/assets/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="style/admin/assets/vendor/charts/c3charts/c3.css">
    <link rel="stylesheet" href="style/admin/assets/vendor/fonts/flag-icon-css/flag-icon.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <title>May - Staff</title>
    <style>
        .btn-update-custom,
        .btn-delete-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem;
            font-size: 1rem;
            border: none;
            background: transparent;
        }
        .btn-update-custom i,
        .btn-delete-custom i {
            font-size: 1.2rem;
        }
        .btn-update-custom {
            color: #007bff;
        }
        .btn-update-custom:hover i {
            color: #0056b3;
        }
        .btn-delete-custom {
            color: #dc3545;
        }

        .btn-delete-custom:hover i {
            color: #c82333;
        }

        .btn-add-custom {
            background-color: #28a745;
            color: #fff;
            border: 1px solid #28a745;
            border-radius: 0.25rem;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .btn-add-custom:hover {
            background-color: #218838;
            border-color: #1e7e34;
            color: #fff;
        }
        .btn-add-custom:focus {
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.5);
        }
        .custom-pagination {
            display: flex;
            justify-content: center;
            padding: 0;
            margin: 20px 0;
            list-style: none;
        }
        .pagination-item {
            margin: 0 5px;
        }
        .pagination-link {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            border-radius: 6px;
            border: 1px solid #ddd;
            background-color: #f8f9fa;
            transition: background-color 0.3s, color 0.3s, border-color 0.3s;
            font-size: 1rem;
            font-weight: 500;
        }
        .pagination-link:hover {
            background-color: #e9ecef;
            color: #0056b3;
            border-color: #007bff;
        }
        .pagination-item.active .pagination-link {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }
        .pagination-link[aria-label="Previous"],
        .pagination-link[aria-label="Next"] {
            font-size: 1.25rem;
        }
        .pagination-item:first-child .pagination-link {
            border-radius: 6px 0 0 6px;
        }

        .pagination-item:last-child .pagination-link {
            border-radius: 0 6px 6px 0;
        }
        .custom-search-group {
            display: flex;
            max-width: 400px;
            border-radius: 2px;
            overflow: hidden;
        }
        .custom-form-control {
            border: 1px solid #ced4da;
            border-radius: 3px 0 0 3px;
            padding: 10px 15px;
            font-size: 1rem;
            flex: 1;
            transition: border-color 0.3s ease;
        }
        .custom-form-control:focus {
            border-color: #0056b3;
            outline: none;
        }
        .custom-btn-search {
            border: none;
            border-radius: 0 3px 3px 0;
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .custom-btn-search:hover {
            background-color: #0056b3;
        }
        .custom-dropdown {
            margin-bottom: 1rem;
        }

        .custom-dropdown .form-select {
            display: block;
            width: 100%;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            padding: 0.75rem 1.25rem;
            font-size: 1rem;
            color: #495057;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .custom-dropdown .form-select:focus {
            border-color: #0056b3;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .custom-dropdown .form-select:disabled {
            background-color: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <div class="dashboard-main-wrapper">
        <div class="dashboard-header">
            <nav class="navbar navbar-expand-lg bg-white fixed-top">
                <a style="color: #b53e79;" class="navbar-brand" href="staff.php">MAY-STAFF</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse " id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar-right-top">
                        <li class="nav-item dropdown nav-user">
                            <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="style/admin/assets/images/avatar-1.jpg" alt="" class="user-avatar-md rounded-circle"></a>
                            <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                                <div class="nav-user-info">
                                <h5 class="mb-0 text-white nav-user-name"><?php echo $fname . ' ' . $lname; ?></h5>
                                    <span class="status"></span><span class="ml-2">Administrators</span>
                                </div>
                                <a class="dropdown-item" href="logout.php"><i class="fas fa-power-off mr-2"></i>Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="nav-left-sidebar sidebar-dark">
            <div class="menu-list">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="d-xl-none d-lg-none" href="#">Dashboard</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="?page=staff_order"><i class="fa-solid fa-clock-rotate-left"></i>Pending Order Management</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?page=inventory"><i class="fa-solid fa-box-open"></i>Inventory Management</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?page=customer_contact"><i class="fa-solid fa-headset"></i>Customer Support</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?page=custom_order_mgr"><i class="fa-solid fa-hammer"></i>Requested Custom Order</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?page=feedback_management"><i class="fa-regular fa-message"></i>Feedback Management</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <div class="dashboard-wrapper" >
            <div class="dashboard-ecommerce" >
                <div class="container-fluid dashboard-content ">
                    <div class="ecommerce-widget" >
                        <!--Nguyen Dai Nghiep -->
                        <?php
                        if (isset($_GET['page'])) {
                            $page = $_GET['page'];
                            if ($page == "staff_order") {
                                include_once("staff_order.php");
                            }
                            if ($page == "staff_detail_order") {
                                include_once("staff_detail_order.php");
                            }
                            if ($page == "customer_contact") {
                                include_once("customer_contact.php");
                            }
                            if ($page == "chat") {
                                include_once("chat.php");
                            }
                            if ($page == "inventory") {
                                include_once("inventory.php");
                            }
                            if ($page == "detail_item") {
                                include_once("item_detail.php");
                            }
                            if ($page == "add_item") {
                                include_once("add_item.php");
                            }
                            if ($page == "confirm_delete_item") {
                                include_once("confirm_delete_item.php");
                            }
                            if ($page == "item_delete_success") {
                                include_once("item_delete_success.php");
                            }
                            if ($page == "update_item") {
                                include_once("update_item.php");
                            }
                            if ($page == "custom_order_mgr") {
                                include_once("custom_order_mgr.php");
                            }
                            if ($page == "feedback_management") {
                                include_once("feedback_management.php");
                            }
                            if ($page == "view_feedback") {
                                include_once("view_feedback.php");
                            }
                        } else {
                            include_once("staff_index.php");
                        }
                        ?>

                    </div>
                </div>
            </div>
            <div class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            Copyright © 2018 Concept. All rights reserved.
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="text-md-right footer-links d-none d-sm-block">
                                <a href="javascript: void(0);">About</a>
                                <a href="javascript: void(0);">Support</a>
                                <a href="javascript: void(0);">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="style/admin/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="style/admin/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="style/admin/assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <script src="style/admin/assets/libs/js/main-js.js"></script>
    <script src="style/admin/assets/vendor/charts/chartist-bundle/chartist.min.js"></script>
    <script src="style/admin/assets/vendor/charts/sparkline/jquery.sparkline.js"></script>
    <script src="style/admin/assets/vendor/charts/morris-bundle/raphael.min.js"></script>
    <script src="style/admin/assets/vendor/charts/morris-bundle/morris.js"></script>
    <script src="style/admin/assets/vendor/charts/c3charts/c3.min.js"></script>
    <script src="style/admin/assets/vendor/charts/c3charts/d3-5.4.0.min.js"></script>
    <script src="style/admin/assets/vendor/charts/c3charts/C3chartjs.js"></script>
    <script src="style/admin/assets/libs/js/dashboard-ecommerce.js"></script>
</body>

</html>