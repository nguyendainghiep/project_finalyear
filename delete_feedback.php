<?php
include 'connection.php';

if (isset($_GET['feedback_id'])) {
    $feedback_id = intval($_GET['feedback_id']);

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Check if there are replies associated with the feedback
        $checkRepliesSql = "SELECT COUNT(*) FROM feedback_replies WHERE feedback_id = ?";
        $stmt = $conn->prepare($checkRepliesSql);
        $stmt->bind_param('i', $feedback_id);
        $stmt->execute();
        $stmt->bind_result($replyCount);
        $stmt->fetch();
        $stmt->close();

        // If there are replies, delete them
        if ($replyCount > 0) {
            $deleteRepliesSql = "DELETE FROM feedback_replies WHERE feedback_id = ?";
            $stmt = $conn->prepare($deleteRepliesSql);
            $stmt->bind_param('i', $feedback_id);
            $stmt->execute();
            $stmt->close();
        }

        // Delete the feedback
        $deleteFeedbackSql = "DELETE FROM feedback WHERE id = ?";
        $stmt = $conn->prepare($deleteFeedbackSql);
        $stmt->bind_param('i', $feedback_id);
        $stmt->execute();
        $stmt->close();

        // Commit the transaction
        $conn->commit();

        // Redirect to feedback management page or a specific page
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } catch (Exception $e) {
        // Rollback the transaction if an error occurs
        $conn->rollback();
        echo "Error deleting feedback: " . $e->getMessage();
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
?>

