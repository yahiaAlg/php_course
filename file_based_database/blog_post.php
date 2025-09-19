<?php
class FileBlog
{
    private string $postsDir = "posts/";

    public function __construct()
    {
        if (!is_dir($this->postsDir)) {
            mkdir($this->postsDir, 077, true);
        }
    }


    public function createPost(string $title, string $content, string $author): bool
    {
        $new_post = [
            "id" => uniqid("post_"),
            "title" => $title,
            "content" => $content,
            "author" => $author,
            "created_at" => date("Y-m-d H:i:s"),
            "views" => 0,
            "published" => true
        ];
        $new_post_json = json_encode($new_post, JSON_PRETTY_PRINT);
        $new_post_file = $this->postsDir . $new_post["id"] . ".json";
        echo "<h3 style=\"color:greenyellow;\">creating {$new_post['title']}</h3>";
        return file_put_contents($new_post_file, $new_post_json);
    }


    public function getPost(string $id): array|null
    {
        $filename = $this->postsDir . $id . ".json";
        if (!file_exists($filename)) {
            return null;
        }
        $searched_post = json_decode(file_get_contents($filename), true);
        echo "<h3 style=\"color:greenyellow;\">searching for post with title {$searched_post['title']}</h3>";

        return $searched_post;
    }

    public function getAllPosts(): array | null
    {
        $posts = [];
        $files = glob($this->postsDir . "*.json");
        foreach ($files as $file) {
            $post = json_decode(file_get_contents($file, true), true);
            echo "<h3 style=\"color:cyan;\">getting for post with title {$post['title']}</h3>";

            $posts[] = $post;
        }

        return $posts;
    }

    public function updatePost($id, $new_data): bool
    {
        $post = $this->getPost($id);
        if ($post) {
            $updated_post = array_merge($post, $new_data);
            $filename = $this->postsDir . $post["id"] . ".json";
            echo "<h3 style=\"color:yellow;\">updating  post with title {$updated_post['title']}</h3>";

            return file_put_contents($filename, json_encode($updated_post, JSON_PRETTY_PRINT));
        }
        return false;
    }

    public function deletePost($id): bool
    {
        $filename = $this->postsDir . $id . ".json";
        if (file_exists($filename)) {
            echo "<h3 style=\"color:red;\">deleting post {$filename}</h3>";
            return unlink($filename);
        }

        return true;
    }
    public function deleteAllPosts(): bool
    {
        $files = glob($this->postsDir . "*.json");
        foreach ($files as $filename) {
            echo "<h3 style=\"color:red;\">deleting post {$filename}</h3>";
            unlink($filename);
        }
        return true;
    }
}




// Usage
$blog = new FileBlog();

// Create some posts
$blog->createPost("Welcome to My Blog", "This is my first post!", "John");
$blog->createPost("PHP Tips", "Here are  some PHP tips...", "John");
$blog->createPost("Database Tutorial", "Databases are important...", "Jane");

// Display all posts
$posts = $blog->getAllPosts();

?>




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

        .posts-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .post-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .post-card h2 {
            margin-top: 0;
            color: #2c3e50;
        }

        .post-card p {
            color: #34495e;
        }

        .post-meta {
            font-size: 0.8em;
            color: #7f8c8d;
            margin-top: 10px;
        }

        .post-card {
            border-left: 4px solid #3498db;
        }
    </style>
</head>

<body>
    <div class="posts-container">
        <?php foreach ($posts as $post): ?>
            <div class="post-card">
                <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                <p><?php echo htmlspecialchars($post['content']); ?></p>
                <div class="post-meta">
                    Author: <?php echo htmlspecialchars($post['author']); ?><br>
                    Created at: <?php echo htmlspecialchars($post['created_at']); ?><br>
                    Views: <?php echo htmlspecialchars($post['views']); ?><br>
                    Status: <?php echo $post['published'] ? 'Published' : 'Draft'; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>

<?php
echo "deleting all posts after ";
for ($i = 1; $i <= 3; $i++) {
    echo $i . " ";
    sleep(1);
}
$blog->deleteAllPosts();
?>