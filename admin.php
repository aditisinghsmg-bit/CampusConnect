
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
    die("Access Denied! Admins only.");
}

$users = $conn->query("SELECT id, name, email FROM users ORDER BY id DESC");

$blogs = $conn->query("
    SELECT blogs.id, blogs.title, users.name
    FROM blogs
    JOIN users ON blogs.user_id = users.id
    ORDER BY blogs.id DESC
");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard - CampusConnect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
            color: #222;
            box-sizing: border-box;
        }

        h1 {
            background: #222;
            color: white;
            padding: 22px;
            border-radius: 10px;
        }

        h2 {
            margin-top: 35px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 15px;
            border-radius: 8px;
        }

        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #333;
            color: white;
        }

        button {
            background: #d93636;
            color: white;
            border: none;
            padding: 9px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        a {
            color: #333;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            body {
                padding: 12px;
            }

            h1 {
                font-size: 24px;
                padding: 16px;
            }

            h2 {
                font-size: 20px;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            th, td {
                padding: 10px;
            }

            button {
                padding: 8px 12px;
            }
        }
    </style>
</head>

<body>

<h1>CampusConnect Admin Dashboard</h1>
<?php
$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];

$totalBlogs = $conn->query("SELECT COUNT(*) AS total FROM blogs")->fetch_assoc()['total'];
$messages = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
$totalMessages = $conn->query("SELECT COUNT(*) AS total FROM contact_messages")->fetch_assoc()['total'];
?>

<div style="display:flex; gap:20px; margin:25px 0; flex-wrap:wrap;">

    <div style="background:white; padding:25px; border-radius:10px; flex:1;">
        <h3>Total Users</h3>
        <h1><?php echo $totalUsers; ?></h1>
    </div>

    <div style="background:white; padding:25px; border-radius:10px; flex:1;">
        <h3>Total Blogs</h3>
        <h1><?php echo $totalBlogs; ?></h1>
    </div>
<div style="background:white; padding:25px; border-radius:10px; flex:1;">
    <h3>Total Messages</h3>
    <h1><?php echo $totalMessages; ?></h1>
</div>
</div>

<hr>

<h2>Registered Users</h2>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
</tr>

<?php while ($user = $users->fetch_assoc()): ?>
<tr>
    <td><?php echo $user['id']; ?></td>
    <td><?php echo htmlspecialchars($user['name']); ?></td>
    <td><?php echo htmlspecialchars($user['email']); ?></td>
</tr>
<?php endwhile; ?>
</table>

<hr>

<h2>Published Blogs</h2>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Blog Title</th>
    <th>Author</th>
<th>Action</th>
</tr>

<?php while ($blog = $blogs->fetch_assoc()): ?>
<tr>
    <td><?php echo $blog['id']; ?></td>
    <td><?php echo htmlspecialchars($blog['title']); ?></td>
    <td><?php echo htmlspecialchars($blog['name']); ?></td>
<td>
    <form method="POST" action="delete_blog.php"
          onsubmit="return confirm('Are you sure you want to delete this blog?');">

        <input type="hidden" name="blog_id"
               value="<?php echo $blog['id']; ?>">

        <button type="submit">Delete Blog</button>
    </form>
</td>
</tr>
<?php endwhile; ?>
</table>

<br>
<hr>

<h2>Contact Messages</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Subject</th>
        <th>Message</th>
        <th>Date</th>
    </tr>

    <?php while ($message = $messages->fetch_assoc()): ?>
    <tr>
        <td><?php echo $message['id']; ?></td>
        <td><?php echo htmlspecialchars($message['name']); ?></td>
        <td><?php echo htmlspecialchars($message['email']); ?></td>
        <td><?php echo htmlspecialchars($message['subject']); ?></td>
        <td><?php echo nl2br(htmlspecialchars($message['message'])); ?></td>
        <td><?php echo $message['created_at']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<br>
<a href="index.php">Back to Homepage</a>

</body>
</html>
