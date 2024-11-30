<?php
include 'connection.php';

// Kiểm tra xem có gửi dữ liệu không
if (isset($_POST['oddetail_id']) && isset($_POST['quantity'])) {
    $oddetail_id = intval($_POST['oddetail_id']);
    $quantity = intval($_POST['quantity']);

    // Kiểm tra số lượng hợp lệ
    if ($quantity < 1) {
        echo 'Invalid quantity.';
        exit;
    }

    // Cập nhật số lượng trong cơ sở dữ liệu
    $sql = "UPDATE order_detail SET Quantity = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $quantity, $oddetail_id);
    
    if ($stmt->execute()) {
        echo 'Quantity updated successfully.';
    } else {
        echo 'Error updating quantity.';
    }

    $stmt->close();
}

$conn->close();
?>

