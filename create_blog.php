
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
    $imagePath = "";

    if ($title != "" && $content != "") {

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            $imageInfo = getimagesize($_FILES['image']['tmp_name']);

            if (in_array($extension, $allowed) &&
                $imageInfo !== false &&
                $_FILES['image']['size'] <= 2 * 1024 * 1024) {

                $fileName = uniqid('blog_', true) . '.' . $extension;
                $destination = 'uploads/' . $fileName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    $imagePath = $destination;
                } else {
                    $message = "Image upload failed.";
                }

            } else {
                $message = "Upload a valid JPG, PNG or WEBP image under 2 MB.";
            }
        }

        if ($message == "") {
            $stmt = $conn->prepare(
                "INSERT INTO blogs (user_id, title, content, image) VALUES (?, ?, ?, ?)"
            );

            $stmt->bind_param("isss", $user_id, $title, $content, $imagePath);

            if ($stmt->execute()) {
                $message = "Blog published successfully!";
            } else {
                $message = "Something went wrong.";
            }

            $stmt->close();
        }

    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create Blog - CampusConnect</title>
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
            max-width: 600px;
            margin: 30px auto;
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

    <h1>Create Your Blog</h1>

    <form method="POST" enctype="multipart/form-data">

        <input type="text" name="title" placeholder="Blog Title" required>
        <br><br>

        <textarea name="content" placeholder="Write your blog..." required></textarea>
        <br><br>

        <label>Select Blog Image:</label>
        <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
        <br><br>

        <button type="submit">Publish Blog</button>

    </form>

    <p><?php echo htmlspecialchars($message); ?></p>

    <a href="index.php">Back to Home</a>

</body>
</html>
