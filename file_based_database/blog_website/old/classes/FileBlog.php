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
    public function createPost(string $title, string $content, string $author, string $mainImageUrl, array $secondaryImageUrls): bool
    {
        $new_post = [
            "id" => uniqid("post_"),
            "title" => $title,
            "content" => $content,
            "author" => $author,
            "created_at" => date("Y-m-d H:i:s"),
            "views" => 0,
            "published" => true,
            "main_image" => $mainImageUrl,
            "secondary_images" => $secondaryImageUrls
        ];
        $new_post_json = json_encode($new_post, JSON_PRETTY_PRINT);
        $new_post_file = $this->postsDir . $new_post["id"] . ".json";
        return file_put_contents($new_post_file, $new_post_json);
    }

    public function getPost(string $id): array|null
    {
        $filename = $this->postsDir . $id . ".json";
        if (!file_exists($filename)) {
            return null;
        }
        return json_decode(file_get_contents($filename), true);
    }

    public function getAllPosts(): array|null
    {
        $posts = [];
        $files = glob($this->postsDir . "*.json");
        foreach ($files as $file) {
            $posts[] = json_decode(file_get_contents($file), true);
        }
        return $posts;
    }

    public function updatePost($id, $new_data): bool
    {
        $post = $this->getPost($id);
        if ($post) {
            $updated_post = array_merge($post, $new_data);
            $filename = $this->postsDir . $post["id"] . ".json";
            return file_put_contents($filename, json_encode($updated_post, JSON_PRETTY_PRINT));
        }
        return false;
    }

    public function deletePost($id): bool
    {
        $filename = $this->postsDir . $id . ".json";
        if (file_exists($filename)) {
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
        return file_put_contents($new_author_file, $new_author_json);
    }

    public function getAuthor(string $id): array|null
    {
        $filename = $this->authorsDir . $id . ".json";
        if (!file_exists($filename)) {
            return null;
        }
        return json_decode(file_get_contents($filename), true);
    }

    public function getAllAuthors(): array|null
    {
        $authors = [];
        $files = glob($this->authorsDir . "*.json");
        foreach ($files as $file) {
            $authors[] = json_decode(file_get_contents($file), true);
        }
        return $authors;
    }

    public function updateAuthor($id, $new_data): bool
    {
        $author = $this->getAuthor($id);
        if ($author) {
            $updated_author = array_merge($author, $new_data);
            $filename = $this->authorsDir . $author["id"] . ".json";
            return file_put_contents($filename, json_encode($updated_author, JSON_PRETTY_PRINT));
        }
        return false;
    }

    public function deleteAuthor($id): bool
    {
        $filename = $this->authorsDir . $id . ".json";
        if (file_exists($filename)) {
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
            return false;
        }
        if ($postIndex < 0 || $postIndex >= count($postFiles)) {
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
        return file_put_contents($new_comment_file, $new_comment_json);
    }

    public function getComment(string $id): array|null
    {
        $filename = $this->commentsDir . $id . ".json";
        if (!file_exists($filename)) {
            return null;
        }
        return json_decode(file_get_contents($filename), true);
    }

    public function getAllComments(): array|null
    {
        $comments = [];
        $files = glob($this->commentsDir . "*.json");
        foreach ($files as $file) {
            $comments[] = json_decode(file_get_contents($file), true);
        }
        return $comments;
    }

    public function updateComment($id, $new_data): bool
    {
        $comment = $this->getComment($id);
        if ($comment) {
            $updated_comment = array_merge($comment, $new_data);
            $filename = $this->commentsDir . $comment["id"] . ".json";
            return file_put_contents($filename, json_encode($updated_comment, JSON_PRETTY_PRINT));
        }
        return false;
    }

    public function deleteComment($id): bool
    {
        $filename = $this->commentsDir . $id . ".json";
        if (file_exists($filename)) {
            return unlink($filename);
        }
        return false;
    }
}
