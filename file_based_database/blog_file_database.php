<?php
class FileBlog
{
    private string $postsDir = "posts/";
    private string $authorsDir = "authors/";
    private string $commentsDir = "comments/";

    public function __construct()
    {
        if (!is_dir($this->postsDir)) {
            mkdir($this->postsDir, 0777, true);
        }
        if (!is_dir($this->authorsDir)) {
            mkdir($this->authorsDir, 0777, true);
        }
        if (!is_dir($this->commentsDir)) {
            mkdir($this->commentsDir, 0777, true);
        }
    }

    // Posts methods
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
        echo "<h3 style=\"color:greenyellow;\">Creating post with title {$new_post['title']}</h3>";
        return file_put_contents($new_post_file, $new_post_json);
    }

    public function getPost(string $id): array|null
    {
        $filename = $this->postsDir . $id . ".json";
        if (!file_exists($filename)) {
            return null;
        }
        $searched_post = json_decode(file_get_contents($filename), true);
        echo "<h3 style=\"color:greenyellow;\">Searching for post with title {$searched_post['title']}</h3>";
        return $searched_post;
    }

    public function getAllPosts(): array|null
    {
        $posts = [];
        $files = glob($this->postsDir . "*.json");
        foreach ($files as $file) {
            $post = json_decode(file_get_contents($file), true);
            echo "<h3 style=\"color:cyan;\">Getting post with title {$post['title']}</h3>";
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
            echo "<h3 style=\"color:yellow;\">Updating post with title {$updated_post['title']}</h3>";
            return file_put_contents($filename, json_encode($updated_post, JSON_PRETTY_PRINT));
        }
        return false;
    }

    public function deletePost($id): bool
    {
        $filename = $this->postsDir . $id . ".json";
        if (file_exists($filename)) {
            echo "<h3 style=\"color:red;\">Deleting post {$filename}</h3>";
            return unlink($filename);
        }
        return false;
    }

    // Authors methods
    public function createAuthor(string $name, string $email): bool
    {
        $new_author = [
            "id" => uniqid("author_"),
            "name" => $name,
            "email" => $email
        ];
        $new_author_json = json_encode($new_author, JSON_PRETTY_PRINT);
        $new_author_file = $this->authorsDir . $new_author["id"] . ".json";
        echo "<h3 style=\"color:greenyellow;\">Creating author with name {$new_author['name']}</h3>";
        return file_put_contents($new_author_file, $new_author_json);
    }

    public function getAuthor(string $id): array|null
    {
        $filename = $this->authorsDir . $id . ".json";
        if (!file_exists($filename)) {
            return null;
        }
        $searched_author = json_decode(file_get_contents($filename), true);
        echo "<h3 style=\"color:greenyellow;\">Searching for author with name {$searched_author['name']}</h3>";
        return $searched_author;
    }

    public function getAllAuthors(): array|null
    {
        $authors = [];
        $files = glob($this->authorsDir . "*.json");
        foreach ($files as $file) {
            $author = json_decode(file_get_contents($file), true);
            echo "<h3 style=\"color:cyan;\">Getting author with name {$author['name']}</h3>";
            $authors[] = $author;
        }
        return $authors;
    }

    public function updateAuthor($id, $new_data): bool
    {
        $author = $this->getAuthor($id);
        if ($author) {
            $updated_author = array_merge($author, $new_data);
            $filename = $this->authorsDir . $author["id"] . ".json";
            echo "<h3 style=\"color:yellow;\">Updating author with name {$updated_author['name']}</h3>";
            return file_put_contents($filename, json_encode($updated_author, JSON_PRETTY_PRINT));
        }
        return false;
    }

    public function deleteAuthor($id): bool
    {
        $filename = $this->authorsDir . $id . ".json";
        if (file_exists($filename)) {
            echo "<h3 style=\"color:red;\">Deleting author {$filename}</h3>";
            return unlink($filename);
        }
        return false;
    }

    // Comments methods
    public function createComment(string $content, int $authorIndex, int $postIndex): bool
    {
        // Get all author files
        $authorFiles = glob($this->authorsDir . "*.json");
        // Get all post files
        $postFiles = glob($this->postsDir . "*.json");

        // Adjust indices to be 0-based
        $authorIndex--;
        $postIndex--;

        // Check if the indices are valid
        if ($authorIndex < 0 || $authorIndex >= count($authorFiles)) {
            echo "<h3 style=\"color:red;\">Invalid author index</h3>";
            return false;
        }
        if ($postIndex < 0 || $postIndex >= count($postFiles)) {
            echo "<h3 style=\"color:red;\">Invalid post index</h3>";
            return false;
        }

        // Get the author ID from the file at the given index
        $authorFile = $authorFiles[$authorIndex];
        $authorData = json_decode(file_get_contents($authorFile), true);
        $authorId = $authorData['id'];

        // Get the post ID from the file at the given index
        $postFile = $postFiles[$postIndex];
        $postData = json_decode(file_get_contents($postFile), true);
        $postId = $postData['id'];

        // Create the comment with the retrieved IDs
        $new_comment = [
            "id" => uniqid("comment_"),
            "content" => $content,
            "author_id" => $authorId,
            "post_id" => $postId,
            "created_at" => date("Y-m-d H:i:s")
        ];
        $new_comment_json = json_encode($new_comment, JSON_PRETTY_PRINT);
        $new_comment_file = $this->commentsDir . $new_comment["id"] . ".json";
        echo "<h3 style=\"color:greenyellow;\">Creating comment for post {$new_comment['post_id']}</h3>";
        return file_put_contents($new_comment_file, $new_comment_json);
    }

    public function getComment(string $id): array|null
    {
        $filename = $this->commentsDir . $id . ".json";
        if (!file_exists($filename)) {
            return null;
        }
        $searched_comment = json_decode(file_get_contents($filename), true);
        echo "<h3 style=\"color:greenyellow;\">Searching for comment with ID {$searched_comment['id']}</h3>";
        return $searched_comment;
    }

    public function getAllComments(): array|null
    {
        $comments = [];
        $files = glob($this->commentsDir . "*.json");
        foreach ($files as $file) {
            $comment = json_decode(file_get_contents($file), true);
            echo "<h3 style=\"color:cyan;\">Getting comment with ID {$comment['id']}</h3>";
            $comments[] = $comment;
        }
        return $comments;
    }

    public function updateComment($id, $new_data): bool
    {
        $comment = $this->getComment($id);
        if ($comment) {
            $updated_comment = array_merge($comment, $new_data);
            $filename = $this->commentsDir . $comment["id"] . ".json";
            echo "<h3 style=\"color:yellow;\">Updating comment with ID {$updated_comment['id']}</h3>";
            return file_put_contents($filename, json_encode($updated_comment, JSON_PRETTY_PRINT));
        }
        return false;
    }

    public function deleteComment($id): bool
    {
        $filename = $this->commentsDir . $id . ".json";
        if (file_exists($filename)) {
            echo "<h3 style=\"color:red;\">Deleting comment {$filename}</h3>";
            return unlink($filename);
        }
        return false;
    }
}

// Usage
$blog = new FileBlog();

// Create some authors
$blog->createAuthor("John Doe", "john.doe@example.com");
$blog->createAuthor("Jane Smith", "jane.smith@example.com");

// Create some posts
$blog->createPost("Welcome to My Blog", "This is my first post!", "John Doe");
$blog->createPost("PHP Tips", "Here are some PHP tips...", "John Doe");
$blog->createPost("Database Tutorial", "Databases are important...", "Jane Smith");

// Create some comments using indices
$blog->createComment("Great post!", 1, 1); // First author, first post
$blog->createComment("Thanks for the tips!", 2, 2); // Second author, second post

// Display all posts
$posts = $blog->getAllPosts();

// Display all authors
$authors = $blog->getAllAuthors();

// Display all comments
$comments = $blog->getAllComments();
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

        .posts-container,
        .authors-container,
        .comments-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-bottom: 30px;
        }

        .post-card,
        .author-card,
        .comment-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .post-card:hover,
        .author-card:hover,
        .comment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .post-card h2,
        .author-card h2,
        .comment-card h2 {
            margin-top: 0;
            color: #2c3e50;
        }

        .post-card p,
        .author-card p,
        .comment-card p {
            color: #34495e;
        }

        .post-meta,
        .author-meta,
        .comment-meta {
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

        .comment-card {
            border-left: 4px solid #e74c3c;
        }
    </style>
</head>

<body>
    <h1>Blog Posts</h1>
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

    <h1>Authors</h1>
    <div class="authors-container">
        <?php foreach ($authors as $author): ?>
            <div class="author-card">
                <h2><?php echo htmlspecialchars($author['name']); ?></h2>
                <p>Email: <?php echo htmlspecialchars($author['email']); ?></p>
                <div class="author-meta">
                    ID: <?php echo htmlspecialchars($author['id']); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h1>Comments</h1>
    <div class="comments-container">
        <?php foreach ($comments as $comment): ?>
            <div class="comment-card">
                <p><?php echo htmlspecialchars($comment['content']); ?></p>
                <div class="comment-meta">
                    Author ID: <?php echo htmlspecialchars($comment['author_id']); ?><br>
                    Post ID: <?php echo htmlspecialchars($comment['post_id']); ?><br>
                    Created at: <?php echo htmlspecialchars($comment['created_at']); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>