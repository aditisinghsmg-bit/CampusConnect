
<?php
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid blog ID.");
}

$blog_id = (int) $_GET['id'];

$stmt = $conn->prepare("
    SELECT blogs.*, users.name
    FROM blogs
    JOIN users ON blogs.user_id = users.id
    WHERE blogs.id = ?
");

$stmt->bind_param("i", $blog_id);
$stmt->execute();

$result = $stmt->get_result();
$blog = $result->fetch_assoc();


if (!$blog) {
    die("Blog not found.");
}

$commentMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_comment'])) {

    if (!isset($_SESSION['user_id'])) {
        $commentMessage = "Please login to comment.";
    } else {
        $commentText = trim($_POST['comment'] ?? '');

        if ($commentText !== "") {

            $user_id = $_SESSION['user_id'];

            $commentStmt = $conn->prepare(
                "INSERT INTO comments (blog_id, user_id, comment)
                 VALUES (?, ?, ?)"
            );

            $commentStmt->bind_param(
                "iis",
                $blog_id,
                $user_id,
                $commentText
            );

            if ($commentStmt->execute()) {
                header("Location: view_blog.php?id=" . $blog_id);
                exit;
            } else {
                $commentMessage = "Could not post your comment.";
            }

            $commentStmt->close();

        } else {
            $commentMessage = "Please write a comment.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title><?php echo htmlspecialchars($blog['title']); ?> - CampusConnect</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            margin: 0;
            padding: 30px;
            box-sizing: border-box;
        }

        .blog-container {
            max-width: 850px;
            width: 100%;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-sizing: border-box;
        }

        .blog-container h1 {
            color: #333;
            overflow-wrap: break-word;
        }

        .blog-image {
            width: 100%;
            max-height: 450px;
            object-fit: cover;
            border-radius: 10px;
            margin: 20px 0;
        }

        .blog-content {
            line-height: 1.8;
            color: #444;
            white-space: pre-wrap;
            overflow-wrap: break-word;
        }

        .author {
            color: #777;
        }

        a {
            color: #3155c5;
            text-decoration: none;
        }

        @media (max-width: 600px) {
            body {
                padding: 12px;
            }

            .blog-container {
                margin: 15px auto;
                padding: 18px;
            }

            .blog-container h1 {
                font-size: 25px;
            }

            .blog-image {
                max-height: 280px;
            }

            .blog-content {
                font-size: 15px;
            }
        }
    </style>
</head>
<body>

<div class="blog-container">

    <a href="index.php">← Back to Home</a>

    <h1><?php echo htmlspecialchars($blog['title']); ?></h1>

    <p class="author">
        Published by: <?php echo htmlspecialchars($blog['name']); ?>
    </p>

    <?php if (!empty($blog['image'])): ?>
        <img
            src="<?php echo htmlspecialchars($blog['image']); ?>"
            class="blog-image"
            alt="Blog Image"
        >
    <?php endif; ?>

    <div class="blog-content">
        <?php echo htmlspecialchars($blog['content']); ?>
    </div>
<hr>
<?php if (isset($_SESSION['user_id'])): ?>

    <h2>Write a Comment</h2>
<p><?php echo htmlspecialchars($commentMessage); ?></p>

    <form method="POST">
        <textarea
            name="comment"
            placeholder="Write your comment..."
            required
            style="width:100%; padding:12px;"
        ></textarea>

        <br><br>

        <button type="submit" name="submit_comment">
            Post Comment
        </button>
    </form>

<?php else: ?>

    <p>
        <a href="login.php">Login</a> to write a comment.
    </p>

<?php endif; ?>
<h2>Comments</h2>

<?php
$commentQuery = $conn->prepare("
    SELECT comments.id, comments.user_id, comments.comment, comments.created_at, users.name
    FROM comments
    JOIN users ON comments.user_id = users.id
    WHERE comments.blog_id = ?
    ORDER BY comments.created_at DESC
");

$commentQuery->bind_param("i", $blog_id);
$commentQuery->execute();
$commentResult = $commentQuery->get_result();

while ($comment = $commentResult->fetch_assoc()):
?>

<div style="padding:15px; border-bottom:1px solid #ddd;">
    <strong><?php echo htmlspecialchars($comment['name']); ?></strong>
<?php if (isset($_SESSION['user_id'])): ?>
    <?php if ($_SESSION['user_id'] == $comment['user_id']): ?>
        <a href="delete_comment.php?id=<?php echo $comment['id']; ?>&blog_id=<?php echo $blog_id; ?>"
           onclick="return confirm('Delete this comment?');">
            Delete
        </a>
    <?php endif; ?>
<?php endif; ?>
    <p><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>

    <small><?php echo htmlspecialchars($comment['created_at']); ?></small>
</div>

<?php endwhile; ?>
</div>

</body>
</html>
