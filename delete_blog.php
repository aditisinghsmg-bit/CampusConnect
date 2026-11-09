
<?php
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$check = $conn->prepare(
    "SELECT is_admin FROM users WHERE id = ?"
);
$check->bind_param("i", $user_id);
$check->execute();

$admin = $check->get_result()->fetch_assoc();

if (!$admin || $admin['is_admin'] != 1) {
    die("Access Denied!");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST['blog_id'])) {

    $blog_id = (int) $_POST['blog_id'];

    $stmt = $conn->prepare(
        "DELETE FROM blogs WHERE id = ?"
    );

    $stmt->bind_param("i", $blog_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: admin.php");
exit;
?>
