<?php
include 'connection.php'; // Kết nối cơ sở dữ liệu

$searchTerm = isset($_POST['search']) ? mysqli_real_escape_string($conn, $_POST['search']) : '';

// Lấy danh sách người dùng có role là 1 và khớp với tìm kiếm
$query = "SELECT id, FirstName, LastName, Phone FROM user WHERE role = 1 
          AND (FirstName LIKE '%$searchTerm%' OR LastName LIKE '%$searchTerm%' OR Phone LIKE '%$searchTerm%')";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Contact</title>
    <style>
        .contact-list {
            max-height: 600px;
            overflow-y: auto;
        }
        .contact-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .contact-item:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }
        .contact-item img {
            border-radius: 50%;
            margin-right: 10px;
        }
        .contact-item .name {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Customer Contact</h1>
    

    <form method="post" style="margin-bottom: 1rem; display: flex; justify-content: flex-end; align-items: center;">
        <div class="custom-search-group ms-3">
            <input type="hidden" name="page" value="personal_order">
            <input type="text" name="search" class="custom-form-control" placeholder="Search orders..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit" class="custom-btn-search">Search</button>
        </div>
    </form>
    <h3>List Customer:</h3>
    <div class="contact-list">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="contact-item" onclick="startChat(<?php echo $row['id']; ?>)">
                <img src="https://via.placeholder.com/50" alt="Profile Picture">
                <div>
                    <div class="name"><?php echo htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']); ?></div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <script> 
        function startChat(userID) {
            // Mở cửa sổ chat hoặc điều hướng đến trang chat
            window.location.href = `?page=chat&id=${userID}`;
        }
    </script>
</body>
</html>
