<?php
session_start();
include 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra xem các biến có tồn tại không
    if (isset($_POST['feedback_id']) && isset($_POST['user_id']) && isset($_POST['reply_text']) && isset($_POST['item_id'])) {
        $feedback_id = $_POST['feedback_id'];
        $user_id = $_POST['user_id'];
        $reply_text = $_POST['reply_text'];
        $item_id = $_POST['item_id'];

        // Chuẩn bị câu lệnh SQL để chèn phản hồi trả lời vào cơ sở dữ liệu
        $sql = "INSERT INTO feedback_replies (feedback_id, user_id, reply_text) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $feedback_id, $user_id, $reply_text);

        if ($stmt->execute()) {
            // Nếu thực hiện thành công, chuyển hướng về trang sản phẩm với ID phản hồi
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: Missing required data.";
    }

    $conn->close();
}
?>
