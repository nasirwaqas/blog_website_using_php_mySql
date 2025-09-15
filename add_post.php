<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}
require_once "config.php";

$success = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = trim($_POST['description'] ?? '');
    $image = $_FILES['image']['name'] ?? null;

    if (empty($description) || empty($image)) {
        $errors[] = "Please fill in all fields and upload a valid image.";
    } else {
        $imagePath = 'uploads/' . basename($image);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            $sql = "INSERT INTO post (discription, image) VALUES ('$description', '$image')";
            if (mysqli_query($conn, $sql)) {
                $success = "Product added successfully!";
            } else {
                $errors[] = "Error adding product: " . mysqli_error($conn);
            }
        } else {
            $errors[] = "Failed to upload the image.";
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="welcom.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #f59608ff;">
        <a class="navbar-brand" href="welcome.php">e-store</a>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add Product</div>
                    <div class="card-body">
                        <?php if ($errors): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach ($errors as $e): ?>
                                        <li><?= htmlspecialchars($e) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                        <?php endif; ?>

                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" class="form-control"
                                    rows="4"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="image">Image (JPG, PNG, GIF)</label>
                                <input id="image" type="file" name="image" class="form-control-file">
                            </div>
                            <button class="btn btn-primary" type="submit">Save Product</button>
                            <a href="welcome.php" class="btn btn-secondary ml-2">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>