<?php
include 'connection.php';

$userId = $_SESSION['userid']; // Lấy ID người dùng từ session

$query = "SELECT * FROM `user` WHERE `id` = $userId";
$result = mysqli_query($conn, $query);

if ($result) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "Lỗi: " . mysqli_error($conn);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>

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
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="row">
                <div class="col-md-4">
                    <h3>Hello, <?php echo $user['FirstName'] . ' ' . $user['LastName']; ?></h3>
                    <p>This your information page.</p>
                    <p><strong>Address:</strong> <?php echo $user['Address']; ?></p>
                    <p><strong>Phone:</strong> <?php echo $user['Phone']; ?></p>
                    <p><strong>Email:</strong> <?php echo $user['Mail']; ?></p>
                    <p><strong>Username:</strong> <?php echo $user['Username']; ?></p>
                    <p><strong>Registration Date:</strong> <?php echo $user['RegisDate']; ?></p>
                    <a href="?page=update_info&id=<?php echo $user['id']; ?>" class="btn btn-update">Update Information</a>
                    <a href="?page=change_password&id=<?php echo $user['id']; ?>" class="btn btn-update">Change Password</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
