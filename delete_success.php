<?php
include 'connection.php'; // Kết nối đến cơ sở dữ liệu

// Lấy ID và bảng từ form POST
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$table = isset($_POST['table']) ? $conn->real_escape_string($_POST['table']) : '';

if ($id <= 0 || empty($table)) {
    echo "<div class='alert alert-danger' role='alert'>Invalid ID or table.</div>";
    exit();
}

// Thực hiện câu lệnh SQL để xóa bản ghi với ID tương ứng
$sql = "DELETE FROM $table WHERE Id = $id";

if ($conn->query($sql) === TRUE) {
    $message = "Object deleted successfully.";
} else {
    $message = "Error: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        .countdown {
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-info text-center">
            <h4><?php echo $message; ?></h4>
            <p>You will be redirected to the management page in <span id="countdown" class="countdown">5</span> seconds.</p>
            <a href="#" class="btn btn-primary" id="backButton">Go back now</a>
        </div>
    </div>

    <script>
        // Lấy tên bảng từ PHP
        const table = <?php echo json_encode($table); ?>;
        
        // Đếm ngược thời gian
        let countdown = 5;
        const countdownElement = document.getElementById('countdown');
        const backButton = document.getElementById('backButton');
        
        const interval = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(interval);
                // Quay về trang quản lý của bảng và làm mới trang đó
                window.location.href = `?page=${table}`;
            }
        }, 1000);
        
        // Đưa người dùng quay lại trang quản lý của bảng và làm mới trang đó nếu nhấn nút
        backButton.addEventListener('click', (event) => {
            event.preventDefault();
            // Quay về trang quản lý của bảng và làm mới trang đó
            window.location.href = `?page=${table}`;
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
