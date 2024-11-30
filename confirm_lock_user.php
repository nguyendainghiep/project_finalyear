<?php
include 'connection.php'; // Kết nối đến cơ sở dữ liệu

// Lấy ID từ URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo "<div class='alert alert-danger' role='alert'>Invalid ID.</div>";
    exit();
}

// Truy vấn thông tin chi tiết từ bảng user
$sql = "SELECT * FROM user WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    echo "<div class='alert alert-danger' role='alert'>No record found.</div>";
    exit();
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Lock User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .confirm-delete {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 1rem rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="confirm-delete mt-5">
            <h3 class="text-center mb-4">Confirm Lock Account</h3>
            <p>Are you sure you want to lock the user?</p>
            <table class="table table-bordered">
                <tr><th>ID</th><td><?php echo htmlspecialchars($row['id']); ?></td></tr>
                <tr><th>First Name</th><td><?php echo htmlspecialchars($row['FirstName']); ?></td></tr>
                <tr><th>Last Name</th><td><?php echo htmlspecialchars($row['LastName']); ?></td></tr>
                <tr><th>Address</th><td><?php echo htmlspecialchars($row['Address']); ?></td></tr>
                <tr><th>Phone</th><td><?php echo htmlspecialchars($row['Phone']); ?></td></tr>
                <tr><th>Email</th><td><?php echo htmlspecialchars($row['Mail']); ?></td></tr>
                <tr><th>Username</th><td><?php echo htmlspecialchars($row['Username']); ?></td></tr>
                <tr><th>Registration Date</th><td><?php echo htmlspecialchars($row['RegisDate']); ?></td></tr>
            </table>
            <form method="post" action="?page=lock_user">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-danger">Lock this account</button>
                    <a href="#" class="btn btn-secondary" onclick="window.history.back(); return false;">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
