<?php
include 'connection.php'; // Kết nối đến cơ sở dữ liệu

session_start(); // Bắt đầu session nếu chưa bắt đầu

$staff_id = mysqli_real_escape_string($conn, $_SESSION['userid']); // Lấy ID của nhân viên từ session
$cus_id = mysqli_real_escape_string($conn, $_POST['receiver_id']); // Lấy ID của khách hàng từ POST
$message = mysqli_real_escape_string($conn, $_POST['message']); // Lấy tin nhắn từ POST

// Chèn tin nhắn mới vào bảng chats
$sql_insert_message = "INSERT INTO chats (sender_id, messages, created_at) VALUES ('$staff_id', '$message', NOW())";
if (mysqli_query($conn, $sql_insert_message)) {
    echo "New message created successfully";
} else {
    echo "Error: " . $sql_insert_message . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>

