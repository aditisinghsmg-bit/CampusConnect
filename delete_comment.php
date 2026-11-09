<?php
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id']) && isset($_GET['blog_id'])) {

    $comment_id = (int) $_GET['id'];
    $blog_id = (int) $_GET['blog_id'];
    $user_id = (int) $_SESSION['user_id'];

    $stmt = $conn->prepare(
        "DELETE FROM comments WHERE id = ? AND user_id = ? AND blog_id = ?"
    );

    $stmt->bind_param("iii", $comment_id, $user_id, $blog_id);
    $stmt->execute();
    $stmt->close();

    header("Location: view_blog.php?id=" . $blog_id);
    exit;
}

header("Location: index.php");
exit;
?>