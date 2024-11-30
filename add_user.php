<?php
include 'connection.php'; // Kết nối đến cơ sở dữ liệu

// Xử lý form khi người dùng gửi dữ liệu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $address = $conn->real_escape_string($_POST['address']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $mail = $conn->real_escape_string($_POST['mail']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $regis_date = date('Y-m-d'); // Ngày đăng ký là ngày hiện tại
    $role = (int)$_POST['role'];

    // Mã hóa mật khẩu bằng MD5
    $hashed_password = md5($password);

    // Thực thi câu lệnh SQL để thêm dữ liệu vào bảng user
    $sql = "INSERT INTO user (FirstName, LastName, Address, Phone, Mail, Username, Password, RegisDate, Status, Role) 
            VALUES ('$first_name', '$last_name', '$address', '$phone', '$mail', '$username', '$hashed_password', '$regis_date', 'Available', $role)";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success' role='alert'>New user added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $conn->error . "</div>";
    }
}

// Lấy dữ liệu từ bảng role để hiển thị trong dropdown
$roleQuery = "SELECT id, Name FROM role";
$roleResult = $conn->query($roleQuery);

?>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Add New User</h2>
        <form method="post" class="mt-4">
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="mb-3">
                <label for="mail" class="form-label">Email</label>
                <input type="email" class="form-control" id="mail" name="mail" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3 custom-dropdown">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <?php
                    // Hiển thị các lựa chọn từ bảng role
                    if ($roleResult->num_rows > 0) {
                        while ($row = $roleResult->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['Name'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No roles available</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Add User</button>
                <a href="?page=user" class="btn btn-secondary">Back to List</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
