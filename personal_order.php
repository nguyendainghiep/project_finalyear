
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
$sql = "SELECT po.*, c.Name as category_name, s.Name as stone_name, u.username as user_name, m.Metal_name as metalname FROM personal_order po
        LEFT JOIN category c ON po.category = c.id
        LEFT JOIN stone s ON po.stone = s.id
        LEFT JOIN user u ON po.userid = u.id
        LEFT JOIN metal m ON po.metal = m.id
        WHERE po.description or u.username or c.Name  LIKE '%$search%'
        ORDER BY po.id DESC 
        LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

// Truy vấn tổng số bản ghi để tính số trang
$total_result = $conn->query("SELECT COUNT(*) as total FROM personal_order WHERE description LIKE '%$search%'");

$total_row = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_row / $limit);
?>

    <h2 class="text-center">Personal Order Management</h2>
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
                                    <th>User</th>
                                    <th>Category</th>
                                    <th>Body Size</th>
                                    <th>Metal & Weight</th>
                                    <th>Stone & Weight</th>
                                    <th>Description</th>
                                    <th>Machining</th>
                                    <th>Machining Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['user_name']}</td>
                            <td>{$row['category_name']}</td>
                            <td>{$row['body_size']} Cm</td>
                            <td>{$row['metalname']} - {$row['metal_weight']} Ct</td>
                            <td>{$row['stone_name']} - {$row['stone_weight']} Ct</td>
                            <td>{$row['description']}</td>
                            <td>{$row['machining']}</td>
                            <td>{$row['ma_description']}</td>
                            <td>
                                <a href='?page=view_p_order&id={$row['id']}' class='btn-update-custom btn-sm'><i class='fa fa-eye'></i></a>
                                <a href='?page=confirm_delete_p_order&id={$row['id']}&table=personal_order' class='btn-delete-custom btn-sm'><i class='fa fa-trash'></i></a>
                            </td>
                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='12' class='text-center'>No orders found.</td></tr>";
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
                    <a class="page-link" href="?page=personal_order&page_num=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=personal_order&page_num=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages) : ?>
                <li class="page-item">
                    <a class="page-link" href="?page=personal_order&page_num=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php
$conn->close();
?>
