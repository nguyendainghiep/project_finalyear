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

// Truy vấn dữ liệu với phân trang và JOIN để lấy tên danh mục
$sql = "SELECT item.*, category.Name as CategoryName 
        FROM item 
        JOIN category ON item.Category = category.id
        WHERE item.Name LIKE '%$search%' 
        ORDER BY item.id DESC 
        LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

// Truy vấn tổng số bản ghi để tính số trang
$total_result = $conn->query("SELECT COUNT(*) as total 
                              FROM item 
                              JOIN category ON item.Category = category.id
                              WHERE item.Name LIKE '%$search%'");

$total_row = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_row / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Management</title>
</head>
<body>
    <h2 class="text-center">Item Management</h2>

    <form method="get" class="mb-3 d-flex justify-content-between align-items-center" style="margin-bottom: 1rem;">
        <a href="?page=add_item" class=" btn btn-success">Add</a>
        <div class="custom-search-group ms-3">
            <input type="hidden" name="page" value="item">
            <input type="text" name="search" class="custom-form-control" placeholder="Search items..." value="<?php echo htmlspecialchars($search); ?>">
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
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Final Price</th>
                                    <th>Details</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                            <td>{$row['id']}</td>
                                            <td>{$row['Name']}</td>
                                            <td>" . htmlspecialchars($row['CategoryName']) . "</td>
                                            <td>{$row['Quantity']}</td>
                                            <td>$ {$row['Final_price']}</td>
                                            <td>{$row['Description']}</td>
                                            <td>
                                                <a href='?page=detail_item&id={$row['id']}' class='btn-view-details btn-sm'><i class='fa fa-eye'></i></a>
                                                <a href='?page=update_item&id={$row['id']}' class='btn-update-custom btn-sm'><i class='fa fa-edit'></i></a>
                                                <a href='?page=confirm_delete_item&id={$row['id']}&table=item' class='btn-delete-custom btn-sm'><i class='fa fa-trash'></i></a>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center'>No items found.</td></tr>";
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
                    <a class="page-link" href="?page=item&page_num=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=item&page_num=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages) : ?>
                <li class="page-item">
                    <a class="page-link" href="?page=item&page_num=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Next">
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
