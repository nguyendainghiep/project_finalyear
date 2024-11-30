<style>
    /* Style for the order complete page */
.container-order {
    text-align: center;
    padding: 40px;
    height:auto;
    min-height: 69.2%;
    padding-top: 10%;
    align-items: center;
}

h1 {
    font-size: 2rem;
    color: #00796b; /* Màu xanh đậm */
    margin-bottom: 20px;
}

.success {
    font-size: 1.2rem;
    color: #333;
    margin-bottom: 20px;
}

.btn {
    display: inline-block;
    background-color: #00796b;
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
}

.btn:hover {
    background-color: #004d40;
}

</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Complete</title>
    <link rel="stylesheet" href="style.css"> <!-- Thay đổi đường dẫn nếu cần -->
</head>
<body>
    <div class="container-order">
        <h1>Thank You for Your Order!</h1>
        <p class="success">Your order has been placed successfully. Our staff will contact you shortly to confirm your details and the pickup date for your products.</p>
        <a href="index.php" class="btn">Return to Home</a> <!-- Thay đổi đường dẫn nếu cần -->
    </div>
</body>
</html>
