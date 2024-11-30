<?php
include 'connection.php'; // Kết nối đến cơ sở dữ liệu

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Xác định số lượng bản ghi trên mỗi trang
$limit = 10;

// Lấy số trang hiện tại từ URL hoặc mặc định là trang đầu tiên
$page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
if ($page < 1) $page = 1;

// Tính toán giá trị OFFSET
$offset = ($page - 1) * $limit;

// Truy vấn dữ liệu với phân trang
$sql = "SELECT payment.*, CONCAT(user.FirstName, ' ', user.LastName) AS UserName
        FROM payment
        LEFT JOIN user ON payment.userId = user.id
        WHERE payment.orderId OR user.FirstName OR user.LastName LIKE '%$search%' 
        ORDER BY payment.paymentDate DESC 
        LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

// Truy vấn tổng số bản ghi để tính số trang
$total_result = $conn->query("SELECT COUNT(*) as total FROM `payment` WHERE 
            `orderId` LIKE '%$search%'");

$total_row = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_row / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Management</title>
    <style>
        .custom-form-control {
            width: 300px;
            margin-right: 10px;
        }
        .custom-btn-search {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
        }
        .custom-btn-search:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2 class="text-center">Payment Management</h2>

    <form method="get" style="margin-bottom: 1rem; display: flex; justify-content: flex-end; align-items: center;">
        <div class="custom-search-group ms-3">
            <input type="hidden" name="page" value="payment_management">
            <input type="text" name="search" class="custom-form-control" placeholder="Search by Order ID..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="custom-btn-search">Search</button>
        </div>
    </form>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Order ID</th>
                                    <th>User Name</th>
                                    <th>Payment Date</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['orderId']}</td>
                                            <td>{$row['UserName']}</td>
                            <td>{$row['paymentDate']}</td>
                            <td>$ {$row['total']}</td>
                            <td>
                                <a href='?page=manage_detail_order&id={$row['orderId']}' class='btn-view-details btn-sm'><i class='fa fa-eye'></i> View Details</a>
                            </td>
                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>No payments found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Phân trang -->
    <nav>
        <ul class="pagination">
            <?php if ($page > 1) : ?>
                <li class="page-item">
                    <a class="page-link" href="?page=payment_management&page_num=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=payment_management&page_num=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages) : ?>
                <li class="page-item">
                    <a class="page-link" href="?page=payment_management&page_num=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
