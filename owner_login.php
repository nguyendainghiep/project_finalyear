<?php
session_start();
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    // Truy vấn để kiểm tra Username, Password và Role là 'owner'
    $sql = "SELECT * FROM user WHERE Username = ? AND Password = ? AND role = '3'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Đăng nhập thành công
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: owner.php");
        exit();
    } else {
        $error = "Incorrect Username or Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa, #d7ccc8);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
        }

        .card h2 {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
            color: #333;
            text-align: center;
        }

        .form-label {
            font-weight: 500;
            color: #555;
        }

        .form-group {
            margin-bottom: 15px; /* Adds space between input fields */
        }

        .form-group input {
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
            padding: 12px;
            font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
            width: 93%; /* Ensures full-width input fields */
        }
        .btn-primary {
            background-color: #baba6c;
            border-radius: 8px;
            padding: 12px;
            font-size: 18px;
            font-weight: bold;
            width: 100%; /* Makes the button full-width */
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #c2c272;
        }

        .text-danger {
            color: #dc3545;
            font-size: 14px;
            text-align: center; /* Center align error message */
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Owner Login</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <?php if (isset($error)) { echo "<p class='text-danger'>$error</p>"; } ?>
        </form>
    </div>
</body>
</html>
