<?php
include 'connection.php';

if (!isset($_SESSION['userid'])) {
    die('Please login to access your cart.');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .message {
            margin-top: 200px;
            justify-content: center;
            height: 40%;
            align-items: center;
            text-align: center;
        }
        .message-content{
            font-weight: bold;
            color: #179639;
        }
        .back-now{
            font-size: 18px;
            margin-left: 10px;
            font-weight: bold;
            text-decoration: underline;
            color: #0740a8;

        }

        .seconds{
            color: red;
            margin-left: 2px;
            margin-right: 2px;
        }
    </style>
</head>
<body>

<?php
if (isset($_GET['id'])) {
    $item_id = $_GET['id'];
    
    // Xóa sản phẩm khỏi giỏ hàng của người dùng dựa trên ID
    $sql = "DELETE FROM `order_detail` WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item_id);

    if ($stmt->execute()) {
        // Xóa thành công
        echo "<div class='message'>";
        echo "<p id='countdown' class='message-content'>Item deleted successfully. Redirecting to the cart page in <span id='seconds' class='seconds'>5</span> seconds or </p>";
        echo "<a href='javascript:history.back()' class='back-now'> Back now.</a>";
        echo "</div>";
        echo '<meta http-equiv="refresh" content="5;url=http://localhost:8080/web_FYP/?page=cart">';
    } else {
        // Xóa thất bại
        echo "<p id='countdown' class='message'>Error deleting item. Redirecting to the cart page in <span id='seconds'> 5</span> seconds.</p>";
        echo '<meta http-equiv="refresh" content="5;url=http://localhost:8080/web_FYP/?page=cart">';
    }

    $stmt->close();
    $conn->close();
}
?>

</body>
</html>
<script>
// Đếm ngược
let seconds = 5;
const countdownElement = document.getElementById('seconds');

const countdownInterval = setInterval(() => {
    seconds--;
    countdownElement.textContent = seconds;

    if (seconds <= 0) {
        clearInterval(countdownInterval);
    }
}, 1000);
</script>
