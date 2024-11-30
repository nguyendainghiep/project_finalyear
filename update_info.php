<?php
include 'connection.php';

$userId = $_GET['id']; // Get user ID from query parameter

$query = "SELECT * FROM `user` WHERE `id` = $userId";
$result = mysqli_query($conn, $query);

if ($result) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "Error: " . mysqli_error($conn);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Information</title>
    <style>
        .container {
            margin-top: 50px;
            margin-bottom: 50px;
        }
        .card {
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn {
            font-weight: bold;
            padding: 1.3em 3em;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            color: #000;
            background-color: #e3e3e6;
            border: none;
            border-radius: 20px;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease 0s;
            cursor: pointer;
            outline: none;
            margin-bottom: 10px;
        }
        .btn:hover {
            background-color: #c7ccd6;
        }
        .success {
            font-weight: bold;
            color: #2d8544;
        }
        .error {
            font-weight: bold;
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Update Your Information</h2><?php
            // Initialize success flag
$update_success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the updated data from the form
    $firstName = mysqli_real_escape_string($conn, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($conn, $_POST['last_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);

    // Check if phone or email already exists
    $sql_check_phone = "SELECT * FROM `user` WHERE `Phone` = '$phone' AND `id` != $userId";
    $result_check_phone = mysqli_query($conn, $sql_check_phone);

    $sql_check_email = "SELECT * FROM `user` WHERE `Mail` = '$email' AND `id` != $userId";
    $result_check_email = mysqli_query($conn, $sql_check_email);

    $form_valid = true;

    if (mysqli_num_rows($result_check_phone) > 0) {
        echo "<p class='error'>Phone number already exists!</p>";
        $form_valid = false;
    }

    if (mysqli_num_rows($result_check_email) > 0) {
        echo "<p class='error'>Email already exists!</p>";
        $form_valid = false;
    }

    // Proceed with update if the form is valid
    if ($form_valid) {
        $updateQuery = "UPDATE `user` SET `FirstName` = '$firstName', `LastName` = '$lastName', `Address` = '$address', `Phone` = '$phone', `Mail` = '$email', `Username` = '$username' WHERE `id` = $userId";

        if (mysqli_query($conn, $updateQuery)) {
            $update_success = true;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Redirect with a success flag
if ($update_success) {
    echo "<script>
        setTimeout(function() {
            window.location.href = '?page=update_info&id=$userId&success=1';
        }, 100);
    </script>";
}
?>
            <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
                <p class="success">Information updated successfully.</p>
            <?php endif; ?>
            <form action="?page=update_info&id=<?php echo $userId; ?>" method="POST">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['FirstName']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['LastName']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['Address']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['Phone']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['Mail']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['Username']); ?>" readonly>
                </div>
                <button type="submit" class="btn">Update Information</button>
                <a href="?page=information" class="btn">Back</a>
            </form>
        </div>
    </div>
</body>
</html>
