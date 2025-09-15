<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
require_once 'config.php';

if (!isset($_GET['id'])) {
    echo "No post selected.";
    exit;
}
$post_id = intval($_GET['id']);

// Fetch single post data
$res = mysqli_query($conn, "SELECT * FROM post WHERE id = $post_id");
if ($res && mysqli_num_rows($res) > 0) {
    $post = mysqli_fetch_assoc($res);
} else {
    echo "Post not found.";
    exit;
}

// Handle delete
if (isset($_POST['delete'])) {
    $del_sql = "DELETE FROM post WHERE id = $post_id";
    mysqli_query($conn, $del_sql);
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Post Details</title>
</head>
<body>
 <img class="card-img-top" src="uploads/<?php echo $post['image']; ?>" alt="Product image">
    <p><?php echo htmlspecialchars($post['discription']); ?></p>
    <form method="post" style="display:inline;">
        <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this post?');">Remove</button>
    </form>
    <a href="edit_post.php?id=<?php echo $post_id; ?>"><button>Edit</button></a>
    <a href="welcome.php"><button>Back</button></a>
</body>
</html>