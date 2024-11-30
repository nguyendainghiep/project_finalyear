<?php
include 'connection.php';

// Check if reply_id is provided
if (isset($_GET['reply_id'])) {
    $reply_id = intval($_GET['reply_id']);

    // Prepare and execute delete statement
    $sql = "DELETE FROM feedback_replies WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reply_id);

    if ($stmt->execute()) {
        // Redirect back to the feedback page
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        echo "Error deleting reply.";
    }

    $stmt->close();
}

$conn->close();
?>
