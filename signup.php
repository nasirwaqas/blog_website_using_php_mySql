<?php
session_start();
if (isset($_SESSION["username"])) {
    header("location: welcome.php");
    exit;
}
require_once "config.php";
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $confirm_password = trim($_POST["confirm_password"] ?? "");

    if (!$username || !$email || !$password || !$confirm_password) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Insert user
        $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);
        if (mysqli_stmt_execute($stmt)) {
            header("location: login.php");
            exit;
        } else {
            $error = "Registration failed.";
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Sign-up</title>
</head>

<body>
    <?php require_once '_nav.php'; ?>

    <form action="" method="post" class="form" id="signup-form" style="max-width: 400px; margin: 50px auto;">
        <h1 class="text-center">Sign-up</h1>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <div class="form-group">
            <label for="username">User Name</label>
            <input type="text" class="form-control" name="username" id="username" aria-describedby="nameHelp" required>
        </div>
        <div class="form-group">
            <label for="email1">Email address</label>
            <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" required>
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>
        <div class="form-group">
            <label for="confirm-password">Confirm Password</label>
            <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="Check" required>
            <label class="form-check-label" for="Check">Check me out</label>
        </div>
        <button type="submit" class="btn btn-primary d-block mx-auto">Submit</button>
    </form>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">

</body>

</html>