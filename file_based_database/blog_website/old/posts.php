<?php
$current_page = 'posts';
require_once 'classes/FileBlog.php';
$blog = new FileBlog();
$posts = $blog->getAllPosts();
include 'includes/header.php';
?>

<h1>All Posts</h1>
<div class="posts-container">
    <?php foreach ($posts as $post): ?>
        <div class="post-card">
            <img src="<?php echo htmlspecialchars($post['main_image']); ?>" alt="Main Image" class="post-image">
            <h2>
                <a href="post_detail.php?id=<?php echo htmlspecialchars($post['id']); ?>">
                    <?php echo htmlspecialchars($post['title']); ?>
                </a>
            </h2>
            <p><?php echo htmlspecialchars(substr($post['content'], 0, 100)) . '...'; ?></p>
            <div class="post-meta">
                Author: <?php echo htmlspecialchars($post['author']); ?><br>
                Created at: <?php echo htmlspecialchars($post['created_at']); ?><br>
                Views: <?php echo htmlspecialchars($post['views']); ?><br>
                Status: <?php echo $post['published'] ? 'Published' : 'Draft'; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>