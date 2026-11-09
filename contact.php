
<?php
include 'db.php';

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if ($name && $email && $subject && $message && filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $stmt = $conn->prepare(
            "INSERT INTO contact_messages (name, email, subject, message)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param("ssss", $name, $email, $subject, $message);

        if ($stmt->execute()) {
            $success = "Your message has been submitted successfully!";
        } else {
            $error = "Something went wrong. Please try again.";
        }

        $stmt->close();

    } else {
        $error = "Please fill all fields with a valid email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - CampusConnect</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            color: #333;
        }

        nav {
            background: #222;
            color: white;
            padding: 20px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        nav h2 {
            margin: 0;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 18px;
        }

        .contact-hero {
            background: #222;
            color: white;
            text-align: center;
            padding: 55px 20px;
        }

        .contact-hero h1 {
            font-size: 38px;
        }

        .contact-hero p {
            color: #ddd;
        }

        .contact-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr 1.3fr;
            gap: 25px;
        }

        .contact-info,
        .contact-form {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px #00000010;
        }

        .contact-info h2,
        .contact-form h2 {
            color: #222;
        }

        .contact-info p {
            line-height: 1.8;
        }

        input, textarea {
            width: 100%;
            padding: 13px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        textarea {
            min-height: 130px;
            resize: vertical;
        }

        button {
            width: 100%;
            padding: 13px;
            background: #222;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #444;
        }

        .success {
            color: green;
            margin-bottom: 15px;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }

        footer {
            background: #222;
            color: white;
            text-align: center;
            padding: 20px;
        }

        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                text-align: center;
            }

            nav a {
                margin: 8px;
                display: inline-block;
            }

            .contact-container {
                grid-template-columns: 1fr;
                padding: 12px;
                margin: 20px auto;
            }

            .contact-hero h1 {
                font-size: 29px;
            }

            .contact-info,
            .contact-form {
                padding: 18px;
            }
        }
    </style>
</head>

<body>

<nav>
    <h2>CampusConnect</h2>

    <div>
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
    </div>
</nav>

<section class="contact-hero">
    <h1>Contact Us</h1>
    <p>We would love to hear from you. Send us your questions or suggestions!</p>
</section>

<div class="contact-container">

    <div class="contact-info">
        <h2>Get In Touch</h2>

        <p>Have questions, suggestions or feedback about CampusConnect?</p>

        <p>📧 Email: campusconnect@example.com</p>
        <p>🌐 Website: CampusConnect</p>

        <p>We welcome your ideas to make our platform better.</p>
    </div>

    <div class="contact-form">
        <h2>Send Us a Message</h2>

        <?php if ($success): ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST" action="contact.php">

            <input type="text" name="name"
                   placeholder="Your Name" maxlength="100" required>

            <input type="email" name="email"
                   placeholder="Your Email" maxlength="254" required>

            <input type="text" name="subject"
                   placeholder="Subject" maxlength="200" required>

            <textarea name="message"
                      placeholder="Write your message..."
                      maxlength="5000" required></textarea>

            <button type="submit">Send Message</button>

        </form>
    </div>

</div>

<footer>
    <p>© 2026 CampusConnect. All Rights Reserved.</p>
    <p>Share Ideas, Inspire Students.</p>
</footer>

</body>
</html>
