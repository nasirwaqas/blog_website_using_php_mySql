<?php
session_start();
if (isset($_SESSION["username"])) {
    header("location: welcome.php");
    exit;
}
require_once 'config.php';
$err = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");
    if (!$email || !$password) {
        $err = "Please enter your email and password.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, username, email, password FROM users WHERE email = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $id, $username, $email_db, $password);
            if (mysqli_stmt_fetch($stmt)) {
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $username;
                $_SESSION["email"] = $email_db;
                $_SESSION["loggedin"] = true;
                header("location: welcome.php");
                exit;
            } else {
                $err = "Invalid email or password.";
            }
            mysqli_stmt_close($stmt);
        }
    }
};
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Login </title>
</head>

<body>
    <?php require_once '_nav.php'; ?>
    <?php if (!empty($err)): ?>
    <div class="alert alert-danger" role="alert" id="error-alert">
      <?php echo $err; ?>
    </div>
    <script>
      setTimeout(function() {
        var alert = document.getElementById('error-alert');
        if(alert) alert.style.display = 'none';
      }, 3000);
    </script>
    <?php endif; ?>

<form action="" method="post" class="form" style="max-width: 400px; margin: 50px auto;">
    <h1 class="text-center">Log-in</h1>
  <div class="form-group">
    <label for="email">Email address</label>
    <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp">
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" name="password" id="password">
  </div>
  <div class="form-group form-check">
    <input type="checkbox" class="form-check-input" id="Check">
    <label class="form-check-label" for="Check">Check me out</label>
  </div>
  <button type="submit" name="submit"lass="btn btn-primary d-block mx-auto">Submit</button>
</form>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
</body>

</html>