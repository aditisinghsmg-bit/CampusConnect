
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        form {
            width: 100%;
            max-width: 450px;
            margin: 30px auto;
            box-sizing: border-box;
        }

        input, button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            box-sizing: border-box;
        }

        @media (max-width: 480px) {
            body {
                padding: 12px;
            }

            form {
                margin: 20px auto;
            }
        }
    </style>
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
