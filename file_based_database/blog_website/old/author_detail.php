<?php
require_once 'classes/FileBlog.php';
$blog = new FileBlog();
$author = $blog->getAuthor($_GET['id']);
if (!$author) {
    die("Author not found.");
}
include 'includes/header.php';
?>

<h1><?php echo htmlspecialchars($author['name']); ?></h1>
<p>Email: <?php echo htmlspecialchars($author['email']); ?></p>
<div class="author-meta">
    ID: <?php echo htmlspecialchars($author['id']); ?>
</div>
<a href="authors.php">Back to Authors</a>

<?php include 'includes/footer.php'; ?>