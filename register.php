
<?php
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($name == "" || $email == "" || $password == "") {
        $message = "Please fill all fields.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO users (name, email, password) VALUES (?, ?, ?)"
        );

        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            $message = "Registration successful!";
        } else {
            $message = "Email already registered or an error occurred.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - CampusConnect</title>
</head>
<body>
    <h1>Join CampusConnect</h1>

    <form method="POST">
        <input type="text" name="name" placeholder="Your Name" required>
        <br><br>

        <input type="email" name="email" placeholder="Your Email" required>
        <br><br>

        <input type="password" name="password" placeholder="Password" required>
        <br><br>

        <button type="submit">Register</button>
    </form>

    <p><?php echo htmlspecialchars($message); ?></p>
</body>
</html>
