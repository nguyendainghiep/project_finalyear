<style>

    /* Additional style for the icon buttons */
    .edit a, .delete a {
        display: inline-block; /* Ensure icons are displayed inline */
        margin: 0 5px; /* Space between icons */
        font-size: 1.5rem; /* Increase icon size for better visibility */
    }

    .payment-method {
        background-color: #f8f8f8; /* Light gray background for a subtle, elegant look */
        border: 1px solid #ccc; /* Light border */
        border-radius: 10px; /* Rounded corners for a softer appearance */
        padding: 20px; /* Generous padding for spacing */
        margin: 20px 0; /* Margin to separate from other content */
    }

    .payment-method h3 {
        font-size: 24px; /* Larger font size for the header */
        color: #333; /* Dark gray color for the text */
        font-family: 'Roboto', sans-serif; /* Elegant and clean font */
        margin-bottom: 20px; /* Space below the header */
    }

    .form-group {
        display: flex;
        align-items: center; /* Center items vertically */
        margin-bottom: 15px; /* Space between items */
    }

    .form-group input[type="radio"] {
        position: absolute; /* Position off-screen */
        opacity: 0; /* Hide the radio button */
    }

    .form-group label {
        font-size: 18px; /* Slightly larger text for labels */
        color: #333; /* Dark gray color for label text */
        font-family: 'Roboto', sans-serif; /* Matching font for consistency */
        display: flex;
        align-items: center; /* Align text and image vertically */
        cursor: pointer; /* Pointer cursor to indicate clickability */
    }

    .form-group img {
        margin-left: 10px; /* Space between label text and image */
        height: 24px; /* Adjust image size */
        width: auto; /* Maintain aspect ratio */
    }

    /* Optional: Style for checked label */
    .form-group input[type="radio"]:checked + label {
        color: #00796b; /* Change color of selected option */
        font-weight: bold; /* Highlight selected option */
    }

/* Optional: Style for checked label */
.form-group input[type="radio"]:checked + label {
    color: #00796b; /* Change color of selected option */
    font-weight: bold; /* Highlight selected option */
}

.payment-method button {
    background: linear-gradient(45deg, #004d40, #00796b); /* Gradient xanh đậm sang trọng */
    border: none;
    border-radius: 0.35rem; /* Bo tròn góc mạnh hơn */
    color: #fff;
    font-size: 1rem;
    font-weight: bold;
    padding: 1rem 2rem; /* Padding lớn hơn để nút trông đầy đặn hơn */
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* Đổ bóng đậm hơn */
    transition: all 0.3s ease; /* Hiệu ứng chuyển tiếp mượt mà */
    text-transform: uppercase; /* Chữ hoa */
    display: block; /* Đảm bảo nút nằm trên một dòng riêng */
    margin: 0 auto; /* Căn giữa nút */
    width: 350px; /* Đặt chiều rộng cụ thể cho nút */
    margin-bottom: 20px;
}

.payment-method button:hover {
    background: linear-gradient(45deg, #00796b, #004d40); /* Gradient xanh đậm hơn khi hover */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); /* Đổ bóng đậm hơn khi hover */
    transform: translateY(-2px); /* Hiệu ứng nổi lên nhẹ khi hover */
}

.page-add {
        padding: 40px 0;
        background-color: #ffffff;
    }

    .page-breadcrumb h2 {
        font-size: 2.5rem;
        color: #333333;
        text-align: center;
        margin-bottom: 20px;
        position: relative;
    }

    .page-breadcrumb h2 span {
        color: #007bff;
    }

    .order-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0 auto;
}

.order-table th, .order-table td {
    padding: 15px;
    text-align: center; /* Căn giữa nội dung của các ô */
    border-bottom: 1px solid rgba(0, 0, 0, 0.1); /* Màu sắc nhẹ cho đường viền */
    background-color: transparent; /* Nền trong suốt */
}

.order-table th {
    font-size: 1.1rem;
    color: #333; /* Màu sắc văn bản mặc định */
}

.order-table tr:nth-child(even) {
    background-color: transparent; /* Nền trong suốt cho hàng chẵn */
}

.order-table tr:last-child {
    border-bottom: none; /* Không có đường viền dưới cùng */
}

.total-row {
    background-color: transparent; /* Nền trong suốt cho hàng tổng cộng */
    font-weight: bold;
    color: #333; /* Màu sắc văn bản mặc định */
    border-top: 2px solid rgba(0, 0, 0, 0.1); /* Đường viền trên mờ cho hàng tổng cộng */
}

.total-label {
    text-align: right;
    padding-right: 15px;
}

.total-price {
    color: #333; /* Màu sắc văn bản cho tổng cộng */
    text-align: center; /* Căn giữa tổng cộng */
    font-size: 1.2rem;
    padding: 15px;
}

.modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            text-align: center;
        }

        .modal-content button {
            background-color: #00796b;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 5px;
            cursor: pointer;
            border-radius: 5px;
        }

        .modal-content button:hover {
            background-color: #004d40;
        }


</style>

<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Assuming user authentication and current user ID is available
    $current_user_id = $_SESSION['userid'];

    // Query to get OrderID from Order table
    $sql = "SELECT id FROM `Order` WHERE userID = ? AND Status = 'pending'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch OrderID
        $order_ids = [];
        while ($row = $result->fetch_assoc()) {
            $order_ids[] = $row['id'];
        }

        // Convert OrderID array to string for SQL query
        $order_ids_str = implode(',', $order_ids);

        // Update status to 'complete', set OrderDate to current date, and Total to session value
        $total_amount = isset($_SESSION['total']) ? $_SESSION['total'] : 0;
        $sql_update = "UPDATE `Order` 
                       SET Status = 'complete', OrderDate = NOW(), Total = ? 
                       WHERE id IN ($order_ids_str)";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("d", $total_amount); // Use 'd' for decimal
        if ($stmt_update->execute()) {
            // Insert payment details into the payment table
            $sql_insert_payment = "INSERT INTO `payment` (orderId, userId, paymentDate, total) 
                                   VALUES (?, ?, NOW(), ?)";
            $stmt_insert_payment = $conn->prepare($sql_insert_payment);

            // Insert for each order ID
            foreach ($order_ids as $order_id) {
                $stmt_insert_payment->bind_param("iid", $order_id, $current_user_id, $total_amount);
                $stmt_insert_payment->execute();
            }

            // Redirect to order complete page after successful update
            echo '<meta http-equiv="refresh" content="0;url=?page=order_complete">';
            exit();
        } else {
            $error_message = "Error updating record: " . $conn->error;
        }
    } else {
        $error_message = "No pending orders found.";
    }
    $conn->close();
}
?>

<!-- Page Add Section Begin -->
<section class="page-add">
    <div class="container">
        <div class="row">
            <div class="page-breadcrumb">
                <h2>Invoice<span>.</span></h2><a onclick="window.history.back(); return false;" class="back-button" style="cursor: pointer; font-size:larger">
    &#8592; Back
</a>

            </div>
            <?php
            // Connect to the database
            include('connection.php');

            // Assuming user authentication and current user ID is available
            $current_user_id = $_SESSION['userid']; // Or retrieve from another method

            // Query to get OrderID from Order table
            $sql = "SELECT * FROM `Order` WHERE userID = ? AND Status = 'pending'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $current_user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Fetch OrderID
                $order_ids = [];
                while ($row = $result->fetch_assoc()) {
                    $order_ids[] = $row['id'];
                }

                // Convert OrderID array to string for SQL query
                $order_ids_str = implode(',', $order_ids);

                // Query to get order details from order_detail table
                $sql_details = "SELECT od.*, i.Name as ProductName, i.Final_price as ProductPrice, 
                               (od.Quantity * i.Final_price) as TotalPrice
                    FROM order_detail od
                    JOIN item i ON od.ItemId = i.id
                    WHERE od.OrderId IN ($order_ids_str)
                ";
                $result_details = $conn->query($sql_details);

                if ($result_details->num_rows > 0) {
                    $total_amount = 0; // Initialize total amount

                    echo "<table class='order-table'>";
                    echo "<tr class='table-header'>
                            <th class='product-name'>Product Name</th>
                            <th class='quantity'>Quantity</th>
                            <th class='unit-price'>Unit Price</th>
                            <th class='total-price'>Total Price</th>
                          </tr>";

                    while ($row = $result_details->fetch_assoc()) {
                        $item_total = $row['Quantity'] * $row['ProductPrice'];
                        $total_amount += $item_total; // Accumulate total amount

                        echo "<tr class='table-row'>";
                        echo "<td class='product-name'>" . htmlspecialchars($row['ProductName']) . "</td>";
                        echo "<td class='quantity'>" . htmlspecialchars($row['Quantity']) . "</td>";
                        echo "<td class='unit-price'>$" . htmlspecialchars($row['ProductPrice']) . "</td>";
                        echo "<td class='total-price'>$" . htmlspecialchars($item_total) . "</td>";
                        echo "</tr>";
                    }

                    // Display total row
                    echo "<tr class='total-row'>";
                    echo "<td colspan='3' class='total-label'>Total Amount:</td>";
                    echo "<td class='total-price'>$" . number_format($total_amount, 2) . "</td>";
                    echo "<td colspan='2'></td>";
                    echo "</tr>";

                    echo "</table>";
                } else {
                    echo "No order details found.";
                }
            } else {
                echo "No orders found.";
            }
            $conn->close();
            ?>
        </div>
    </div>
</section>
<!-- Page Add Section End -->



<!-- Cart Total Page Begin -->
    <!-- Cart Total Page Begin -->
    <section class="cart-total-page spad">
        <div class="container">
            <form id="checkout-form" action="" method="post">
                <div class="payment-method">
                    <h3>Payment</h3>
                    <div class="form-group">
                        <input type="radio" id="paypal" name="payment" value="paypal" required>
                        <label for="paypal">Paypal <img src="style/template/img/paypal.jpg" alt="Paypal"></label>
                    </div>
                    <div class="form-group">
                        <input type="radio" id="credit-card" name="payment" value="credit-card" required>
                        <label for="credit-card">Credit / Debit card <img src="style/template/img/mastercard.jpg" alt="Credit Card"></label>
                    </div>           
                    <div class="form-group">
                        <input type="radio" id="cash-on-delivery" name="payment" value="cash-on-delivery" required>
                        <label for="cash-on-delivery">Pay when you get the item.</label>
                    </div>  
                    <button type="button" id="place-order-btn">Place your order</button>
                </div>
                <h5>After you complete your order, staff will proactively contact you to confirm some information as well as the date when you can pick up the products you may have ordered.</h5>
            </form>
        </div>


    </section>

    <!-- Confirmation Modal -->
    <div id="confirmation-modal" class="modal">
        <div class="modal-content">
            <h2>Confirm Your Order</h2>
            <p>Are you sure you want to place your order?</p>
            <button id="confirm-btn">Yes, Place Order</button>
            <button id="cancel-btn">Cancel</button>
        </div>
    </div>

        <!-- Modal Styles and JavaScript -->
        <script>
        document.getElementById('place-order-btn').addEventListener('click', function() {
            document.getElementById('confirmation-modal').style.display = 'block';
        });

        document.getElementById('confirm-btn').addEventListener('click', function() {
            document.getElementById('checkout-form').submit();
        });

        document.getElementById('cancel-btn').addEventListener('click', function() {
            document.getElementById('confirmation-modal').style.display = 'none';
        });

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == document.getElementById('confirmation-modal')) {
                document.getElementById('confirmation-modal').style.display = 'none';
            }
        }
    </script>

