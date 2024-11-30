<!DOCTYPE html>
<html lang="en">

<head>
    <title>SignUp-MayJWR</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="style/css/style.css">

    <style>
        /* Additional styles can be added here if needed */
        .column-container {
            display: flex;
            justify-content: space-between;
        }

        .column {
            flex: 0 0 calc(50% - 10px);
            /* 50% width with some margin in between */
            margin-right: 20px;
            /* Adjust spacing between columns */
        }

        .form-group {
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .column {
                flex: 0 0 100%;
                /* Full width on smaller screens */
                margin-right: 0;
                margin-bottom: 20px;
                /* Space between columns on smaller screens */
            }
        }
    </style>

</head>

<body>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-5">
                    <h2 class="heading-section" style="margin-bottom: 0;">MAY JEWELRY STORE</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-12">
                    <div class="wrap d-md-flex">
                        <div class="img" style="background-image: url(style/template/img/nicecollect.jpg);">
                        </div>
                        <div class="login-wrap p-4 p-md-5">
                            <div class="d-flex">
                                <div class="w-100">
                                    <h3 class="mb-4">Sign Up</h3>
                                </div>
                            </div>
                            <form method="post" class="signin-form">
                                <div class="column-container">
                                    <div class="column">
                                        <div class="form-group">
                                            <label class="label" for="username">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="label" for="password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="label" for="confirm_password">Confirm Password</label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                                        </div>
                                    </div>
                                    <div></div>
                                    <div class="column">
                                        <div class="form-group">
                                            <label class="label" for="firstname">First Name</label>
                                            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="label" for="lastname">Last Name</label>
                                            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="label" for="phone">Phone</label>
                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">

                                    <label class="checkbox-wrap checkbox-primary mb-0">Agree To The Terms Of Use?
                                        <input type="checkbox" checked>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <button name="btnRegister" id="btnRegister" value="Register" type="submit" class="form-control btn btn-primary rounded submit px-3">Sign Up</button>
                                </div>
                            </form>
                            <p class="text-center">Already have an account? <a href="login.php">Sign In</a></p>
                            <?php
require_once 'connection.php';
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if (isset($_POST['btnRegister'])) {
    // Collect form data
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Note: Password should be securely hashed in production
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];

    // Check if username exists
    $sql_check_username = "SELECT * FROM user WHERE Username = '$username'";
    $result_check_username = $conn->query($sql_check_username);

    // Check if phone exists
    $sql_check_phone = "SELECT * FROM user WHERE Phone = '$phone'";
    $result_check_phone = $conn->query($sql_check_phone);

    // Initialize flag for form validation
    $form_valid = true;

    if ($result_check_username->num_rows > 0) {
        echo "<p class='text-danger font-weight-bold text-center'>Username already exists!</p>";
        $form_valid = false;
    }

    if ($result_check_phone->num_rows > 0) {
        echo "<p class='text-danger font-weight-bold text-center'>Phone number already exists!</p>";
        $form_valid = false;
    }

    // Proceed with insertion if the form is valid
    if ($form_valid) {
        // Insert data into database
        $sql = "INSERT INTO user (Username, Password, FirstName, LastName, Phone, Role, RegisDate)
        VALUES ('$username', '$password', '$firstname', '$lastname', '$phone', 1, NOW())";

        if ($conn->query($sql) === TRUE) {
            echo "<p class='text-success font-weight-bold text-center'>New account created successfully!</p>";
            // Redirect or show success message as needed
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    // Close connection
    $conn->close();
}
?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="style/js/jquery.min.js"></script>
    <script src="style/js/popper.js"></script> 
    <script src="style/js/bootstrap.min.js"></script>
    <script src="style/js/main.js"></script>

</body>

</html>