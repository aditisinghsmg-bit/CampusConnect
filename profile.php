
<?php
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$userStmt = $conn->prepare(
    "SELECT name, email FROM users WHERE id = ?"
);
$userStmt->bind_param("i", $user_id);
$userStmt->execute();

$user = $userStmt->get_result()->fetch_assoc();

$blogStmt = $conn->prepare(
    "SELECT id, title, content, created_at
     FROM blogs
     WHERE user_id = ?
     ORDER BY created_at DESC"
);
$blogStmt->bind_param("i", $user_id);
$blogStmt->execute();

$myBlogs = $blogStmt->get_result();
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Profile - CampusConnect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 30px;
            margin: 0;
            box-sizing: border-box;
        }

        .profile-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            max-width: 750px;
            margin: auto;
            box-sizing: border-box;
        }

        .blog-item {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            overflow-wrap: break-word;
        }

        a {
            color: #222;
        }

        @media (max-width: 600px) {
            body {
                padding: 12px;
            }

            .profile-box {
                padding: 16px;
                width: 100%;
            }

            .blog-item {
                padding: 12px 0;
            }
        }
    </style>
</head>
<body>

<div class="profile-box">

    <h1>My Profile</h1>

    <?php if ($user): ?>
        <h3>Name: <?php echo htmlspecialchars($user['name']); ?></h3>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
    <?php endif; ?>

    <hr>

    <h2>My Published Blogs</h2>

    <?php if ($myBlogs->num_rows > 0): ?>

        <?php while ($blog = $myBlogs->fetch_assoc()): ?>

            <div class="blog-item">
                <h3><?php echo htmlspecialchars($blog['title']); ?></h3>

                <p>
                    <?php echo htmlspecialchars(substr($blog['content'], 0, 150)); ?>...
                </p>

                <a href="view_blog.php?id=<?php echo $blog['id']; ?>">
                    Read Blog →
                </a>
            </div>

        <?php endwhile; ?>

    <?php else: ?>

        <p>You haven't published any blogs yet.</p>

    <?php endif; ?>

    <br>
    <a href="index.php">Back to Home</a>

</div>

</body>
</html>
