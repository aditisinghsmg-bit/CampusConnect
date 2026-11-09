
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - CampusConnect</title>

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
            padding: 20px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        nav h2 {
            color: white;
            margin: 0;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 18px;
        }

        .about-hero {
            background: #222;
            color: white;
            text-align: center;
            padding: 75px 20px;
        }

        .about-hero h1 {
            font-size: 42px;
            margin-bottom: 15px;
        }

        .about-hero p {
            max-width: 700px;
            margin: auto;
            line-height: 1.8;
            color: #ddd;
        }

        .about-content {
            max-width: 1100px;
            margin: 45px auto;
            padding: 20px;
        }

        .about-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 3px 12px #00000010;
        }

        .about-card h2 {
            color: #222;
        }

        .about-card p {
            line-height: 1.8;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .feature {
            background: white;
            padding: 25px;
            text-align: center;
            border-radius: 10px;
        }

        .feature h3 {
            color: #222;
        }

        footer {
            background: #222;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
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

            .about-hero h1 {
                font-size: 30px;
            }

            .features {
                grid-template-columns: 1fr;
            }

            .about-content {
                margin: 20px auto;
                padding: 12px;
            }

            .about-card {
                padding: 20px;
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
        <a href="create_blog.php">Write Blog</a>
        <a href="profile.php">Profile</a>
    </div>
</nav>

<section class="about-hero">
    <h1>About CampusConnect</h1>

    <p>
        A platform where students connect, share knowledge,
        express ideas and inspire one another through blogging.
    </p>
</section>

<div class="about-content">

    <div class="about-card">
        <h2>Who We Are</h2>

        <p>
            CampusConnect is a college blogging platform designed
            to bring students together in one digital space.
            It allows students to publish blogs, share experiences,
            discuss technology and explore educational content.
        </p>
    </div>

    <div class="about-card">
        <h2>Our Mission</h2>

        <p>
            Our mission is to encourage creativity, knowledge sharing
            and communication among students. We aim to provide a
            platform where every student can share their thoughts
            and learn from others.
        </p>
    </div>

    <h2 style="text-align:center;">What We Offer</h2>

    <div class="features">

        <div class="feature">
            <h3>✍️ Share Blogs</h3>
            <p>Write and publish your ideas and experiences.</p>
        </div>

        <div class="feature">
            <h3>💡 Share Knowledge</h3>
            <p>Explore coding tips, projects and study resources.</p>
        </div>

        <div class="feature">
            <h3>🤝 Connect</h3>
            <p>Interact with other students through comments.</p>
        </div>

    </div>

</div>

<footer>
    <p>© 2026 CampusConnect. All Rights Reserved.</p>
    <p>Share Ideas, Inspire Students.</p>
</footer>

</body>
</html>
