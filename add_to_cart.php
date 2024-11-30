<?php

include 'connection.php';

// Function to display notification
function displayNotification($message) {
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
        }

        .container_noti {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 70vh;
        }

        .notification {
            background-color: #ffffff;
            border: 2px solid #28a745; /* Green border */
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        .notification p {
            margin: 10px 0;
            font-size: 16px;
            color: #333;
        }

        .notification a {
            color: #007bff;
            text-decoration: none;
        }

        .notification a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container_noti">
        <div class="notification">
            <p>' . $message . '</p>
            <p><a href="javascript:history.back()">Return to the previous page</a></p>
        </div>
    </div>
</body>
</html>';
}

// Check if the session is active and if the user ID is set
if (!isset($_SESSION['userid'])) {
    displayNotification('Login to add this item to your cart. <a href="login.php">Log in</a>');
    return;
}

$user_id = $_SESSION['userid'];

// Check if the necessary POST values are set
if (!isset($_POST['item_id']) || !isset($_POST['Price']) || !isset($_POST['Quantity'])) {
    displayNotification('Item ID, Price, and Quantity are required.');
    return;
}

$item_id = $_POST['item_id'];
$price = $_POST['Price'];
$quantity = $_POST['Quantity'];

// Begin a transaction
$conn->begin_transaction();

try {
    // Check if there is a pending order for this user
    $sql = "SELECT id, status FROM `order` WHERE userId = ? ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $order_id = $row['id'];
        $status = $row['status'];

        // If the order is completed, create a new order
        if ($status == 'complete') {
            $sql = "INSERT INTO `order` (userId, status) VALUES (?, 'pending')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $order_id = $stmt->insert_id;
        }
    } else {
        // No pending orders, create a new one
        $sql = "INSERT INTO `order` (userId, status) VALUES (?, 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $order_id = $stmt->insert_id;
    }

    // Check if the item already exists in the order details
    $sql = "SELECT Quantity FROM `order_detail` WHERE OrderId = ? AND ItemId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $order_id, $item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Item exists, update quantity
        $row = $result->fetch_assoc();
        $current_quantity = $row['Quantity'];
        $new_quantity = $current_quantity + $quantity;

        $sql = "UPDATE `order_detail` SET Quantity = ?, Price = ? WHERE OrderId = ? AND ItemId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("idii", $new_quantity, $price, $order_id, $item_id);
        $stmt->execute();
    } else {
        // Item does not exist, insert new entry
        $sql = "INSERT INTO `order_detail` (OrderId, ItemId, Quantity, Price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiid", $order_id, $item_id, $quantity, $price);
        $stmt->execute();
    }

    // Commit the transaction
    $conn->commit();
    displayNotification('The product has been successfully added to the cart! <a href="?page=cart">View your shopping cart</a> or <a href="javascript:history.back()"></a>.');
    
} catch (Exception $e) {
    // Rollback the transaction if there is an error
    $conn->rollback();
    displayNotification('Failed to add the product to the cart: ' . $e->getMessage());
}

$stmt->close();
$conn->close();
?>
