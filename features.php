<?php

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
require_once 'config.php';

$post = [];
$search_query = ''; // Initialize search query

$limit = 6; // Number of posts per page
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, $current_page); // Ensure current page is at least 1
$offset = ($current_page - 1) * $limit; // Calculate the offset

// Fetch only featured posts
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM post WHERE discription LIKE '%$search_query%' ORDER BY id LIMIT $limit OFFSET $offset";
} else {
    $sql = "SELECT * FROM post  ORDER BY id LIMIT $limit OFFSET $offset";
}

$result = mysqli_query($conn, $sql);

if (isset($_POST['delete'])) {
    $post_id = $_POST['delete_id'];
    $del_sql = "DELETE FROM post WHERE id = $post_id";
    mysqli_query($conn, $del_sql);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="welcom.css">
    <title>Featured Posts</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #f59608ff;">
        <a class="navbar-brand" href="welcome.php">Blog Website</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText"
            aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav ">
                <li class="nav-item active">
                    <a class="nav-link" href="welcome.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Blog</a>
                </li>
            </ul>

            <form class="form-inline d-flex align-items-center " method="get" action="features.php" style="margin: 0 auto">
                <input class="form-control mr-sm-2" type="search" name="search" placeholder="Search" aria-label="Search" value="<?php echo htmlspecialchars($search_query); ?>">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>

            <span class="navbar-text d-flex align-items-center">
                <div class="dropdown">
                    <a class="d-flex align-items-center" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false" style="text-decoration:none;">
                        <?php echo "Welcome " . $_SESSION['username']; ?>
                        <span class="user-icon ml-2" id="user-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                                class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                            </svg>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="profile.php">Profile</a>
                        <a class="dropdown-item" href="edit_profile.php">Edit Profile</a>
                        <a class="dropdown-item" href="add_post.php">Add Post</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                </div>

            </span>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12" style="margin: 0 auto;">
                    <div class="card" data-id="<?php echo $row['id']; ?>">
                        <img class="card-img-top" src="uploads/<?php echo $row['image']; ?>" alt="Post image">
                        <div class="card-body">
                            <p class="card-text text-center"><?php echo htmlspecialchars($row['discription'] ?? ''); ?></p>
                            <div class="button-group d-flex justify-content-center mx-0 ">
                                <button type="button" class="btn btn-success btn-sm me-md-2 details-btn mx-0 "
                                    data-id="<?php echo $row['id']; ?>">Details</button>
                                <button type="button" class="btn btn-info btn-sm me-md-2 edit-btn mx-0"
                                    data-id="<?php echo $row['id']; ?>">Edit</button>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="delete" class="btn btn-warning btn-sm me-md-2 remove-btn mx-0"
                                        onclick="return confirm('Are you sure you want to delete this post?');">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

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