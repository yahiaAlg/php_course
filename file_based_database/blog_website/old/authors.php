<?php
$current_page = 'authors';
require_once 'classes/FileBlog.php';
$blog = new FileBlog();
$authors = $blog->getAllAuthors();
include 'includes/header.php';
?>

<h1>All Authors</h1>
<div class="authors-container">
    <?php foreach ($authors as $author): ?>
        <div class="author-card">
            <h2>
                <a href="author_detail.php?id=<?php echo htmlspecialchars($author['id']); ?>">
                    <?php echo htmlspecialchars($author['name']); ?>
                </a>
            </h2>
            <p>Email: <?php echo htmlspecialchars($author['email']); ?></p>
            <div class="author-meta">
                ID: <?php echo htmlspecialchars($author['id']); ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>