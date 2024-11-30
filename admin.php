<?php
session_start(); // Start the session

include_once("connection.php");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Ensure session variables are set before accessing them
$fname = $_SESSION['firstname'];
$lname = $_SESSION['lastname'];
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
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


    <title>May - Admin</title>
    <style>
        /* Loại bỏ nền và đường viền cho các nút */
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

        /* Căn chỉnh kích thước biểu tượng */
        .btn-update-custom i,
        .btn-delete-custom i {
            font-size: 1.2rem;
            /* Kích thước biểu tượng */
        }

        /* Nút Update */
        .btn-update-custom {
            color: #007bff;
            /* Màu lạnh cho biểu tượng */
        }

        .btn-update-custom:hover i {
            color: #0056b3;
            /* Màu lạnh hơn khi hover */
        }

        /* Nút Delete */
        .btn-delete-custom {
            color: #dc3545;
            /* Màu đỏ cho biểu tượng */
        }

        .btn-delete-custom:hover i {
            color: #c82333;
            /* Màu đỏ đậm hơn khi hover */
        }

        /* Lớp CSS tùy chỉnh cho nút Add */
        .btn-add-custom {
            background-color: #28a745;
            /* Màu nền xanh lá cây */
            color: #fff;
            /* Màu chữ trắng */
            border: 1px solid #28a745;
            /* Đường viền xanh lá cây */
            border-radius: 0.25rem;
            /* Bo góc nút */
            padding: 0.5rem 1rem;
            /* Khoảng cách bên trong nút */
            font-size: 1rem;
            /* Kích thước chữ */
            font-weight: bold;
            /* Chữ đậm */
            text-align: center;
            /* Canh giữa chữ */
            text-decoration: none;
            /* Bỏ gạch chân */
            display: inline-block;
            /* Hiển thị inline-block để căn chỉnh tốt hơn */
        }

        /* Hiệu ứng hover cho nút Add */
        .btn-add-custom:hover {
            background-color: #218838;
            /* Màu nền xanh lá cây đậm hơn khi hover */
            border-color: #1e7e34;
            /* Đường viền xanh lá cây đậm hơn khi hover */
            color: #fff;
            /* Màu chữ vẫn trắng khi hover */
        }

        /* Hiệu ứng focus cho nút Add */
        .btn-add-custom:focus {
            outline: none;
            /* Bỏ viền khi nút được chọn */
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.5);
            /* Hiệu ứng đổ bóng khi focus */
        }

        /* Kiểu chung cho phân trang */
        .custom-pagination {
            display: flex;
            justify-content: center;
            padding: 0;
            margin: 20px 0;
            list-style: none;
        }

        /* Kiểu cho từng phần tử phân trang */
        .pagination-item {
            margin: 0 5px;
        }

        /* Kiểu cho liên kết phân trang */
        .pagination-link {
            display: block;
            padding: 10px 15px;
            color: #333;
            /* Màu chữ chính */
            text-decoration: none;
            /* Bỏ gạch chân */
            border-radius: 6px;
            /* Bo góc */
            border: 1px solid #ddd;
            /* Đường viền nhạt */
            background-color: #f8f9fa;
            /* Màu nền nhạt */
            transition: background-color 0.3s, color 0.3s, border-color 0.3s;
            /* Hiệu ứng chuyển tiếp */
            font-size: 1rem;
            /* Kích thước chữ */
            font-weight: 500;
            /* Độ đậm chữ */
        }

        /* Hiệu ứng khi di chuột qua liên kết phân trang */
        .pagination-link:hover {
            background-color: #e9ecef;
            /* Màu nền khi hover */
            color: #0056b3;
            /* Màu chữ khi hover */
            border-color: #007bff;
            /* Đường viền khi hover */
        }

        /* Kiểu cho phần tử phân trang đang hoạt động */
        .pagination-item.active .pagination-link {
            background-color: #007bff;
            /* Màu nền cho phần tử active */
            color: #fff;
            /* Màu chữ cho phần tử active */
            border-color: #007bff;
            /* Đường viền cho phần tử active */
        }

        /* Kiểu cho các mũi tên điều hướng */
        .pagination-link[aria-label="Previous"],
        .pagination-link[aria-label="Next"] {
            font-size: 1.25rem;
            /* Kích thước chữ lớn hơn cho mũi tên */
        }

        /* Tùy chỉnh border cho phân trang */
        .pagination-item:first-child .pagination-link {
            border-radius: 6px 0 0 6px;
            /* Bo góc trái cho phần tử đầu tiên */
        }

        .pagination-item:last-child .pagination-link {
            border-radius: 0 6px 6px 0;
            /* Bo góc phải cho phần tử cuối cùng */
        }

        /* Kiểu cho nhóm đầu vào tìm kiếm */
        .custom-search-group {
            display: flex;
            max-width: 400px;
            border-radius: 2px;
            /* Bo góc nhẹ */
            overflow: hidden;
            /* Ẩn các phần tử ngoài vùng bo góc */
        }

        /* Kiểu cho ô nhập liệu */
        .custom-form-control {
            border: 1px solid #ced4da;
            /* Đường viền màu sáng */
            border-radius: 3px 0 0 3px;
            /* Bo góc trái cho ô nhập liệu */
            padding: 10px 15px;
            /* Padding cho ô nhập liệu */
            font-size: 1rem;
            /* Kích thước chữ */
            flex: 1;
            /* Chiếm toàn bộ không gian còn lại */
            transition: border-color 0.3s ease;
            /* Hiệu ứng chuyển tiếp cho đường viền */
        }

        /* Hiệu ứng khi di chuột vào ô nhập liệu */
        .custom-form-control:focus {
            border-color: #0056b3;
            /* Đổi màu đường viền khi focus */
            outline: none;
            /* Loại bỏ outline mặc định */
        }

        /* Kiểu cho nút tìm kiếm */
        .custom-btn-search {
            border: none;
            /* Loại bỏ đường viền mặc định */
            border-radius: 0 3px 3px 0;
            /* Bo góc phải cho nút */
            padding: 10px 20px;
            /* Padding cho nút */
            font-size: 1rem;
            /* Kích thước chữ */
            background-color: #007bff;
            /* Màu nền của nút */
            color: #fff;
            /* Màu chữ của nút */
            cursor: pointer;
            /* Con trỏ khi hover */
            transition: background-color 0.3s ease;
            /* Hiệu ứng chuyển tiếp */
        }

        /* Hiệu ứng khi di chuột vào nút tìm kiếm */
        .custom-btn-search:hover {
            background-color: #0056b3;
            /* Đổi màu nền khi hover */
        }

        /* Kiểu cho dropdown trong form */
        .custom-dropdown {
            margin-bottom: 1rem;
            /* Khoảng cách bên dưới dropdown */
        }

        .custom-dropdown .form-select {
            display: block;
            /* Đảm bảo chiếm toàn bộ chiều rộng */
            width: 100%;
            /* Chiếm toàn bộ chiều rộng của phần tử chứa */
            background-color: #f8f9fa;
            /* Màu nền nhẹ */
            border: 1px solid #ced4da;
            /* Đường viền sáng */
            border-radius: 0.25rem;
            /* Bo góc nhẹ */
            padding: 0.75rem 1.25rem;
            /* Padding cho dropdown */
            font-size: 1rem;
            /* Kích thước chữ */
            color: #495057;
            /* Màu chữ */
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            /* Hiệu ứng chuyển tiếp */
        }

        /* Hiệu ứng khi focus vào dropdown */
        .custom-dropdown .form-select:focus {
            border-color: #0056b3;
            /* Đổi màu đường viền khi focus */
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            /* Hiệu ứng đổ bóng khi focus */
        }

        /* Hiệu ứng cho dropdown khi bị vô hiệu hóa */
        .custom-dropdown .form-select:disabled {
            background-color: #e9ecef;
            /* Màu nền khi bị vô hiệu hóa */
            color: #6c757d;
            /* Màu chữ khi bị vô hiệu hóa */
            cursor: not-allowed;
            /* Con trỏ khi bị vô hiệu hóa */
        }
    </style>
</head>

<body>
    <!-- ============================================================== -->
    <div class="dashboard-main-wrapper">
        <!-- ============================================================== -->
        <div class="dashboard-header">
            <nav class="navbar navbar-expand-lg bg-white fixed-top">
                <a style="color: #b53e79;" class="navbar-brand" href="admin.php">MAY-ADMIN</a>
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
        <!-- ============================================================== -->
        <div class="nav-left-sidebar sidebar-dark">
            <div class="menu-list">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="d-xl-none d-lg-none" href="#">Dashboard</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav flex-column">
                            <li class="nav-item ">
                                <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-1" aria-controls="submenu-1"><i class="fa fa-shop"></i>Manage Store <span class="badge badge-success">6</span></a>
                                <div id="submenu-1" class="collapse submenu">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="?page=item">Items</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="?page=category">Categories</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="?page=stone">Stone</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="?page=metal">Metal</a>
                                        </li>

                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?page=user"><i class="fa fa-fw fa-user-circle"></i>Manage User</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?page=order"><i class="fa fa-fw fa-newspaper"></i>Manage Order</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?page=personal_order"><i class="fab fa-fw fa-wpforms"></i>Requested Custom Order</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?page=updatemetalprice"><i class="fas fa-fw fa-table"></i>Update Gold Price</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?page=manage_payment"><i class="fa-solid fa-coins"></i>View List Payment</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- end left sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        <div class="dashboard-wrapper">
            <div class="dashboard-ecommerce">
                <div class="container-fluid dashboard-content ">
                    <div class="ecommerce-widget">
                        <!--Nguyen Dai Nghiep -->
                        <?php
                        if (isset($_GET['page'])) {
                            $page = $_GET['page'];
                            if ($page == "category") {
                                include_once("category.php");
                            }
                            if ($page == "add_category") {
                                include_once("add_category.php");
                            }
                            if ($page == "update_category") {
                                include_once("update_category.php");
                            }
                            if ($page == "stone") {
                                include_once("stone.php");
                            }
                            if ($page == "add_stone") {
                                include_once("add_stone.php");
                            }
                            if ($page == "update_stone") {
                                include_once("update_stone.php");
                            }
                            if ($page == "metal") {
                                include_once("metal.php");
                            }
                            if ($page == "add_metal") {
                                include_once("add_metal.php"); 
                            }
                            if ($page == "update_metal") {
                                include_once("update_metal.php");
                            }
                            if ($page == "updatemetalprice") {
                                include_once("update_metal_price.php");
                            }
                            if ($page == "item") {
                                include_once("item.php");
                            }
                            if ($page == "detail_item") {
                                include_once("item_detail.php");
                            }
                            if ($page == "add_item") {
                                include_once("add_item.php");
                            }
                            if ($page == "confirm_delete_item") {
                                include_once("confirm_delete_item.php");
                            }if ($page == "item_delete_success") {
                                include_once("item_delete_success.php");
                            }
                            if ($page == "update_item") {
                                include_once("update_item.php");
                            }
                            if ($page == "user") {
                                include_once("account.php");
                            }
                            if ($page == "add_user") {
                                include_once("add_user.php");
                            }
                            if ($page == "update_user") {
                                include_once("update_user.php");
                            }
                            if ($page == "delete_category") {
                                include_once("confirm_delete_category.php");
                            }
                            if ($page == "delete_metal") {
                                include_once("confirm_delete_metal.php");
                            }
                            if ($page == "delete_stone") {
                                include_once("confirm_delete_stone.php");
                            }
                            if ($page == "confirm_lock_user") {
                                include_once("confirm_lock_user.php");
                            }
                            if ($page == "confirm_unlock_user") {
                                include_once("confirm_unlock_user.php");
                            }
                            if ($page == "lock_user") {
                                include_once("lock_user.php");
                            }
                            if ($page == "unlock_user") {
                                include_once("unlock_user.php");
                            }
                            if ($page == "delete_success") {
                                include_once("delete_success.php");
                            }
                            if ($page == "order") {
                                include_once("order.php");
                            }
                            if ($page == "manage_detail_order") {
                                include_once("manage_detail_order.php");
                            }
                            if ($page == "personal_order") {
                                include_once("personal_order.php");
                            }
                            if ($page == "view_p_order") {
                                include_once("view_p_order.php");
                            }
                            if ($page == "confirm_delete_p_order") {
                                include_once("confirm_delete_p_order.php");
                            }
                            if ($page == "manage_payment") {
                                include_once("manage_payment.php");
                            }
                        } else {
                            include_once("admin_index.php");
                        }
                        ?>

                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
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
            <!-- ============================================================== -->
            <!-- end footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- end wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <!-- jquery 3.3.1 -->
    <script src="style/admin/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <!-- bootstap bundle js -->
    <script src="style/admin/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- slimscroll js -->
    <script src="style/admin/assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <!-- main js -->
    <script src="style/admin/assets/libs/js/main-js.js"></script>
    <!-- chart chartist js -->
    <script src="style/admin/assets/vendor/charts/chartist-bundle/chartist.min.js"></script>
    <!-- sparkline js -->
    <script src="style/admin/assets/vendor/charts/sparkline/jquery.sparkline.js"></script>
    <!-- morris js -->
    <script src="style/admin/assets/vendor/charts/morris-bundle/raphael.min.js"></script>
    <script src="style/admin/assets/vendor/charts/morris-bundle/morris.js"></script>
    <!-- chart c3 js -->
    <script src="style/admin/assets/vendor/charts/c3charts/c3.min.js"></script>
    <script src="style/admin/assets/vendor/charts/c3charts/d3-5.4.0.min.js"></script>
    <script src="style/admin/assets/vendor/charts/c3charts/C3chartjs.js"></script>
    <script src="style/admin/assets/libs/js/dashboard-ecommerce.js"></script>
</body>

</html>