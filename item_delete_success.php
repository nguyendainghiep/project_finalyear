

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
            <h4><?php echo 'That item deleted sucessfully!' ?></h4>
            <p>You will be redirected to the management page in <span id="countdown" class="countdown">5</span> seconds.</p>
            <a href="#" class="btn btn-primary" id="backButton">Go back now</a>
        </div>
    </div>

    <script>
        
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
                window.location.href = `?page=item`;
            }
        }, 1000);
        
        // Đưa người dùng quay lại trang quản lý của bảng và làm mới trang đó nếu nhấn nút
        backButton.addEventListener('click', (event) => {
            event.preventDefault();
            // Quay về trang quản lý của bảng và làm mới trang đó
            window.location.href = `?page=item`;
        });
    </script>

</body>
</html>
