<?php
include 'connection.php'; // Connect to the database

// Check if the user ID is provided via URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid user ID.");
}

$user_id = (int)$_GET['id'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $address = $conn->real_escape_string($_POST['address']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $mail = $conn->real_escape_string($_POST['mail']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $role = (int)$_POST['role'];

    // Fetch current password from the database
    $sql = "SELECT Password FROM user WHERE id=$user_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $current_password = $result->fetch_assoc()['Password'];
    } else {
        die("User does not exist.");
    }

    // Build SQL statement to update user information
    $sql = "UPDATE user SET 
            FirstName='$first_name', 
            LastName='$last_name', 
            Address='$address', 
            Phone='$phone', 
            Mail='$mail', 
            Username='$username', 
            Role=$role";

    // Update password only if it's not empty and different from the current password
    if (!empty($password) && md5($password) !== $current_password) {
        $hashed_password = md5($password);
        $sql .= ", Password='$hashed_password'";
    } else {
        // If no new password is entered or it's the same as the current one, keep the old password
        $sql .= ", Password=Password";
    }

    $sql .= " WHERE id=$user_id";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success' role='alert'>User updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $conn->error . "</div>";
    }
}

// Fetch user information from the database
$sql = "SELECT * FROM user WHERE id=$user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    die("User does not exist.");
}

// Fetch roles from the role table
$role_sql = "SELECT id, Name FROM role";
$role_result = $conn->query($role_sql);

if (!$role_result) {
    die("Query failed: " . $conn->error);
}
?>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Update User</h2>
        <form method="post" class="mt-4">
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['FirstName']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['LastName']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($user['Address']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['Phone']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="mail" class="form-label">Email</label>
                <input type="email" class="form-control" id="mail" name="mail" value="<?php echo htmlspecialchars($user['Mail']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['Username']); ?>" readonly required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password (optional)">
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <div class="input-group">
                    <select class="form-control" id="role" name="role" required>
                        <?php
                        while ($role_row = $role_result->fetch_assoc()) {
                            $selected = ($role_row['id'] == $user['Role']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($role_row['id']) . "' $selected>" . htmlspecialchars($role_row['Name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="?page=user" class="btn btn-secondary">Go Back</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
