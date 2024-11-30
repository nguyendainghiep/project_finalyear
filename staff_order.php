<?php
include 'connection.php';

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$limit = 10;
$page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

// Truy vấn SQL đã sửa
$sql = "SELECT `order`.`id` as order_id, `order`.*, `user`.`FirstName`, `user`.`LastName` 
        FROM `order`
        JOIN `user` ON `order`.`userID` = `user`.`id`
        WHERE `order`.`Status` LIKE '%$search%' AND `order`.`Status` = 'pending'
        ORDER BY `OrderDate` DESC
        LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

// Tính tổng số kết quả
$total_result = $conn->query("SELECT COUNT(*) as total FROM `order`
                              WHERE `Status` LIKE '%$search%' AND `Status` = 'pending'");

$total_row = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_row / $limit);
?>

<body>
    <h2 class="text-center">Pending Order</h2>

    <form method="get" style="margin-bottom: 1rem; display: flex; justify-content: flex-end; align-items: center;">
        <div class="custom-search-group ms-3">
            <input type="hidden" name="page" value="personal_order">
            <input type="text" name="search" class="custom-form-control" placeholder="Search orders..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="custom-btn-search">Search</button>
        </div>
    </form>

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered first">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer Name - ID</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $customer_name = htmlspecialchars($row['FirstName']) . ' ' . htmlspecialchars($row['LastName']) . ' - ' . htmlspecialchars($row['userID']);
                                    $order_id = htmlspecialchars($row['order_id']);

                                    echo "<tr>
                                        <td>{$order_id}</td>
                                        <td>{$customer_name}</td>
                                        <td>{$row['Status']}</td>
                                        <td>
                                            <a href='?page=staff_detail_order&id={$order_id}' class='btn btn-sm'><i class='fa fa-eye'></i> View Details</a>
                                            <a href='?page=chat&id={$row['userID']}' class='btn btn-success'><i class='fa-solid fa-comments-dollar'></i> Contact</a>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center'>No orders found.</td></tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <nav>
        <ul class="pagination">
            <?php if ($page > 1) : ?>
                <li class="page-item">
                    <a class="page-link" href="?page=staff_order&page_num=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=staff_order&page_num=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages) : ?>
                <li class="page-item">
                    <a class="page-link" href="?page=staff_order&page_num=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Next">
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
