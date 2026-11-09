
<?php
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid blog ID.");
}

$blog_id = (int) $_GET['id'];
$user_id = (int) $_SESSION['user_id'];

$stmt = $conn->prepare(
    "SELECT title, content FROM blogs WHERE id = ? AND user_id = ?"
);
$stmt->bind_param("ii", $blog_id, $user_id);
$stmt->execute();

$result = $stmt->get_result();
$blog = $result->fetch_assoc();

if (!$blog) {
    die("Blog not found or you cannot edit it.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title !== "" && $content !== "") {
        $update = $conn->prepare(
            "UPDATE blogs SET title = ?, content = ? WHERE id = ? AND user_id = ?"
        );
        $update->bind_param("ssii", $title, $content, $blog_id, $user_id);

        if ($update->execute()) {
            header("Location: view_blog.php?id=" . $blog_id);
            exit;
        } else {
            $message = "Could not update your blog.";
        }
        $update->close();
    } else {
        $message = "Please fill in both fields.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Blog - CampusConnect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f6fa;
            box-sizing: border-box;
        }

        form {
            width: 100%;
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-sizing: border-box;
        }

        input, textarea, button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            box-sizing: border-box;
        }

        textarea {
            min-height: 150px;
            resize: vertical;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        @media (max-width: 480px) {
            body {
                padding: 12px;
            }

            form {
                margin: 15px auto;
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<h2>Edit Your Blog</h2>

<p><?php echo htmlspecialchars($message); ?></p>

<form method="POST">
    <label>Blog Title:</label><br>
    <input type="text" name="title"
           value="<?php echo htmlspecialchars($blog['title']); ?>"
           required><br><br>

    <label>Blog Content:</label><br>
    <textarea name="content" rows="10" required><?php
        echo htmlspecialchars($blog['content']);
    ?></textarea><br><br>

    <button type="submit">Update Blog</button>
</form>

<a href="index.php">Back to Home</a>

</body>
</html>
