<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <title>Your Cart</title>
    <style>
        .cart-heading {
            text-align: center;
            color: #444;
            font-size: 2em;
            margin-top: 20px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .cart-table {
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .cart-table th {
            font-weight: bold;
        }

        .cart-table th,
        .cart-table td {
            padding: 15px;
            text-align: center;
            vertical-align: middle;
        }

        .cart-table th {
            background-color: #f4f4f4;
            color: #333;
        }

        .cart-table td {
            border-bottom: 1px solid #ddd;
        }

        .cart-table tr:last-child td {
            border-bottom: none;
        }

        .empty-cart-message {
            text-align: center;
            color: #888;
            font-size: 1.2em;
            margin: 20px 0;
            font-weight: bold;
        }

        .checkout-button-container {
            text-align: right;
            margin: 20px;
        }

        .delete-button {
            display: inline-block;
            padding: 5px 10px;
            margin: 2px;
            text-decoration: none;
            font-weight: bold;
            color: #fff;
            border-radius: 5px;
            transition: color 0.3s;
        }

        .edit a,
        .delete a {
            font-size: 20px;
            color: #343a40;
            text-decoration: none;
            transition: color 0.3s;
        }

        .edit a:hover,
        .delete a:hover {
            color: #0056b3;
        }

        .delete a {
            color: #343a40;
        }

        .delete a:hover {
            color: #c82333;
        }

        .edit a,
        .delete a {
            display: inline-block;
            margin: 0 5px;
            font-size: 1.5rem;
        }

        .cart-table img {
            width: 100px;
            height: 100px;
        }

        .table th,
        .table td {
            padding: 0.5rem;
            /* Giảm khoảng cách giữa các ô */
        }

        @media (max-width: 767.98px) {

            /* Ví dụ cho các màn hình nhỏ hơn 768px */
            .table img {
                width: 70px;
                height: 70px;
                /* Ẩn hình ảnh */
            }

            .table td,
            .table th {
                font-size: 0.6rem;
                /* Giảm kích thước chữ */
            }
        }


        /* Nút chung */
        .btn {
            font-weight: bold;
            padding: 1.3em 3em;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            color: #000;
            background-color: #e3e3e6;
            border: none;
            border-radius: 20px;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease 0s;
            cursor: pointer;
            outline: none;
            margin-bottom: 10px;
        }

        .btn:active {
            transform: translateY(-1px);
        }

        /* Nút "View Your History Order" */
        .btn-historyod {
            /* Nền trong suốt */
            color: #333;
            /* Màu chữ tối */
        }

        .btn-historyod:hover {
            color: #000;
            background-color: #c7ccd6;
            /* Màu chữ đậm hơn khi di chuột qua */
        }
        /* Nút "View Your Customize Order" */
        .btn-customizeod {
 
            /* Nền trong suốt */
            color: #333;
            /* Màu chữ tối */
        }

        .btn-customizeod:hover {
            color: #000;
            background-color: #c7ccd6;
            /* Màu chữ đậm hơn khi di chuột qua */
        }

        /* Nút "Proceed to Checkout" */
        .btn-check-out {
            background-color: #adedbe;
            /* Xám sáng nhẹ */
            color: #2d8544;
            border: 1px solid #2d8544;
            /* Đường viền xám sáng */
        }

        .btn-check-out:hover {
            background-color: #2d8544;
            box-shadow: 0px 15px 20px rgba(46, 229, 157, 0.4);
            color: #fff;
            transform: translateY(-7px);
        }

        /* Modal container */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        /* Modal content */
        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
        }

        .modal-content p {
            font-size: 1.2em;
            margin-bottom: 20px;
        }

        /* Buttons inside the modal */
        .modal-content .btn-confirm,
        .modal-content .btn-cancel {
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 10px;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-confirm {
            background-color: #a3d4a9;
            color: #000;

        }

        .btn-confirm:hover {
            background-color: #245f33;
            color: #fff;
        }

        .btn-cancel {
            background-color: #deadad;
            color: #000;
        }

        .btn-cancel:hover {
            background-color: #d60404;
            color: #fff;
        }
        .quantity-container {
    display: flex;
    align-items: center;
    justify-content: center;
}

.quantity-btn {
    border: none;
    background-color: transparent;
    padding: 7px 10px;
    cursor: pointer;
    font-size: 30px;
    color: #000;
}
.quantity-btn:hover {
    color: #75746f;
}


.quantity-container input {
    width: 60px;
    height: 37px;
    text-align: center;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    margin: 0;
}

    </style>
</head>

<body>
    <?php
    include 'connection.php';

    if (!isset($_SESSION['userid'])) {
        die('Please login to view your cart.');
    }

    $user_id = $_SESSION['userid'];

    $sql = "SELECT o.id AS order_id, od.ItemId, od.Quantity, od.Price, i.Name AS item_name, img.name AS item_image, od.id AS oddetail_id
            FROM `order` o
            JOIN `order_detail` od ON o.id = od.OrderId
            JOIN `item` i ON od.ItemId = i.id
            LEFT JOIN `imgitem` img ON i.id = img.itemId AND img.place = 1
            WHERE o.userId = ? AND o.Status = 'pending'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <div class="table-responsive-sm" style="max-width: 80%; margin: 0 auto; height:auto;min-height: 67%; ">
        <h1 class="cart-heading" style="margin-bottom:0;">Your Cart</h1>
        <div class="button-container">
            <a href="?page=history_order"><button class="btn btn-historyod">History Order</button></a>
            <a href="?page=personal_order_cart"><button class="btn btn-customizeod">Requested Custom Order</button></a>
        </div>
        <?php if ($result->num_rows > 0): ?>
        <table class="table table-sm table-striped cart-table">
            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Price</th>
                <th></th>
            </tr>
            <?php
            $total_price = 0;
            while ($row = $result->fetch_assoc()):
                $total_price += $row['Price'] * $row['Quantity'];
            ?>
 <tr>
    <td><img src="style/template/img/products/<?php echo htmlspecialchars($row['item_image']); ?>" alt="Product Image"></td>
    <td><?php echo htmlspecialchars($row['item_name']); ?></td>
    <td>
        <div class="quantity-container">
            <button class="quantity-btn decrease" onclick="changeQuantity(<?php echo $row['oddetail_id']; ?>, -1)">
                <i class="fa-solid fa-caret-left"></i>
            </button>
            <input id="quantity_<?php echo $row['oddetail_id']; ?>" value="<?php echo htmlspecialchars($row['Quantity']); ?>" min="1" onchange="updateQuantity(<?php echo $row['oddetail_id']; ?>)">
            <button class="quantity-btn increase" onclick="changeQuantity(<?php echo $row['oddetail_id']; ?>, 1)">
                <i class="fa-solid fa-caret-right"></i>
            </button>
        </div>
    </td>
    <td>$<?php echo htmlspecialchars($row['Price']); ?></td>
    <td class="total-price" data-price="<?php echo htmlspecialchars($row['Price']); ?>">
        $<?php echo htmlspecialchars($row['Price'] * $row['Quantity']); ?>
    </td>
    <td class='delete'>
        <a href="#" class="delete-button" onclick="confirmDelete(<?php echo $row['oddetail_id']; ?>)"><i class="fas fa-trash-alt"></i></a>
    </td>
</tr>

            <?php endwhile; $_SESSION['total'] = $total_price;?>
            <tr class="total-row">
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td style="font-size: 20px; font-weight:bold;">TOTAL:</td>
    <td style="font-size: 20px; font-weight:bold;">$<?php echo htmlspecialchars($total_price); ?></td>
</tr>

        </table>

        <div class="checkout-button-container">
            <a href="?page=payment"><button class="btn btn-check-out">Proceed to Checkout</button></a>
        </div>
        <?php else: ?>
        <p class="empty-cart-message">Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <!-- Custom Alert Modal -->
    <div id="customModal" class="modal">
        <div class="modal-content">
            <p>Are you sure you want to delete this item?</p>
            <button class="btn-confirm" id="confirmBtn">Confirm</button>
            <button class="btn-cancel" id="cancelBtn">Cancel</button>
        </div>
    </div>

    <?php
    $stmt->close();
    $conn->close();
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        function confirmDelete(oddetail_id) {
            var modal = document.getElementById("customModal");
            var confirmBtn = document.getElementById("confirmBtn");
            var cancelBtn = document.getElementById("cancelBtn");

            modal.style.display = "flex";

            confirmBtn.onclick = function () {
                window.location.href = "?page=delete_cart_item&id=" + oddetail_id;
            };

            cancelBtn.onclick = function () {
                modal.style.display = "none";
            };
        }

        function confirmDelete(oddetail_id) {
    var modal = document.getElementById("customModal");
    var confirmBtn = document.getElementById("confirmBtn");
    var cancelBtn = document.getElementById("cancelBtn");

    modal.style.display = "flex";

    confirmBtn.onclick = function () {
        window.location.href = "?page=delete_cart_item&id=" + oddetail_id;
    };

    cancelBtn.onclick = function () {
        modal.style.display = "none";
    };
}

function changeQuantity(oddetail_id, increment) {
    var quantityInput = document.getElementById('quantity_' + oddetail_id);
    var currentQuantity = parseInt(quantityInput.value);
    var newQuantity = currentQuantity + increment;
    var totalCell = quantityInput.closest('tr').querySelector('.total-price');
    var pricePerUnit = parseFloat(totalCell.getAttribute('data-price'));

    if (newQuantity < 1) {
        return;
    }

    quantityInput.value = newQuantity;

    // Update item total price
    totalCell.textContent = `$${(pricePerUnit * newQuantity).toFixed(0)}`;

    // Update the cart total price
    updateCartTotal();

    // Send request to update the database
    updateDatabaseQuantity(oddetail_id, newQuantity);
}

function updateQuantity(oddetail_id) {
    var quantityInput = document.getElementById('quantity_' + oddetail_id);
    var newQuantity = parseInt(quantityInput.value);
    var totalCell = quantityInput.closest('tr').querySelector('.total-price');
    var pricePerUnit = parseFloat(totalCell.getAttribute('data-price'));

    if (isNaN(newQuantity) || newQuantity < 1) {
        quantityInput.value = 1;
        alert('Quantity must be a number greater than 0.');
        return;
    }

    // Update item total price
    totalCell.textContent = `$${(pricePerUnit * newQuantity).toFixed(0)}`;

    // Update the cart total price
    updateCartTotal();

    // Send request to update the database
    updateDatabaseQuantity(oddetail_id, newQuantity);
}

function updateDatabaseQuantity(oddetail_id, newQuantity) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_quantity.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
            } else {
                console.error('Error updating quantity');
            }
        }
    };

    xhr.send('oddetail_id=' + encodeURIComponent(oddetail_id) + '&quantity=' + encodeURIComponent(newQuantity));
}

function updateCartTotal() {
    var totalPriceCells = document.querySelectorAll('.total-price');
    var grandTotal = 0;

    totalPriceCells.forEach(function(cell) {
        var cellText = cell.textContent;
        var price = parseFloat(cellText.replace('$', ''));
        grandTotal += price;
    });

    var totalRow = document.querySelector('tr.total-row td:last-child');
    totalRow.textContent = `$${grandTotal.toFixed(0)}`;
}





    </script>
</body>

</html>

