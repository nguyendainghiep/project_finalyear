<?php
include 'connection.php';

$userId = $_SESSION['userid']; // Lấy ID người dùng từ session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
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
        .form-group {
            margin-bottom: 15px;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .success {
            font-weight: bold;
            color: #2d8544;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Change Password For: <?php echo $_SESSION['username'];?></h2>
            <?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = md5($_POST['current_password']);
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Get the current password from the database
    $query = "SELECT Password FROM `user` WHERE `id` = $userId";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $user = mysqli_fetch_assoc($result);

        if ($user['Password'] === $currentPassword) {
            if ($newPassword === $confirmPassword) {
                // Update the password
                $newPasswordHashed = md5($newPassword);
                $updateQuery = "UPDATE `user` SET `Password` = '$newPasswordHashed' WHERE `id` = $userId";
                if (mysqli_query($conn, $updateQuery)) {
                    echo "<p class='success'>Password updated successfully!</p>";
                } else {
                    echo "<p style='color: red;>Error updating password: </p>" . mysqli_error($conn);
                }
            } else {
                echo "<p style='color: red;'>New passwords do not match.</p>";
            }
        } else {
            echo "<p style='color: red;'>Current password is incorrect.</p>";
        }
    } else {
        echo "<p style='color: red;'>Error retrieving user data: </p>" . mysqli_error($conn);
    }
}
?>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn">Change Password</button>
                <a href="?page=information" class="btn">Back</a>
            </form>
        </div>
    </div>
</body>
</html>
