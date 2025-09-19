<?php
require_once 'classes/FileBlog.php';
$blog = new FileBlog();
$post = $blog->getPost($_GET['id']);
if (!$post) {
    die("Post not found.");
}
include 'includes/header.php';
?>

<h1><?php echo htmlspecialchars($post['title']); ?></h1>
<img src="<?php echo htmlspecialchars($post['main_image']); ?>" alt="Main Image" class="post-image">
<p><?php echo htmlspecialchars($post['content']); ?></p>
<div class="post-meta">
    Author: <?php echo htmlspecialchars($post['author']); ?><br>
    Created at: <?php echo htmlspecialchars($post['created_at']); ?><br>
    Views: <?php echo htmlspecialchars($post['views']); ?><br>
    Status: <?php echo $post['published'] ? 'Published' : 'Draft'; ?>
</div>
<div class="secondary-images">
    <?php foreach ($post['secondary_images'] as $image): ?>
        <img src="<?php echo htmlspecialchars($image); ?>" alt="Secondary Image">
    <?php endforeach; ?>
</div>
<a href="posts.php">Back to Posts</a>

<?php include 'includes/footer.php'; ?>