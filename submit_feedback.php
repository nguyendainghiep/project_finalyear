<?php
session_start();
include 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $item_id = $_POST['item_id'];
    $feedback_text = $_POST['feedback_text'];

    $sql = "INSERT INTO feedback (user_id, product_id, feedback_text) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $user_id, $item_id, $feedback_text);

    if ($stmt->execute()) {
        // Redirect to the specific URL after successful submission
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>


