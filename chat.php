<style>
:root {
    /* Biến cho màu sắc */
    --customer-bg-color: #c1d3f7; /* Màu nền cho tin nhắn của khách hàng */
    --staff-bg-color: #c7f0d2; /* Màu nền cho tin nhắn của nhân viên */

    /* Biến cho mũi tên */
    --arrow-size: 7px;

    /* Biến cho padding và bo tròn góc */
    --message-padding: 10px 15px;
    --message-border-radius: 20px;

    /* Biến cho chiều rộng tin nhắn */
    --message-max-width: 70%;
}

/* CSS cho khung chat */
.chat_form {
    width: 100%;
    max-width: 100%;
     /* Bạn có thể điều chỉnh kích thước tối đa theo ý muốn */
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.chat-box {
    height: 500px;
    max-height: 80%;
    overflow-y: auto;
    padding: 20px;
    background-color: transparent;
    border-radius: 10px;
    margin-bottom: 20px;
}

/* Căn chỉnh tin nhắn */
/* Định dạng ngày tháng và giờ */
.message-date {
    display: block;
    font-size: 0.8rem; /* Kích thước chữ nhỏ hơn cho ngày tháng */
    color: #888; /* Màu chữ nhẹ cho ngày tháng */
    margin-bottom: 2px; /* Khoảng cách giữa ngày tháng và nội dung tin nhắn */
}

/* Định dạng tin nhắn */
.message {
    display: inline-block;
    margin-bottom: 5px;
    padding: var(--message-padding);
    border-radius: var(--message-border-radius);
    max-width: var(--message-max-width);
    clear: both;
    position: relative;
    word-wrap: break-word; /* Để tin nhắn dài không bị tràn ra ngoài */
    font-size: 1.1rem; /* Kích thước chữ lớn hơn cho nội dung tin nhắn */
    color: #000; /* Màu chữ đen cho nội dung tin nhắn */
    font-family: "Montserrat", sans-serif;
}

/* Tin nhắn của nhân viên */
.message.sender {
    background-color: var(--staff-bg-color);
    float: right; /* Đẩy tin nhắn sang phải */
    text-align: right; /* Canh phải nội dung */
}

/* Tin nhắn của khách hàng */ 
.message.receiver {
    background-color: var(--customer-bg-color);
    float: left; /* Đẩy tin nhắn sang trái */
}

/* Tạo mũi tên chỉ vào tin nhắn */
.message.sender::after,
.message.receiver::after {
    content: "";
    position: absolute;
    width: 0;
    height: 0;
    border-style: solid;
}

/* Mũi tên cho tin nhắn của nhân viên */
.message.sender::after {
    border-width: var(--arrow-size) 0 var(--arrow-size) var(--arrow-size);
    border-color: transparent transparent transparent var(--staff-bg-color);
    top: 50%;
    right: calc(-1 * var(--arrow-size));
    transform: translateY(-50%);
}

/* Mũi tên cho tin nhắn của khách hàng */
.message.receiver::after {
    border-width: var(--arrow-size) var(--arrow-size) var(--arrow-size) 0;
    border-color: transparent var(--customer-bg-color) transparent transparent;
    top: 50%;
    left: calc(-1 * var(--arrow-size));
    transform: translateY(-50%);
}

/* Khung nhập tin nhắn */
.message-form {
    position: sticky;
    bottom: 0;
    width: 100%;
    background-color: #fff; /* Màu nền cho khung nhập tin nhắn */
    border-top: 1px solid #ddd;
    padding: 10px;
    display: flex;
    align-items: center; /* Căn giữa nội dung theo chiều dọc */
}

.message-input {
    flex-grow: 1;
    border-radius: var(--message-border-radius);
    border: 1px solid #ccc;
    padding: var(--message-padding);
    margin-right: 10px;
}

.send-button {
    border-radius: var(--message-border-radius);
    padding: 10px 20px;
    background-color: #6587f7;
    color: #000;
    border: none;
    cursor: pointer;
}

.send-button:hover {
    background-color: #87a2fa;
}

/* Header của chat */
.chat-header {
    background-color: var(--header-bg-color);
    color: var(--header-text-color);
    padding: 10px;
    margin-bottom: 0px;
    border-radius: 10px;
    font-size: 1.5rem;
    font-weight: bold;
    text-align: center;
}


</style>
<?php

$customer_id = mysqli_real_escape_string($conn, $_GET['id']); // Lấy receiver_id từ URL

// Truy vấn tên khách hàng từ bảng user
$sql = "SELECT FirstName, LastName FROM user WHERE id = '$customer_id'";
$result = mysqli_query($conn, $sql);

$customer_name = 'Customer'; // Giá trị mặc định nếu không tìm thấy
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $customer_name = htmlspecialchars($row['FirstName']) . ' ' . htmlspecialchars($row['LastName']);
}

mysqli_close($conn);
?>

<body>
<h2 class="chat-header"><?php echo $customer_name; ?></h2>
<button class="btn btn-back" style="border-radius: 10px; background-color:transparent; margin-bottom:2px;" onclick="history.back()"><i class="fa-solid fa-angles-left"> Back</i></button>
<div class="chat_form">
    <div id="chat-box" class="chat-box"></div>
    <form id="message-form" class="message-form d-flex align-items-center mt-3 p-2 border-top">
        <input type="hidden" name="receiver_id" value="<?php echo $_GET['id']; ?>">
        <input type="hidden" name="sender_id" value="<?php echo $_SESSION['userid']; ?>">
        <input type="text" name="message" id="message" class="message-input form-control me-2" placeholder="Your message" required>
        <button type="submit" class="send-button btn">SEND<i class="fa-solid fa-play"></i></button>
    </form>
</div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
                var isAtBottom = true; 
            function loadMessages() {
                $.ajax({
                    url: "load_messages.php",
                    method: "GET",
                    data: {
                        receiver_id: <?php echo $_GET['id']; ?>
                    },
                    success: function(data) {
                        $('#chat-box').html(data);
                        if (isAtBottom) {
                    scrollToBottom();
                }
                    }
                });
            }

            function scrollToBottom() {
        var chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function checkScrollPosition() {
        var chatBox = document.getElementById('chat-box');
        isAtBottom = chatBox.scrollHeight - chatBox.scrollTop === chatBox.clientHeight;
    }

            $(document).ready(function() {
                loadMessages();
                setInterval(loadMessages, 500);

                $('#message-form').on('submit', function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: "send_message.php",
                        method: "POST",
                        data: $(this).serialize(),
                        success: function(response) {
                            $('#message').val('');
                            loadMessages();
                            scrollToBottom();
                        },
                        error: function() {
                    console.error("Error sending message");
                }
                    });
                });
            });


            $('#chat-box').on('scroll', checkScrollPosition);
        scrollToBottom();
        </script>
</body>
</html>