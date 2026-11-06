<?php
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];

    if ($title != "" && $content != "") {
        $stmt = $conn->prepare(
            "INSERT INTO blogs (user_id, title, content) VALUES (?, ?, ?)"
        );

        $stmt->bind_param("iss", $user_id, $title, $content);

        if ($stmt->execute()) {
            $message = "Blog published successfully!";
        } else {
            $message = "Something went wrong.";
        }

        $stmt->close();
    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Blog - CampusConnect</title>
</head>
<body>

    <h1>Create Your Blog</h1>

    <form method="POST">
        <input type="text" name="title" placeholder="Blog Title" required>
        <br><br>

        <textarea name="content" placeholder="Write your blog..." required></textarea>
        <br><br>

        <button type="submit">Publish Blog</button>
    </form>

    <p><?php echo htmlspecialchars($message); ?></p>

    <a href="index.php">Back to Home</a>

</body>
</html>