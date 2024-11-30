<!doctype html>
<html lang="en">

<head>
  <title>Login-MayJWR</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="style/css/style.css">
</head>

<body>
  <section class="ftco-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6 text-center mb-5">
          <h2 class="heading-section">MAY JEWELRY STORE</h2>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-md-12 col-lg-10">
          <div class="wrap d-md-flex">
            <div class="img" style="background-image: url(style/template/img/nicecollect.jpg);"></div>
            <div class="login-wrap p-4 p-md-5">
              <div class="d-flex">
                <div class="w-100">
                  <h3 class="mb-4">Sign In</h3>
                </div>
              </div>
              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="signin-form">
                <div class="form-group mb-3">
                  <label class="label" for="username">Username</label>
                  <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="form-group mb-3">
                  <label class="label" for="password">Password</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                  <button type="submit" class="form-control btn btn-primary rounded submit px-3">Sign In</button>
                </div>
              </form>
              <p class="text-center">Not a member? <a href="register.php">Sign Up</a></p>

              <?php
              session_start();

              require_once 'connection.php';

              if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
              }

              if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST['username'];
                $password = $_POST['password'];

                $hashed_password = md5($password);

                $sql = "SELECT * FROM user WHERE Username=? AND Password=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $username, $hashed_password);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                  $row = $result->fetch_assoc();

                  if ($row['Status'] === 'Unavailable') {
                    echo "<p class='text-danger text-center'>This account is Unavailable!</p>";
                  } else {
                    $_SESSION['username'] = $username;
                    $_SESSION['userid'] = $row['id'];
                    $_SESSION['lastname'] = $row['LastName'];
                    $_SESSION['firstname'] = $row['FirstName'];

                    if ($row['Role'] == 1) {
                      header("Location: index.php");
                      exit();
                    } elseif ($row['Role'] == 2) {
                      header("Location: admin.php");
                      exit();
                    } elseif ($row['Role'] == 3) {
                      header("Location: owner.php");
                      exit();
                    } elseif ($row['Role'] == 4) {
                      header("Location: staff.php");
                      exit();
                    }                    
                    else {
                      echo "<p class='text-danger text-center'>Role is not recognized.</p>";
                    }
                  }
                } else {
                  echo "<p class='text-danger text-center'>Incorrect username or password. Please try again.</p>";
                }

                $stmt->close();
              }

              $conn->close();
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
<script>
  document.querySelector('.signin-form').addEventListener('submit', function() {
    console.log('Form submitted');
  });
</script>