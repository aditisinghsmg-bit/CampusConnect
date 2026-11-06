
<?php
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            header("Location: index.php");
            exit;
        }
    }

    $message = "Invalid email or password.";
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - CampusConnect</title>
</head>
<body>

    <h1>Welcome Back!</h1>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <br><br>

        <input type="password" name="password" placeholder="Password" required>
        <br><br>

        <button type="submit">Login</button>
    </form>

    <p><?php echo htmlspecialchars($message); ?></p>

    <a href="register.php">Create an account</a>

</body>
</html>
