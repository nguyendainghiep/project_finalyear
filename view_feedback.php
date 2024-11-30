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
<style>
    .feedback-section,
    .feedback-display {
        margin-top: 30px;
    }

    .feedback-item,
    .reply-item {
        border: 1px solid #000;
        padding: 10px;
        padding-top: 10px;
        margin: 5px;
        margin-top: 10px;
        background-color: #e1ebe1;
        border-radius: 20px;
    }

    .feedback-replies {
        margin-left: 40px;
        margin-top: 10px;
    }

    .reply-form {
        display: none;
        margin-top: 15px;
    }

    .edit-form {
        display: none;
        margin-top: 15px;
    }

    .reply-icon,
    .edit-icon,
    .delete-icon {
        cursor: pointer;
        color: #4d4c4c;
        font-weight: bold;
        font-size: 14px;
    }

    .time {
        margin: 0;
        padding-left: 10px;
        font-size: 12px;
    }

    /* Feedback Section */
    .feedback-section {
        margin: 20px 0;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    .feedback-section h3 {
        margin-bottom: 15px;
        font-size: 1.5rem;
        color: #333;
    }

    .feedback-section .form-group {
        margin-bottom: 15px;
    }

    .feedback-section textarea.form-control {
        width: 100%;
        border-radius: 5px;
        border: 1px solid #ccc;
        padding: 10px;
        resize: vertical;
    }

    .feedback-section button.primary-btn.pc-btn {
        background-color: #007bff;
        /* Adjust as needed */
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
        font-size: 1rem;
    }

    .feedback-section button.primary-btn.pc-btn:hover {
        background-color: #0056b3;
        /* Adjust as needed */
    }

    /* Reply and Edit Forms */
    .reply-form,
    .edit-form {
        margin-top: 20px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
        display: none;
        /* Hidden by default */
    }

    .reply-form h4,
    .edit-form h4 {
        margin-bottom: 15px;
        font-size: 1.25rem;
        color: #333;
    }

    .reply-form textarea.form-control,
    .edit-form textarea.form-control {
        width: 100%;
        border-radius: 5px;
        border: 1px solid #ccc;
        padding: 10px;
        resize: vertical;
    }

    .reply-form button.primary-btn.pc-btn,
    .edit-form button.primary-btn.pc-btn {
        background-color: #28a745;
        /* Green for replies */
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
        font-size: 1rem;
    }

    .reply-form button.primary-btn.pc-btn:hover,
    .edit-form button.primary-btn.pc-btn:hover {
        background-color: #218838;
        /* Darker green for hover */
    }
</style>
<div class="card mb-3">
    <div class="card-body">
        <h4 class="card-title"><?php echo $item_name; ?></h4>
        <p class="card-text"><strong>Category:</strong> <?php echo $cate_name; ?></p>
        <p class="card-text"><strong>Description:</strong> <?php echo $des; ?></p>
        <?php if (isset($image_place1) && !empty($image_place1)) : ?>
            <img src="style/template/img/products/<?php echo htmlspecialchars($image_place1); ?>" alt="Product Image" style="width:200px;">
        <?php endif; ?>

    </div>

</div>

<a href="?page=feedback_management" class="btn btn-primary">Back to Feedback Management</a>
<section class="feedback-display" >
    <div class="container" >
        <?php
        $feedback_sql = "SELECT feedback.id, feedback.feedback_text, feedback.created_at, feedback.user_id, user.FirstName, user.LastName
                         FROM feedback 
                         JOIN user ON feedback.user_id = user.id 
                         WHERE feedback.product_id = $item_id 
                         ORDER BY feedback.created_at DESC";
        $feedback_result = $conn->query($feedback_sql);

        if ($feedback_result && $feedback_result->num_rows > 0) {
            while ($feedback_row = $feedback_result->fetch_assoc()) {
                $is_current_user = ($feedback_row['user_id'] == $_SESSION['userid']);
                echo "<div style='margin-top: 20px;' class='feedback-item'>";
                echo "<p class='time'>" . htmlspecialchars($feedback_row['created_at']) . "</p>";
                echo "<p style='margin:0; margin-left:10px; color:#000;'><strong>" . htmlspecialchars($feedback_row['FirstName']) . " " . htmlspecialchars($feedback_row['LastName']) . ":</strong> " . htmlspecialchars($feedback_row['feedback_text']) . "</p>";
                echo "<span style='margin-left:10px;' class='reply-icon' data-feedback-id='" . $feedback_row['id'] . "'> Reply</span>";
                if ($is_current_user) {
                    echo "<span style='margin-left:10px;' class=' edit-icon' data-feedback-id='" . $feedback_row['id'] . "'> Edit</span>";
                    echo "<a href='delete_feedback.php?feedback_id=" . htmlspecialchars($feedback_row['id']) . "' style='text-decoration:none;'>
                                <span style='margin-left:10px;' class='delete-icon'>Delete</span>
                              </a>";
                }
                echo "</div>";

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

                // Edit form
                echo "<div class='edit-form' id='edit-form-" . $feedback_row['id'] . "'>";
                echo "<h4>Edit this feedback</h4>";
                echo "<form action='submit_edit.php' method='post'>";
                echo "<input type='hidden' name='feedback_id' value='" . $feedback_row['id'] . "'>";
                echo "<input type='hidden' name='user_id' value='" . $_SESSION['userid'] . "'>";
                echo "<input type='hidden' name='item_id' value='" . $item_id . "'>";
                echo "<textarea name='feedback_text' class='form-control' rows='3' required placeholder='Edit your feedback here...'>" . htmlspecialchars($feedback_row['feedback_text']) . "</textarea>";
                echo "<button type='submit' class='primary-btn pc-btn' style='cursor: pointer;'>Save</button>";
                echo "</form>";
                echo "</div>";
                // Display replies
                $feedback_id = $feedback_row['id'];
                $reply_sql = "SELECT feedback_replies.reply_text, feedback_replies.created_at, feedback_replies.id, feedback_replies.user_id, user.FirstName, user.LastName 
                                        FROM feedback_replies 
                                        JOIN user ON feedback_replies.user_id = user.id 
                                        WHERE feedback_replies.feedback_id = $feedback_id 
                                        ORDER BY feedback_replies.created_at ASC";
                $reply_result = $conn->query($reply_sql);

                if ($reply_result && $reply_result->num_rows > 0) {
                    echo "<div class='feedback-replies'>";
                    while ($reply_row = $reply_result->fetch_assoc()) {
                        // Determine if the reply was made by the current user
                        $current_user = ($reply_row['user_id'] == $_SESSION['userid']);

                        echo "<div class='reply-item' style='margin-top: 20px;'>";
                        echo "<p class='time'>" . htmlspecialchars($reply_row['created_at']) . "</p>";
                        echo "<p style='margin: 10px; margin-top: 0; color: #000;'><strong>" . htmlspecialchars($reply_row['FirstName']) . " " . htmlspecialchars($reply_row['LastName']) . ": </strong>" . htmlspecialchars($reply_row['reply_text']) . "</p>";

                        // Show edit and delete icons only if the reply was made by the current user
                        if ($current_user) {
                            echo "<span style='margin-left:10px;' class='edit-icon' data-feedback-id='" . htmlspecialchars($reply_row['id']) . "'> Edit</span>";
                            echo "<a href='delete_reply.php?reply_id=" . htmlspecialchars($reply_row['id']) . "' style='text-decoration:none;'>
                                <span style='margin-left:10px;' class='delete-icon'>Delete</span>
                              </a>";
                        }
                        echo "</div>";
                        // Edit form
                        echo "<div class='edit-form' id='edit-form-" . $reply_row['id'] . "'>";
                        echo "<h4>Edit this feedback</h4>";
                        echo "<form action='edit_reply.php' method='post'>";
                        echo "<input type='hidden' name='reply_id' value='" . $reply_row['id'] . "'>";
                        echo "<input type='hidden' name='user_id' value='" . $_SESSION['userid'] . "'>";
                        echo "<input type='hidden' name='item_id' value='" . $item_id . "'>";
                        echo "<textarea name='reply_text' class='form-control' rows='3' required placeholder='Edit your feedback here...'>" . htmlspecialchars($reply_row['reply_text']) . "</textarea>";
                        echo "<button type='submit' class='primary-btn pc-btn' style='cursor: pointer;'>Save</button>";
                        echo "</form>";
                        echo "</div>";
                    }
                    echo "</div>";
                }
            }
        } else {
            echo "<p>No feedback available.</p>";
        }
        ?>
    </div>
</section>
<form action="delete_feedback.php" method="post" class="delete-form" id="delete-form-<?php echo $feedback_row['id']; ?>">
    <input type="hidden" name="feedback_id" value="<?php echo $feedback_row['id']; ?>">
    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
</form>
<form action="delete_reply.php" method="post" class="delete-form" id="delete-reply-form-<?php echo $reply_row['id']; ?>">
    <input type="hidden" name="reply_id" value="<?php echo $reply_row['id']; ?>">
    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Toggle reply form visibility
        $('.reply-icon').on('click', function() {
            var feedbackId = $(this).data('feedback-id');
            var $replyForm = $('#reply-form-' + feedbackId);

            // Hide other reply forms
            $('.reply-form').not($replyForm).hide();
            // Hide edit forms
            $('.edit-form').hide();

            // Toggle the specific reply form
            $replyForm.toggle();
        });

        // Toggle edit form visibility
        $('.edit-icon').on('click', function() {
            var feedbackId = $(this).data('feedback-id');
            var $editForm = $('#edit-form-' + feedbackId);

            // Hide other edit forms
            $('.edit-form').not($editForm).hide();
            // Hide reply forms
            $('.reply-form').hide();

            // Toggle the specific edit form
            $editForm.toggle();
        });
    });
</script>