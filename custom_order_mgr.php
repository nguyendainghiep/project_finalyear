<style>
  .modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background: #fff;
    padding: 1rem;
    border-radius: 5px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.modal-content h4 {
    margin-top: 0;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 1rem;
}

.btn-cancel {
    background: #f0f0f0;
    border: none;
    padding: 0.5rem 1rem;
    margin-right: 0.5rem;
    cursor: pointer;
}

.btn-confirm {
    background: #007bff;
    color: #fff;
    border: none;
    padding: 0.5rem 1rem;
    cursor: pointer;
}

</style>
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
        WHERE po.description LIKE '%$search%'
        ORDER BY po.id DESC 
        LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

// Truy vấn tổng số bản ghi để tính số trang
$total_result = $conn->query("SELECT COUNT(*) as total FROM personal_order WHERE description LIKE '%$search%'");

$total_row = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_row / $limit);

// Xử lý cập nhật trạng thái
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orderId']) && isset($_POST['status'])) {
    $orderId = $conn->real_escape_string($_POST['orderId']);
    $status = $conn->real_escape_string($_POST['status']);

    $update_sql = "UPDATE personal_order SET status = '$status' WHERE id = $orderId";
    if ($conn->query($update_sql)) {
        echo 'Status updated successfully.';
    } else {
        echo 'Failed to update status.';
    }
    exit; // Ensure no further output is sent
}
?>

<h2 class="text-center">Requested Custom Order</h2>
<form method="get" style="margin-bottom: 1rem; display: flex; justify-content: flex-end; align-items: center;">
    <div class="custom-search-group ms-3">
        <input type="hidden" name="page" value="personal_order">
        <input type="text" name="search" class="custom-form-control" placeholder="Search orders..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="custom-btn-search">Search</button>
    </div>
</form>
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card" >
            <div class="card-body" >
                <div class="table-responsive" >
                    <table class="table table-striped table-bordered first">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Category</th>
                                <th>Body Size</th>
                                <th>Metal</th>
                                <th>Stone</th>
                                <th>Machining</th>
                                <th>Status</th>
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
                                <td>{$row['metalname']} Ct</td>
                                <td>{$row['stone_name']}</td>
                                <td>{$row['machining']}</td>
                                <td>{$row['status']}</td>
                                <td>
                                    <button class='btn-update-status btn btn-primary btn-sm' data-id='{$row['id']}' data-status='{$row['status']}'><i class='fa fa-sync'></i> Update Status</button>
                                    <a href='?page=chat&id={$row['userid']}' class='btn btn-sm btn-success'><i class='fa-regular fa-comment'></i></a>
                                </td>
                            </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='9' class='text-center'>No orders found.</td></tr>";
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const updateStatusButtons = document.querySelectorAll('.btn-update-status');
    const statusModalOverlay = document.getElementById('update-status-overlay');
    const updateStatusForm = document.getElementById('update-status-form');
    const orderIdInput = document.getElementById('order-id');
    const orderStatusSelect = document.getElementById('order-status');

    // Xử lý mở popup cập nhật trạng thái
    updateStatusButtons.forEach(button => {
        button.addEventListener('click', function () {
            const orderId = this.getAttribute('data-id');
            const currentStatus = this.getAttribute('data-status');

            orderIdInput.value = orderId;
            orderStatusSelect.value = currentStatus;

            statusModalOverlay.style.display = 'flex';
        });
    });

    // Xử lý gửi dữ liệu cập nhật trạng thái
    updateStatusForm.addEventListener('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(this);

        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(text => {
            if (text.includes('Status updated successfully')) {
                location.reload();
            } else {
                alert('Update failed.');
            }
        });
    });

    // Đóng popup cập nhật trạng thái
    window.closeStatusModal = function () {
        statusModalOverlay.style.display = 'none';
    }
});
</script>
<!-- Popup để cập nhật trạng thái -->
<div class="modal-overlay" id="update-status-overlay">
    <div class="modal-content">
        <h4>Update Status</h4>
        <form id="update-status-form" method="POST">
            <input type="hidden" name="orderId" id="order-id" value="">
            <select name="status" id="order-status" class="form-control" required>
                <option value="Inprocess">Inprocess</option>
                <option value="Received">Received</option>
                <option value="Crafting">Crafting</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeStatusModal()">Cancel</button>
                <button type="submit" class="btn-confirm">Update</button>
            </div>
        </form>
    </div>
</div>