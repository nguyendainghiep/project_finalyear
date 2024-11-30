<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <link rel="stylesheet" href="path/to/your/css/file.css"> <!-- Update with your CSS file path -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
    <style>
        .primary-btn:hover {
            background-color: #c1edc0;
        }
        .feedback-section, .feedback-display {
            margin-top: 30px;
        }
        .feedback-item, .reply-item {
            border: 1px solid #e1e1e1;
            padding: 15px;
            margin-bottom: 15px;
        }
        .feedback-replies {
            margin-left: 20px;
            margin-top: 15px;
        }
        .reply-form {
            display: none;
            margin-top: 15px;
        }
        .reply-icon {
            cursor: pointer;
            color: #5f7496;
        }
    </style>
</head>

<body>
    <?php
    include 'connection.php';
    $item_id = $_GET['id'];
    $sql = "SELECT item.*, imgitem.name as image_name, imgitem.place, item.Name as item_name, item.Final_price as fprice, c.Name as cate_name
            FROM item
            JOIN `category` c ON c.id = item.Category
            LEFT JOIN imgitem ON imgitem.itemId = item.id
            WHERE item.id = $item_id";
    $result = $conn->query($sql);

    $image_place1 = null;
    $image_place2 = null;
    $item_name = null;
    $fprice = null;
    $des = null;
    $cate_name = null;

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['place'] == 1) {
                $image_place1 = $row['image_name'];
            } elseif ($row['place'] == 2) {
                $image_place2 = $row['image_name'];
            }
            $item_name = $row['item_name'];
            $fprice = $row['fprice'];
            $cate_name = $row['cate_name'];
            $des = $row['Description'];
        }
    }
    ?>

    <!-- Page Add Section Begin -->
    <section class="page-add">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="page-breadcrumb">
                        <h2><?php echo htmlspecialchars($cate_name); ?><span>.</span></h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Page Add Section End -->

    <!-- Product Page Section Begin -->
    <section class="product-page">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="product-slider owl-carousel">
                        <div class="product-img">
                            <figure>
                                <?php if ($image_place1): ?>
                                    <img src="style/template/img/products/<?php echo htmlspecialchars($image_place1); ?>" alt="">
                                <?php else: ?>
                                    <img src="style/template/img/products/default.jpg" alt=""> <!-- Default image -->
                                <?php endif; ?>
                                <div class="p-status">New</div>
                            </figure>
                        </div>
                        <div class="product-img">
                            <figure>
                                <?php if ($image_place2): ?>
                                    <img src="style/template/img/products/<?php echo htmlspecialchars($image_place2); ?>" alt="">
                                <?php else: ?>
                                    <img src="style/template/img/products/default.jpg" alt=""> <!-- Default image -->
                                <?php endif; ?>
                                <div class="p-status">New</div>
                            </figure>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="product-content">
                        <h2><?php echo htmlspecialchars($item_name); ?></h2>
                        <div class="pc-meta">
                            <h5>$<?php echo htmlspecialchars($fprice); ?></h5>
                            <div class="rating">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div>
                        </div>
                        <p><?php echo htmlspecialchars($des); ?></p>
                        <ul class="tags">
                            <li><span>Category :</span><?php echo htmlspecialchars($cate_name); ?></li>
                            <li><span>Tags :</span><?php echo htmlspecialchars($cate_name); ?></li>
                        </ul>
                        <!-- Add to Cart Form -->
                        <form method="post" action="?page=addtocart">
                            <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                            <input type="hidden" name="Price" value="<?php echo $fprice; ?>">
                            <div class="product-quantity">
                                <div class="pro-qty">
                                    <input type="text" name="Quantity" value="1">
                                </div>
                            </div>
                            <button type="submit" class="primary-btn pc-btn" style="cursor: pointer;">Add to cart</button>
                        </form>
                        <ul class="p-info">
                            <li>Product Information</li>
                            <li>Reviews</li>
                            <li>Product Care</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Page Section End -->

   <!-- Feedback Section Begin -->
   <section class="feedback-section">
        <div class="container">
            <h3>Leave a Feedback</h3>
            <form action="submit_feedback.php" method="post">
                <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['userid']; ?>"> <!-- Ensure user is logged in -->
                <div class="form-group">
                    <textarea name="feedback_text" class="form-control" rows="5" required placeholder="Write your feedback here..."></textarea>
                </div>
                <button type="submit" class="primary-btn pc-btn" style="cursor: pointer;">Submit Feedback</button>
            </form>
        </div>
    </section>
    <!-- Feedback Section End -->

    <!-- Feedback Display Section Begin -->
    <section class="feedback-display">
        <div class="container">
            <h3>Customer Feedback</h3>
            <?php
            $feedback_sql = "SELECT feedback.id, feedback.feedback_text, feedback.created_at, user.FirstName, user.LastName 
                             FROM feedback 
                             JOIN user ON feedback.user_id = user.id 
                             WHERE feedback.product_id = $item_id 
                             ORDER BY feedback.created_at DESC";
            $feedback_result = $conn->query($feedback_sql);

            if ($feedback_result && $feedback_result->num_rows > 0) {
                while ($feedback_row = $feedback_result->fetch_assoc()) {
                    echo "<div class='feedback-item'>";
                    echo "<p><strong>" . htmlspecialchars($feedback_row['FirstName']) . " " . htmlspecialchars($feedback_row['LastName']) . ":</strong> ". htmlspecialchars($feedback_row['feedback_text']) ." (" . htmlspecialchars($feedback_row['created_at']) . ")";
                    echo "<span class='reply-icon' data-feedback-id='" . $feedback_row['id'] . "'> <i class='fa-solid fa-reply'></i></span></p>";

                    // Display replies
                    $feedback_id = $feedback_row['id'];
                    $reply_sql = "SELECT feedback_replies.reply_text, feedback_replies.created_at, user.FirstName, user.LastName 
                                  FROM feedback_replies 
                                  JOIN user ON feedback_replies.user_id = user.id 
                                  WHERE feedback_replies.feedback_id = $feedback_id 
                                  ORDER BY feedback_replies.created_at ASC";
                    $reply_result = $conn->query($reply_sql);

                    if ($reply_result && $reply_result->num_rows > 0) {
                        echo "<div class='feedback-replies'>";
                        while ($reply_row = $reply_result->fetch_assoc()) {
                            echo "<div class='reply-item'>";
                            echo "<p><strong>" . htmlspecialchars($reply_row['FirstName']) . " " . htmlspecialchars($reply_row['LastName']) . "</strong> (" . htmlspecialchars($reply_row['created_at']) . ")</p>";
                            echo "<p>" . htmlspecialchars($reply_row['reply_text']) . "</p>";
                            echo "</div>";
                        }
                        echo "</div>";
                    }

                    // Reply form
                    echo "<div class='reply-form' id='reply-form-" . $feedback_row['id'] . "'>";
                    echo "<h4>Reply to this feedback</h4>";
                    echo "<form action='submit_reply.php' method='post'>";
                    echo "<input type='hidden' name='feedback_id' value='" . $feedback_row['id'] . "'>";
                    echo "<input type='hidden' name='user_id' value='" . $_SESSION['userid'] . "'>";
                    echo "<input type='hidden' name='item_id' value='" . $item_id . "'>";
                    echo "<textarea name='reply_text' class='form-control' rows='3' required placeholder='Write your reply here...'></textarea>";
                    echo "<button type='submit' class='primary-btn pc-btn' style='cursor: pointer;'>Reply</button>";
                    echo "</form>";
                    echo "</div>";

                    echo "</div>";
                }
            } else {
                echo "<p>No feedback available.</p>";
            }
            ?>
        </div>
    </section>
    <!-- Feedback Display Section End -->

        <!-- Related Product Section Begin -->
        <section class="related-product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="section-title">
                        <h2>Related Products</h2>
                    </div>
                </div>
            </div>
            <?php include 'related_products.php'; ?>
        </div>
    </section>
    <!-- Related Product Section End -->

    <script>
        $(document).ready(function() {
            // Toggle reply form visibility
            $('.reply-icon').on('click', function() {
                var feedbackId = $(this).data('feedback-id');
                $('#reply-form-' + feedbackId).toggle();
            });
        });
    </script>
</body>
</html>


