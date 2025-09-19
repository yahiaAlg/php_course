<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .nav {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .nav a {
            padding: 10px 20px;
            text-decoration: none;
            color: #333;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .nav a.active {
            background-color: #fff;
            border-bottom: 1px solid #fff;
        }

        .posts-container,
        .authors-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .post-card,
        .author-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .post-card:hover,
        .author-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .post-card h2,
        .author-card h2 {
            margin-top: 0;
            color: #2c3e50;
        }

        .post-card p,
        .author-card p {
            color: #34495e;
        }

        .post-meta,
        .author-meta {
            font-size: 0.8em;
            color: #7f8c8d;
            margin-top: 10px;
        }

        .post-card {
            border-left: 4px solid #3498db;
        }

        .author-card {
            border-left: 4px solid #2ecc71;
        }

        .post-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .secondary-images {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .secondary-images img {
            width: 100px;
            height: 75px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <nav class="nav">
            <a href="posts.php" <?php if (isset($current_page) && $current_page == 'posts') echo 'class="active"'; ?>>All Posts</a>
            <a href="authors.php" <?php if (isset($current_page) && $current_page == 'authors') echo 'class="active"'; ?>>All Authors</a>
        </nav>