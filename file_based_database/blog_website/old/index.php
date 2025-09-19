<?php
require_once 'classes/FileBlog.php';
$blog = new FileBlog();

// Create some sample data if it doesn't exist
$authors = $blog->getAllAuthors();
if (empty($authors)) {
    $blog->createAuthor("John Doe", "john.doe@example.com");
    $blog->createAuthor("Jane Smith", "jane.smith@example.com");
    $blog->createAuthor("Alice Johnson", "alice.johnson@example.com");
    $blog->createAuthor("Bob Brown", "bob.brown@example.com");
}

$posts = $blog->getAllPosts();
if (empty($posts)) {
    $blog->createPost(
        "Welcome to My Blog",
        "This is my first post! Welcome to my blog where I share my thoughts and experiences.",
        "John Doe",
        "https://picsum.photos/800/400?random=1",
        [
            "https://picsum.photos/400/300?random=1",
            "https://picsum.photos/400/300?random=2",
            "https://picsum.photos/400/300?random=3"
        ]
    );
    $blog->createPost(
        "PHP Tips",
        "Here are some PHP tips that will help you become a better developer.",
        "John Doe",
        "https://picsum.photos/800/400?random=2",
        [
            "https://picsum.photos/400/300?random=4",
            "https://picsum.photos/400/300?random=5",
            "https://picsum.photos/400/300?random=6"
        ]
    );
    $blog->createPost(
        "Database Tutorial",
        "Databases are important for storing and retrieving data efficiently.",
        "Jane Smith",
        "https://picsum.photos/800/400?random=3",
        [
            "https://picsum.photos/400/300?random=7",
            "https://picsum.photos/400/300?random=8",
            "https://picsum.photos/400/300?random=9"
        ]
    );
    $blog->createPost(
        "Web Development Trends",
        "Stay updated with the latest trends in web development.",
        "Alice Johnson",
        "https://picsum.photos/800/400?random=4",
        [
            "https://picsum.photos/400/300?random=10",
            "https://picsum.photos/400/300?random=11",
            "https://picsum.photos/400/300?random=12"
        ]
    );
    $blog->createPost(
        "JavaScript Basics",
        "Learn the basics of JavaScript to enhance your web development skills.",
        "Bob Brown",
        "https://picsum.photos/800/400?random=5",
        [
            "https://picsum.photos/400/300?random=13",
            "https://picsum.photos/400/300?random=14",
            "https://picsum.photos/400/300?random=15"
        ]
    );
}

// Redirect to posts page
header("Location: posts.php");
exit;
