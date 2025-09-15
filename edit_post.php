<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}
require_once "config.php";
$success = "";
$errors = [];

// Enable mysqli error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Get post ID
if (!isset($_GET['id'])) {
    echo "No post selected.";
    exit;
}
$post_id = intval($_GET['id']);

// Fetch post data
$post = null;
$res = mysqli_query($conn, "SELECT * FROM post WHERE id = $post_id");
if ($res && mysqli_num_rows($res) > 0) {
    $post = mysqli_fetch_assoc($res);
} else {
    echo "Post not found.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = trim($_POST['description'] ?? '');
    $image = $_FILES['image']['name'] ?? null;
    $update_image = false;

    if (empty($description)) {
        $errors[] = "Please fill in all fields.";
    } else {
        if ($image) {
            $imagePath = 'uploads/' . basename($image);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                $update_image = true;
            } else {
                $errors[] = "Failed to upload the image.";
            }
        }
        if (!$errors) {
            if ($update_image) {
                $stmt = mysqli_prepare($conn, "UPDATE post SET discription=?, image=? WHERE id=?");
                mysqli_stmt_bind_param($stmt, "ssi", $description, $image, $post_id);
            } else {
                $stmt = mysqli_prepare($conn, "UPDATE post SET discription=? WHERE id=?");
                mysqli_stmt_bind_param($stmt, "si", $description, $post_id);
            }
            if ($stmt) {
                if (mysqli_stmt_execute($stmt)) {
                    $success = "Post updated successfully!";
                    // Refresh post data
                    $res = mysqli_query($conn, "SELECT * FROM post WHERE id = $post_id");
                    $post = mysqli_fetch_assoc($res);
                } else {
                    $errors[] = "Error updating post: " . mysqli_stmt_error($stmt);
                }
                mysqli_stmt_close($stmt);
            } else {
                $errors[] = "Database error: Unable to prepare statement. " . mysqli_error($conn);
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Post</title>
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
                <div class="card-header">Edit Post</div>
                <div class="card-body">
                    <?php if ($errors): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $e): ?>
                                    <li><?=htmlspecialchars($e)?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?=htmlspecialchars($success)?></div>
                    <?php endif; ?>
                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="4"><?=htmlspecialchars($_POST['description'] ?? $post['discription'] ?? '')?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Image (JPG, PNG, GIF)</label>
                            <input id="image" type="file" name="image" class="form-control-file">
                            <?php if (!empty($post['image'])): ?>
                                <div class="mt-2">
                                    <img src="uploads/<?=htmlspecialchars($post['image'])?>" alt="Current image" style="max-width:150px;">
                                </div>
                            <?php endif; ?>
                        </div>
                        <button class="btn btn-primary" type="submit">Update Post</button>
                        <a href="welcome.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
