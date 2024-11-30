<?php
include 'connection.php'; // Kết nối đến cơ sở dữ liệu

session_start(); // Bắt đầu session nếu chưa bắt đầu

$customer_id = mysqli_real_escape_string($conn, $_GET['receiver_id']); // Lấy receiver_id từ URL
$staff_id = $_SESSION['userid']; // Lấy staff_id từ session

// Lấy tất cả tin nhắn từ bảng chats
$sql = "SELECT * FROM chats 
        WHERE receiver_id = '$customer_id' OR sender_id = '$customer_id'
        ORDER BY created_at ASC"; // Sắp xếp tin nhắn theo thời gian tạo
$result = mysqli_query($conn, $sql);

$output = '';
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Xác định class dựa trên sender_id
        $class = ($row['sender_id'] == $customer_id) ? 'sender' : 'receiver';
        
        // Định dạng ngày tháng và giờ
        $datetime = new DateTime($row['created_at']);
        $formatted_date = $datetime->format('d M Y \a\t H:i'); // Ví dụ: 21 Aug 2024 at 14:30
        
        // Tạo thẻ <p> với class tương ứng
        $output .= '<div class="message ' . $class . '">'
                    . '<span class="message-date">' . htmlspecialchars($formatted_date) . '</span>'
                    . '<p style="position: relative; word-wrap: break-word; font-size: 1.1rem; color: #000;">' . htmlspecialchars($row['messages']) . '</p>'
                    . '</div>';
    }
} else {
    $output .= 'No messages.';
}
echo $output;

mysqli_close($conn);
?>
