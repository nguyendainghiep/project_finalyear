<?php
session_start();
include 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Retrieve data from POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reply_id = $_POST['reply_id'];
    $user_id = $_POST['user_id'];
    $item_id = $_POST['item_id'];
    $reply_text = $_POST['reply_text'];

    // Validate input
    if (empty($reply_id) || empty($user_id) || empty($item_id) || empty($reply_text)) {
        echo "Invalid input. Please try again.";
        exit();
    }

    // Check if the feedback belongs to the current user
    $check_sql = "SELECT user_id FROM feedback_replies WHERE id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("i", $reply_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $reply_feedback = $result->fetch_assoc();

    if ($reply_feedback['user_id'] != $user_id) {
        echo "You do not have permission to edit this feedback.";
        exit();
    }

    // Update feedback in the database
    $update_sql = "UPDATE feedback_replies SET reply_text = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $reply_text, $reply_id);

    if ($stmt->execute()) {
        // Redirect back to the product page
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        echo "Error updating feedback. Please try again.";
    }
} else {
    echo "Invalid request method.";
}
?>
