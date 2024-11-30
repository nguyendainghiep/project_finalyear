<?php
include 'connection.php';

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$limit = 10;

$page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

$sql = "SELECT user.*, role.Name as RoleName FROM user 
        LEFT JOIN role ON user.Role = role.id 
        WHERE 
        role.name LIKE '%$search%' OR 
        Address LIKE '%$search%' OR 
        Phone LIKE '%$search%' OR 
        Mail LIKE '%$search%' OR 
        user.id LIKE '%$search%' OR 
            FirstName LIKE '%$search%' OR 
            LastName LIKE '%$search%' OR 
            Username LIKE '%$search%' 
        ORDER BY RegisDate DESC 
        LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

$total_result = $conn->query("SELECT COUNT(*) as total FROM user WHERE 
            FirstName LIKE '%$search%' OR 
            LastName LIKE '%$search%' OR 
            Username LIKE '%$search%'");

if (!$total_result) {
    die("Total query failed: " . $conn->error);
}

$total_row = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_row / $limit);
?>
<body>
    <h2 class="text-center">User Management</h2>
    <form method="get" action="admin.php" class="mb-3 d-flex justify-content-between align-items-center" style="margin-bottom: 1rem;">
        <a href="?page=add_user" class="btn btn-success">Add</a>
        <div class="custom-search-group ms-3">
            <input type="hidden" name="page" value="user">
            <input type="text" name="search" class="form-control" placeholder="Search users..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="custom-btn-search">Search</button>
        </div>
    </form>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Address</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>Registration Date</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['FirstName']}</td>
                            <td>{$row['LastName']}</td>
                            <td>{$row['Address']}</td>
                            <td>{$row['Phone']}</td>
                            <td>{$row['Mail']}</td>
                            <td>{$row['Username']}</td>
                            <td>{$row['RegisDate']}</td>
                            <td>{$row['RoleName']}</td>
                            <td>
                                <!-- Liên kết cập nhật người dùng -->
                                <a href=\"?page=update_user&id=" . htmlspecialchars($row['id']) . "\" class=\"btn-update-custom btn-sm\">
                                    <i class=\"fa fa-edit\"></i>
                                </a>

                                <!-- Kiểm tra trạng thái và tạo liên kết phù hợp -->
                                " . ($row['Status'] === 'Unavailable' ?
                                    "<a href=\"?page=confirm_unlock_user&id=" . htmlspecialchars($row['id']) . "&table=user&action=unlock\" 
                                       class=\"btn-delete-custom btn-sm\" 
                                       title=\"Unlock this account\">
                                        <i class=\"fa fa-user-lock\"></i>
                                    </a>" :
                                    "<a href=\"?page=confirm_lock_user&id=" . htmlspecialchars($row['id']) . "&table=user&action=lock\" 
                                       class=\"btn-delete-custom btn-sm\" 
                                       title=\"Lock this account\">
                                        <i class=\"fa fa-unlock\" style=\"color: green;\"></i>
                                    </a>") . "
                            </td>
                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='10' class='text-center'>No users found.</td></tr>";
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
                    <a class="page-link" href="?page=user&page_num=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=user&page_num=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages) : ?>
                <li class="page-item">
                    <a class="page-link" href="?page=user&page_num=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Next">
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
