<?php
include 'connection.php'; // Kết nối đến cơ sở dữ liệu

// Xử lý cập nhật số lượng nếu có yêu cầu POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['itemId']) && isset($_POST['quantity'])) {
    $itemId = $conn->real_escape_string($_POST['itemId']);
    $quantity = (int)$_POST['quantity'];

    if ($quantity > 0) {
        $update_sql = "UPDATE item SET Quantity = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ii", $quantity, $itemId);

        if ($stmt->execute()) {
            echo "<script>alert('Quantity updated successfully.'); window.location.href='?page=inventory';</script>";
        } else {
            echo "<script>alert('Failed to update quantity.');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Quantity must be greater than zero.');</script>";
    }
}

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
    <style>
        /* Overlay để làm mờ nền */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        /* Phong cách cho nội dung của popup */
        .modal-content {
            background-color: #a7b7cf;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            text-align: center;
            width: 350px;
            max-width: 100%;
            animation: fadeIn 0.3s ease;
        }

        /* Hiệu ứng xuất hiện */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Nhóm các nút hành động */
        .modal-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .modal-actions .btn-cancel {
            background-color: #eb1e1e;
            /* Màu cam */
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .modal-actions .btn-confirm {
            background-color: #399e60;
            /* Màu xanh dương đậm */
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .modal-actions .btn-cancel:hover {
            background-color: #cc1d1d;
            /* Màu cam đậm */
        }

        .modal-actions .btn-confirm:hover {
            background-color: #2a7848;
            /* Màu xanh dương đậm hơn */
        }

        /* Phong cách cho input số lượng */
        .modal-content .form-control {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #000;
            box-sizing: border-box;
            background-color: #a7b7cf;
            color: #202224;
        }

        .modal-content h4 {
            margin-bottom: 20px;
            color: #202224;
            font-weight: 600;
        }

        /* Phong cách cho nút Update Quantity */
        .btn-update-quantity {
            background-color: #4972c4;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn-update-quantity i {
            margin-right: 5px;
        }

        .btn-update-quantity:hover {
            background-color: #5381db;
        }
    </style>
</head>

<body>
    <h2 class="text-center">Inventory Item Management</h2>

    <form method="get" style="margin-bottom: 1rem; display: flex; justify-content: flex-end; align-items: center;">
        <div class="custom-search-group ms-3">
            <input type="hidden" name="page" value="inventory">
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
                                                <button class='btn-update-quantity btn-sm' data-id='{$row['id']}' data-quantity='{$row['Quantity']}'><i class='fa fa-sync'></i> Update Quantity</button>
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
                    <a class="page-link" href="?page=inventory&page_num=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=inventory&page_num=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages) : ?>
                <li class="page-item">
                    <a class="page-link" href="?page=inventory&page_num=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Popup để cập nhật số lượng -->
    <div class="modal-overlay" id="modal-overlay">
        <div class="modal-content">
            <h4>Update Quantity</h4>
            <form id="update-quantity-form" method="POST">
                <input type="hidden" name="itemId" id="item-id" value="">
                <input type="number" name="quantity" id="item-quantity" class="form-control" min="1" placeholder="Enter new quantity" required>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn-confirm">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const updateButtons = document.querySelectorAll('.btn-update-quantity');
            const modalOverlay = document.getElementById('modal-overlay');
            const updateForm = document.getElementById('update-quantity-form');
            const itemIdInput = document.getElementById('item-id');
            const itemQuantityInput = document.getElementById('item-quantity');

            updateButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const itemId = this.getAttribute('data-id');
                    const currentQuantity = this.getAttribute('data-quantity');

                    itemIdInput.value = itemId;
                    itemQuantityInput.value = currentQuantity;

                    modalOverlay.style.display = 'flex';
                });
            });

            updateForm.addEventListener('submit', function (event) {
                event.preventDefault();
                const formData = new FormData(this);

                fetch('', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(text => {
                    if (text.includes('Quantity updated successfully')) {
                        location.reload();
                    } else {
                        alert('Update failed.');
                    }
                });
            });

            window.closeModal = function () {
                modalOverlay.style.display = 'none';
            }
        });
    </script>
</body>

</html>
