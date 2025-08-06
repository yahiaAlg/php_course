# From Files to Databases: Why and When to Make the Jump

## Table of Contents
1. [The Problem with Files](#the-problem-with-files)
2. [What is a Database?](#what-is-a-database)
3. [File vs Database Comparison](#file-vs-database-comparison)
4. [When Files Are Still OK](#when-files-are-still-ok)
5. [When You NEED a Database](#when-you-need-a-database)
6. [Types of Databases](#types-of-databases)
7. [Getting Started with Databases](#getting-started-with-databases)
8. [Migration Examples](#migration-examples)
9. [Best Practices](#best-practices)

---

## The Problem with Files

Let's start with a real scenario. Imagine you're building a simple blog using files to store posts:

### The "Simple" File-Based Blog

```php
<?php
// blog_post.php - Our "simple" file-based blog

class FileBlog {
    private $postsDir = 'posts/';
    
    public function __construct() {
        if (!is_dir($this->postsDir)) {
            mkdir($this->postsDir, 0777, true);
        }
    }
    
    public function createPost($title, $content, $author) {
        $post = [
            'id' => uniqid(),
            'title' => $title,
            'content' => $content,
            'author' => $author,
            'created_at' => date('Y-m-d H:i:s'),
            'views' => 0,
            'published' => true
        ];
        
        $filename = $this->postsDir . $post['id'] . '.json';
        file_put_contents($filename, json_encode($post, JSON_PRETTY_PRINT));
        
        return $post['id'];
    }
    
    public function getPost($id) {
        $filename = $this->postsDir . $id . '.json';
        if (!file_exists($filename)) {
            return null;
        }
        
        return json_decode(file_get_contents($filename), true);
    }
    
    public function getAllPosts() {
        $posts = [];
        $files = glob($this->postsDir . '*.json');
        
        foreach ($files as $file) {
            $post = json_decode(file_get_contents($file), true);
            if ($post) {
                $posts[] = $post;
            }
        }
        
        return $posts;
    }
    
    public function updatePost($id, $data) {
        $filename = $this->postsDir . $id . '.json';
        if (!file_exists($filename)) {
            return false;
        }
        
        $post = json_decode(file_get_contents($filename), true);
        $post = array_merge($post, $data);
        $post['updated_at'] = date('Y-m-d H:i:s');
        
        file_put_contents($filename, json_encode($post, JSON_PRETTY_PRINT));
        return true;
    }
}

// Usage
$blog = new FileBlog();

// Create some posts
$blog->createPost("Welcome to My Blog", "This is my first post!", "John");
$blog->createPost("PHP Tips", "Here are some PHP tips...", "John");
$blog->createPost("Database Tutorial", "Databases are important...", "Jane");

// Display all posts
$posts = $blog->getAllPosts();
foreach ($posts as $post) {
    echo "<h2>{$post['title']}</h2>";
    echo "<p>By: {$post['author']} on {$post['created_at']}</p>";
    echo "<p>{$post['content']}</p><hr>";
}
?>
```

### What Happens When Your Blog Grows?

Let's see what problems emerge as our blog becomes popular:

```php
<?php
// Now we need more features... problems start to appear

class FileBlogWithProblems {
    private $postsDir = 'posts/';
    private $commentsDir = 'comments/';
    private $usersDir = 'users/';
    
    // Problem 1: Finding posts by author becomes SLOW
    public function getPostsByAuthor($author) {
        $posts = [];
        $files = glob($this->postsDir . '*.json'); // Must read ALL files
        
        foreach ($files as $file) {
            $post = json_decode(file_get_contents($file), true);
            if ($post && $post['author'] === $author) {
                $posts[] = $post;
            }
        }
        
        return $posts; // What if you have 10,000 posts? This is SLOW!
    }
    
    // Problem 2: Counting posts requires reading everything
    public function getPostCount() {
        return count(glob($this->postsDir . '*.json')); // Still slow
    }
    
    // Problem 3: Getting recent posts requires loading and sorting ALL posts
    public function getRecentPosts($limit = 5) {
        $posts = $this->getAllPosts(); // Load everything into memory!
        
        // Sort by date
        usort($posts, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return array_slice($posts, 0, $limit);
    }
    
    // Problem 4: Search is nightmare
    public function searchPosts($keyword) {
        $results = [];
        $files = glob($this->postsDir . '*.json');
        
        foreach ($files as $file) {
            $post = json_decode(file_get_contents($file), true);
            if ($post && (
                stripos($post['title'], $keyword) !== false ||
                stripos($post['content'], $keyword) !== false
            )) {
                $results[] = $post;
            }
        }
        
        return $results; // Gets slower with every new post
    }
    
    // Problem 5: Concurrent access issues
    public function incrementViews($postId) {
        $filename = $this->postsDir . $postId . '.json';
        
        // What if two people view the post at the same time?
        // Both read the same view count, both increment by 1,
        // but only one increment is saved!
        
        $post = json_decode(file_get_contents($filename), true);
        $post['views']++;
        file_put_contents($filename, json_encode($post, JSON_PRETTY_PRINT));
    }
    
    // Problem 6: No relationships between data
    public function getPostsWithComments() {
        $posts = $this->getAllPosts();
        
        foreach ($posts as &$post) {
            // For each post, we need to find related comments
            $commentFiles = glob($this->commentsDir . 'post_' . $post['id'] . '_*.json');
            $comments = [];
            
            foreach ($commentFiles as $commentFile) {
                $comment = json_decode(file_get_contents($commentFile), true);
                if ($comment) {
                    $comments[] = $comment;
                }
            }
            
            $post['comments'] = $comments;
        }
        
        return $posts; // This gets exponentially slower!
    }
}

// Demonstration of problems
$blog = new FileBlogWithProblems();

// These operations become painfully slow as data grows:
$authorPosts = $blog->getPostsByAuthor("John"); // Reads every file
$recentPosts = $blog->getRecentPosts(5); // Loads everything to get 5 posts
$searchResults = $blog->searchPosts("PHP"); // No indexing = slow
$postsWithComments = $blog->getPostsWithComments(); // Multiply the problems
?>
```

### The Breaking Point

```php
<?php
// Real-world scenario: What happens with growth

// With 1,000 posts:
// - getAllPosts() takes 0.5 seconds
// - Search takes 0.3 seconds
// - Getting recent posts takes 0.5 seconds

// With 10,000 posts:
// - getAllPosts() takes 5 seconds
// - Search takes 3 seconds  
// - Getting recent posts takes 5 seconds
// - Your website becomes unusable!

// With 100,000 posts:
// - Your server runs out of memory
// - Operations time out
// - Users leave your website

// Multiple users accessing at once:
// - File locking issues
// - Corrupted data
// - Lost updates
// - Frustrated users

echo "File-based storage works for small data, but doesn't scale!";
?>
```

---

## What is a Database?

Think of a database like a super-smart filing cabinet that:
- **Organizes** your data efficiently
- **Finds** what you need instantly
- **Handles** multiple people accessing it at once
- **Protects** your data from corruption
- **Ensures** data consistency

### Database vs Filing Cabinet Analogy

```
Filing Cabinet (Files):
├── drawer1/
│   ├── document1.pdf
│   ├── document2.pdf
│   └── document3.pdf
└── drawer2/
    ├── report1.doc
    └── report2.doc

Finding something = Opening every drawer, checking every document

Database:
├── Index Cards (Indexes)
│   ├── "Author: John" → Points to exact location
│   ├── "Date: 2024" → Points to exact location
│   └── "Topic: PHP" → Points to exact location
└── Organized Storage
    └── Data stored efficiently with relationships

Finding something = Look at index card, go directly to location
```

### Key Database Concepts

```php
<?php
// Instead of this file structure:
/*
posts/
├── post_1.json
├── post_2.json
└── post_3.json

comments/
├── comment_1.json
├── comment_2.json
└── comment_3.json

users/
├── user_1.json
└── user_2.json
*/

// Databases organize data like this:
/*
DATABASE: blog_db
├── TABLE: posts
│   ├── COLUMNS: id, title, content, author_id, created_at
│   ├── INDEXES: author_id, created_at
│   └── RELATIONSHIPS: author_id links to users.id
├── TABLE: comments  
│   ├── COLUMNS: id, post_id, user_id, content, created_at
│   ├── INDEXES: post_id, user_id
│   └── RELATIONSHIPS: post_id links to posts.id
└── TABLE: users
    ├── COLUMNS: id, username, email, created_at
    └── INDEXES: username, email (unique)
*/

// The magic: Database can instantly find:
// - All posts by a specific author (using author_id index)
// - All comments for a post (using post_id index)
// - Recent posts (using created_at index)
// - Search results (using full-text indexes)
?>
```

---

## File vs Database Comparison

### Performance Comparison

```php
<?php
// Let's compare real scenarios

// Scenario 1: Finding posts by author
echo "=== Finding Posts by Author ===\n";

// FILE APPROACH:
$start = microtime(true);
// Must read and parse every single post file
$files = glob('posts/*.json'); // 10,000 files
foreach ($files as $file) {
    $post = json_decode(file_get_contents($file), true);
    if ($post['author'] === 'John') {
        // Found one!
    }
}
$fileTime = microtime(true) - $start;
echo "Files: {$fileTime} seconds (reads 10,000 files)\n";

// DATABASE APPROACH:
$start = microtime(true);
// SELECT * FROM posts WHERE author = 'John'
// Database uses index, finds instantly
$dbTime = 0.001; // Typically under 1ms
echo "Database: {$dbTime} seconds (uses index)\n\n";

// Scenario 2: Getting recent posts
echo "=== Getting 10 Most Recent Posts ===\n";

// FILE APPROACH:
// 1. Read ALL files (10,000 files)
// 2. Load ALL into memory 
// 3. Sort ALL by date
// 4. Take first 10
echo "Files: Must load everything, then sort\n";
echo "Memory usage: ~100MB for 10,000 posts\n";
echo "Time: ~5 seconds\n\n";

// DATABASE APPROACH:
// SELECT * FROM posts ORDER BY created_at DESC LIMIT 10
echo "Database: Direct query with limit\n";
echo "Memory usage: ~5KB for 10 posts\n"; 
echo "Time: ~0.001 seconds\n\n";

// Scenario 3: Search functionality
echo "=== Searching for 'PHP' ===\n";

// FILE APPROACH:
echo "Files: Read every file, search content\n";
echo "Time grows with data size\n";
echo "No ranking or relevance\n\n";

// DATABASE APPROACH:
echo "Database: Full-text search with ranking\n";
echo "Time stays constant\n";
echo "Relevance scoring included\n";
?>
```

### Feature Comparison Table

| Feature | Files | Database |
|---------|-------|----------|
| **Storage** | Individual files | Structured tables |
| **Speed (small data)** | ✅ Fast | ✅ Fast |
| **Speed (large data)** | ❌ Very slow | ✅ Still fast |
| **Memory usage** | ❌ Loads everything | ✅ Loads only needed |
| **Concurrent access** | ❌ Problems | ✅ Handles well |
| **Data integrity** | ❌ Can corrupt | ✅ Protected |
| **Searching** | ❌ Very slow | ✅ Indexed & fast |
| **Relationships** | ❌ Manual work | ✅ Built-in |
| **Backup** | ❌ Complex | ✅ Simple |
| **Scaling** | ❌ Doesn't scale | ✅ Scales well |

---

## When Files Are Still OK

Don't think databases are always better! Files are perfect for:

### 1. Configuration Files
```php
<?php
// config.json - Perfect for files!
{
    "app_name": "My Blog",
    "debug": false,
    "max_upload_size": "10MB",
    "allowed_extensions": ["jpg", "png", "gif"]
}

// Why files are better here:
// - Small amount of data
// - Rarely changes
// - Easy to edit manually
// - No complex queries needed
?>
```

### 2. Logs and Cache
```php
<?php
// error.log - Perfect for files!
[2024-01-15 10:30:15] ERROR: User login failed for user@email.com
[2024-01-15 10:31:22] INFO: New user registered: newuser@email.com
[2024-01-15 10:32:45] WARNING: High memory usage detected

// cache/user_123.json - Good for temporary data
{
    "user_id": 123,
    "username": "john",
    "cached_at": "2024-01-15 10:30:00",
    "expires_at": "2024-01-15 11:30:00"
}

// Why files work here:
// - Temporary data
// - Simple read/write
// - No relationships
// - Performance not critical
?>
```

### 3. Small, Simple Applications
```php
<?php
// Simple contact form submissions
class ContactForm {
    public function saveMessage($name, $email, $message) {
        $data = [
            'name' => $name,
            'email' => $email,
            'message' => $message,
            'submitted_at' => date('Y-m-d H:i:s')
        ];
        
        // Append to CSV file
        $file = fopen('messages.csv', 'a');
        fputcsv($file, array_values($data));
        fclose($file);
    }
}

// This is fine because:
// - Few submissions per day
// - No complex queries needed
// - Easy to export to spreadsheet
// - Simple backup (copy file)
?>
```

### 4. Static Data
```php
<?php
// countries.json - Perfect for files!
[
    {"code": "US", "name": "United States"},
    {"code": "CA", "name": "Canada"},
    {"code": "UK", "name": "United Kingdom"}
]

// Why files work:
// - Data never changes
// - Small dataset
// - No relationships
// - Easy to maintain
?>
```

---

## When You NEED a Database

### 1. Growing Data Volume

```php
<?php
// The tipping point - when files become painful

// Small blog: 50 posts
// - Files work fine
// - getAllPosts() takes 0.01 seconds

// Medium blog: 1,000 posts  
// - Files getting slow
// - getAllPosts() takes 0.5 seconds
// - Search takes 0.3 seconds

// Large blog: 10,000+ posts
// - Files are unusable
// - getAllPosts() takes 5+ seconds
// - Search takes 3+ seconds
// - Users leave your site

echo "Rule of thumb: If you have more than 1,000 records, consider a database";
?>
```

### 2. Complex Queries

```php
<?php
// Things that are nightmare with files, easy with databases

// Find all posts by authors who joined in 2024
// FILE APPROACH: 
// 1. Read all user files
// 2. Filter users by join date
// 3. Read all post files  
// 4. Match posts to filtered users
// = Thousands of file reads

// DATABASE APPROACH:
// SELECT p.* FROM posts p 
// JOIN users u ON p.author_id = u.id 
// WHERE u.created_at >= '2024-01-01'
// = One fast query

// Find posts with more than 10 comments by active users
// FILE APPROACH: Nightmare
// DATABASE APPROACH: One query

echo "If you need to combine data from multiple sources, you need a database";
?>
```

### 3. Multiple Users

```php
<?php
// Concurrent access problems with files

class FileConcurrencyProblem {
    public function updatePostViews($postId) {
        $filename = "posts/{$postId}.json";
        
        // THE PROBLEM:
        // User A reads: {"views": 100}
        // User B reads: {"views": 100} (same time)
        // User A writes: {"views": 101}
        // User B writes: {"views": 101}
        // Result: Should be 102, but it's 101! Lost update!
        
        $post = json_decode(file_get_contents($filename), true);
        $post['views']++;
        file_put_contents($filename, json_encode($post));
    }
}

// DATABASE SOLUTION:
// UPDATE posts SET views = views + 1 WHERE id = ?
// Database handles concurrency automatically!

echo "If multiple people use your app at once, you need a database";
?>
```

### 4. Data Relationships

```php
<?php
// When your data connects together

// E-commerce example:
// - Users place Orders
// - Orders contain Products  
// - Products belong to Categories
// - Users write Reviews for Products

// FILE APPROACH - Nightmare to manage:
/*
users/user_123.json
orders/order_456.json -> contains user_id: 123
products/product_789.json
order_items/order_456_product_789.json
categories/category_10.json -> product references this
reviews/review_999.json -> user and product references
*/

// Finding "all orders by user John with products from Electronics category"
// = Reading hundreds of files and manually connecting them

// DATABASE APPROACH:
/*
SELECT o.*, p.name as product_name 
FROM orders o
JOIN users u ON o.user_id = u.id  
JOIN order_items oi ON o.id = oi.order_id
JOIN products p ON oi.product_id = p.id
JOIN categories c ON p.category_id = c.id
WHERE u.name = 'John' AND c.name = 'Electronics'
*/

echo "If your data connects together, you need a database";
?>
```

### 5. Data Integrity Requirements

```php
<?php
// When data consistency matters

// Banking example - transferring money
class FileBankingProblem {
    public function transferMoney($fromAccount, $toAccount, $amount) {
        // THE PROBLEM: What if system crashes between these steps?
        
        // Step 1: Reduce from account
        $from = json_decode(file_get_contents("accounts/{$fromAccount}.json"), true);
        $from['balance'] -= $amount;
        file_put_contents("accounts/{$fromAccount}.json", json_encode($from));
        
        // CRASH HERE = Money disappears!
        
        // Step 2: Add to account
        $to = json_decode(file_get_contents("accounts/{$toAccount}.json"), true);  
        $to['balance'] += $amount;
        file_put_contents("accounts/{$toAccount}.json", json_encode($to));
    }
}

// DATABASE SOLUTION - Transactions:
/*
BEGIN TRANSACTION;
UPDATE accounts SET balance = balance - 100 WHERE id = 'from';
UPDATE accounts SET balance = balance + 100 WHERE id = 'to';
COMMIT; -- Either both succeed or both fail
*/

echo "If data consistency is critical, you need a database";
?>
```

---

## Types of Databases

### 1. SQL Databases (Relational)

Perfect for structured data with relationships:

```php
<?php
// SQL Database Example - MySQL, PostgreSQL, SQLite

// Structure:
/*
TABLE users:
+----+----------+------------------+
| id | username | email            |
+----+----------+------------------+
| 1  | john     | john@email.com   |
| 2  | jane     | jane@email.com   |
+----+----------+------------------+

TABLE posts:
+----+-----------+----------+-----------+
| id | title     | content  | author_id |
+----+-----------+----------+-----------+
| 1  | PHP Tips  | Content  | 1         |
| 2  | DB Guide  | Content  | 2         |
+----+-----------+----------+-----------+
*/

// Benefits:
// - Strong consistency
// - ACID transactions
// - Complex queries with JOINs
// - Mature ecosystem

// Best for:
// - Business applications
// - Financial systems  
// - E-commerce
// - CRM systems

echo "SQL databases: Best for most web applications";
?>
```

### 2. NoSQL Databases

Good for flexible, document-based data:

```php
<?php
// NoSQL Database Example - MongoDB

// Structure (similar to JSON):
/*
users collection:
{
  "_id": "user1",
  "username": "john",
  "profile": {
    "email": "john@email.com",
    "preferences": {
      "theme": "dark",
      "notifications": true
    },
    "social_links": [
      {"platform": "twitter", "url": "..."},
      {"platform": "github", "url": "..."}
    ]
  }
}
*/

// Benefits:
// - Flexible schema
// - JSON-like documents
// - Easy to scale horizontally
// - Good for rapid development

// Best for:
// - Content management
// - Real-time applications
// - IoT data
// - Catalogs with varying attributes

echo "NoSQL databases: Good for flexible, evolving data structures";
?>
```

### 3. SQLite (Great for Beginners!)

```php
<?php
// SQLite - File-based SQL database
// Perfect stepping stone from files to full databases

// Benefits:
// - No server setup required
// - Single file database
// - Full SQL support
// - Built into PHP

// Create SQLite database:
$db = new PDO('sqlite:blog.db');

$db->exec('CREATE TABLE IF NOT EXISTS posts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    content TEXT NOT NULL,
    author TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)');

// Use it like any database:
$stmt = $db->prepare('INSERT INTO posts (title, content, author) VALUES (?, ?, ?)');
$stmt->execute(['My First Post', 'Hello World!', 'John']);

$stmt = $db->prepare('SELECT * FROM posts WHERE author = ?');
$stmt->execute(['John']);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "SQLite: Perfect for learning databases!";
?>
```

---

## Getting Started with Databases

### Step 1: Install SQLite (Easiest Start)

SQLite is perfect for beginners because:
- No server setup needed
- Single file database
- Built into PHP
- Full SQL functionality

```php
<?php
// Create your first database
$database = new PDO('sqlite:my_first_database.db');

// Create a table
$database->exec('
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
');

echo "Database created successfully!";
?>
```

### Step 2: Basic CRUD Operations

```php
<?php
class SimpleUserManager {
    private $db;
    
    public function __construct() {
        $this->db = new PDO('sqlite:users.db');
        $this->createTable();
    }
    
    private function createTable() {
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT UNIQUE NOT NULL,
                age INTEGER,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ');
    }
    
    // CREATE - Add new user
    public function addUser($name, $email, $age) {
        $stmt = $this->db->prepare('
            INSERT INTO users (name, email, age) 
            VALUES (?, ?, ?)
        ');
        
        return $stmt->execute([$name, $email, $age]);
    }
    
    // READ - Get user by ID
    public function getUser($id) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // READ - Get all users
    public function getAllUsers() {
        $stmt = $this->db->query('SELECT * FROM users ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // UPDATE - Modify user
    public function updateUser($id, $name, $email, $age) {
        $stmt = $this->db->prepare('
            UPDATE users 
            SET name = ?, email = ?, age = ? 
            WHERE id = ?
        ');
        
        return $stmt->execute([$name, $email, $age, $id]);
    }
    
    // DELETE - Remove user
    public function deleteUser($id) {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = ?');
        return $stmt->execute([$id]);
    }
    
    // SEARCH - Find users by name
    public function searchUsers($name) {
        $stmt = $this->db->prepare('
            SELECT * FROM users 
            WHERE name LIKE ? 
            ORDER BY name
        ');
        
        $stmt->execute(['%' . $name . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Usage example
$userManager = new SimpleUserManager();

// Add users
$userManager->addUser('John Doe', 'john@email.com', 25);
$userManager->addUser('Jane Smith', 'jane@email.com', 30);
$userManager->addUser('Bob Johnson', 'bob@email.com', 35);

// Get all users
$users = $userManager->getAllUsers();
foreach ($users as $user) {
    echo "{$user['name']} ({$user['email']}) - Age: {$user['age']}\n";
}

// Search for users
$results = $userManager->searchUsers('John');
echo "Search results: " . count($results) . " users found\n";
?>
```

### Step 3: Understanding SQL Basics

```php
<?php
// SQL is like asking questions in a structured way

// Instead of: "Show me all posts"
// SQL: SELECT * FROM posts;

// Instead of: "Show me posts by John"  
// SQL: SELECT * FROM posts WHERE author = 'John';

// Instead of: "Show me recent posts, newest first"
// SQL: SELECT * FROM posts ORDER BY created_at DESC LIMIT 10;

// Instead of: "Count how many posts each author has"
// SQL: SELECT author, COUNT(*) as post_count 
//      FROM posts 
//      GROUP BY author;

// Basic SQL patterns:
$examples = [
    'Get everything' => 'SELECT * FROM table_name',
    'Get specific columns' => 'SELECT name, email FROM users',  
    'Filter results' => 'SELECT * FROM posts WHERE published = 1',
    'Sort results' => 'SELECT * FROM posts ORDER BY created_at DESC',
    'Limit results' => 'SELECT * FROM posts LIMIT 5',
    'Count records' => 'SELECT COUNT(*) FROM posts',
    'Insert data' => 'INSERT INTO users (name, email) VALUES (?, ?)',
    'Update data' => 'UPDATE users SET email = ? WHERE id = ?',
    'Delete data' => 'DELETE FROM posts WHERE id = ?'
];

foreach ($examples as $description => $sql) {
    echo "$description: $sql\n";
}
?>
```

---

## Migration Examples

### Example 1: Converting File-Based Blog to Database

```php
<?php
// Step 1: Create database structure
class BlogMigration {
    private $db;
    
    public function __construct() {
        $this->db = new PDO('sqlite:blog.db');
        $this->createTables();
    }
    
    private function createTables() {
        // Posts table
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS posts (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                content TEXT NOT NULL,
                author TEXT NOT NULL,
                created_at DATETIME NOT NULL,
                views INTEGER DEFAULT 0,
                published BOOLEAN DEFAULT 1
            )
        ');
        
        // Comments table (new functionality!)
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS comments (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                post_id INTEGER NOT NULL,
                author_name TEXT NOT NULL,
                author_email TEXT NOT NULL,
                content TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (post_id) REFERENCES posts(id)
            )
        ');
        
        echo "Database tables created!\n";
    }
    
    // Step 2: Import existing JSON files
    public function importFromFiles($postsDirectory = 'posts/') {
        $files = glob($postsDirectory . '*.json');
        $imported = 0;
        
        foreach ($files as $file) {
            $postData = json_decode(file_get_contents($file), true);
            
            if ($postData) {
                $stmt = $this->db->prepare('
                    INSERT INTO posts (title, content, author, created_at, views, published)
                    VALUES (?, ?, ?, ?, ?, ?)
                ');
                
                $stmt->execute([
                    $postData['title'],
                    $postData['content'], 
                    $postData['author'],
                    $postData['created_at'],
                    $postData['views'] ?? 0,
                    $postData['published'] ?? 1
                ]);
                
                $imported++;
            }
        }
        
        echo "Imported $imported posts from files to database!\n";
    }
    
    // Step 3: New database-powered blog class
    public function createNewBlogClass() {
        return new DatabaseBlog($this->db);
    }
}

class DatabaseBlog {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    // Same interface, but MUCH faster!
    public function getPostsByAuthor($author) {
        $stmt = $this->db->prepare('
            SELECT * FROM posts 
            WHERE author = ? 
            ORDER BY created_at DESC
        ');
        $stmt->execute([$author]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getRecentPosts($limit = 5) {
        $stmt = $this->db->prepare('
            SELECT * FROM posts 
            WHERE published = 1 
            ORDER BY created_at DESC 
            LIMIT ?
        ');
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function searchPosts($keyword) {
        $stmt = $this->db->prepare('
            SELECT * FROM posts 
            WHERE (title LIKE ? OR content LIKE ?) 
            AND published = 1
            ORDER BY created_at DESC
        ');
        $searchTerm = '%' . $keyword . '%';
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function incrementViews($postId) {
        // No concurrency issues!
        $stmt = $this->db->prepare('
            UPDATE posts 
            SET views = views + 1 
            WHERE id = ?
        ');
        return $stmt->execute([$postId]);
    }
    
    // New functionality that was hard with files
    public function getPostsWithCommentCount() {
        $stmt = $this->db->query('
            SELECT p.*, COUNT(c.id) as comment_count
            FROM posts p
            LEFT JOIN comments c ON p.id = c.post_id
            WHERE p.published = 1
            GROUP BY p.id
            ORDER BY p.created_at DESC
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getPopularPosts($limit = 10) {
        $stmt = $this->db->prepare('
            SELECT * FROM posts 
            WHERE published = 1 
            ORDER BY views DESC 
            LIMIT ?
        ');
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function addComment($postId, $authorName, $authorEmail, $content) {
        $stmt = $this->db->prepare('
            INSERT INTO comments (post_id, author_name, author_email, content)
            VALUES (?, ?, ?, ?)
        ');
        return $stmt->execute([$postId, $authorName, $authorEmail, $content]);
    }
}

// Usage: Migrate from files to database
$migration = new BlogMigration();
$migration->importFromFiles('old_posts/');
$blog = $migration->createNewBlogClass();

// Now everything is FAST!
$recentPosts = $blog->getRecentPosts(5); // Instant!
$searchResults = $blog->searchPosts('PHP'); // Instant!
$popularPosts = $blog->getPopularPosts(10); // Instant!
?>
```

### Example 2: User Management System Migration

```php
<?php
class UserSystemMigration {
    private $db;
    
    public function __construct() {
        $this->db = new PDO('sqlite:users.db');
        $this->createUserTables();
    }
    
    private function createUserTables() {
        // Users table
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE NOT NULL,
                email TEXT UNIQUE NOT NULL,
                password_hash TEXT NOT NULL,
                first_name TEXT,
                last_name TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                last_login DATETIME,
                is_active BOOLEAN DEFAULT 1
            )
        ');
        
        // User profiles table
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS user_profiles (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                bio TEXT,
                website TEXT,
                location TEXT,
                birth_date DATE,
                avatar_url TEXT,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ');
        
        // User sessions table (better than file-based sessions)
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS user_sessions (
                id TEXT PRIMARY KEY,
                user_id INTEGER NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                expires_at DATETIME NOT NULL,
                ip_address TEXT,
                user_agent TEXT,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ');
        
        echo "User system tables created!\n";
    }
    
    // Import users from JSON files
    public function importUsersFromFiles($usersDirectory = 'users/') {
        $files = glob($usersDirectory . '*.json');
        $imported = 0;
        
        foreach ($files as $file) {
            $userData = json_decode(file_get_contents($file), true);
            
            if ($userData) {
                try {
                    // Insert user
                    $stmt = $this->db->prepare('
                        INSERT INTO users (username, email, password_hash, first_name, last_name, created_at)
                        VALUES (?, ?, ?, ?, ?, ?)
                    ');
                    
                    $stmt->execute([
                        $userData['username'],
                        $userData['email'],
                        $userData['password_hash'],
                        $userData['first_name'] ?? '',
                        $userData['last_name'] ?? '',
                        $userData['created_at'] ?? date('Y-m-d H:i:s')
                    ]);
                    
                    $userId = $this->db->lastInsertId();
                    
                    // Insert profile if exists
                    if (isset($userData['profile'])) {
                        $profileStmt = $this->db->prepare('
                            INSERT INTO user_profiles (user_id, bio, website, location, birth_date)
                            VALUES (?, ?, ?, ?, ?)
                        ');
                        
                        $profileStmt->execute([
                            $userId,
                            $userData['profile']['bio'] ?? null,
                            $userData['profile']['website'] ?? null,
                            $userData['profile']['location'] ?? null,
                            $userData['profile']['birth_date'] ?? null
                        ]);
                    }
                    
                    $imported++;
                } catch (PDOException $e) {
                    echo "Error importing user from $file: " . $e->getMessage() . "\n";
                }
            }
        }
        
        echo "Imported $imported users from files to database!\n";
    }
}

// Advanced user management with database
class DatabaseUserManager {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    // Complex queries that were impossible with files
    public function getUsersWithProfiles($limit = 50) {
        $stmt = $this->db->prepare('
            SELECT 
                u.id, u.username, u.email, u.first_name, u.last_name,
                u.created_at, u.last_login,
                p.bio, p.website, p.location
            FROM users u
            LEFT JOIN user_profiles p ON u.id = p.user_id
            WHERE u.is_active = 1
            ORDER BY u.created_at DESC
            LIMIT ?
        ');
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function searchUsers($query) {
        $stmt = $this->db->prepare('
            SELECT u.*, p.bio, p.location
            FROM users u
            LEFT JOIN user_profiles p ON u.id = p.user_id
            WHERE u.is_active = 1 
            AND (
                u.username LIKE ? OR 
                u.first_name LIKE ? OR 
                u.last_name LIKE ? OR
                u.email LIKE ? OR
                p.bio LIKE ?
            )
            ORDER BY u.username
        ');
        $searchTerm = '%' . $query . '%';
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUserActivity($userId) {
        // Get user with session history
        $stmt = $this->db->prepare('
            SELECT 
                u.*,
                COUNT(s.id) as total_sessions,
                MAX(s.created_at) as last_session
            FROM users u
            LEFT JOIN user_sessions s ON u.id = s.user_id
            WHERE u.id = ?
            GROUP BY u.id
        ');
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getActiveUsers($hoursBack = 24) {
        $stmt = $this->db->prepare('
            SELECT DISTINCT u.*
            FROM users u
            JOIN user_sessions s ON u.id = s.user_id
            WHERE s.created_at >= datetime("now", "-' . $hoursBack . ' hours")
            ORDER BY s.created_at DESC
        ');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
```

### Example 3: E-commerce Product Catalog Migration

```php
<?php
class EcommerceMigration {
    private $db;
    
    public function __construct() {
        $this->db = new PDO('sqlite:shop.db');
        $this->createShopTables();
    }
    
    private function createShopTables() {
        // Categories table
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS categories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                slug TEXT UNIQUE NOT NULL,
                description TEXT,
                parent_id INTEGER,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (parent_id) REFERENCES categories(id)
            )
        ');
        
        // Products table  
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS products (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                slug TEXT UNIQUE NOT NULL,
                description TEXT,
                price DECIMAL(10,2) NOT NULL,
                cost DECIMAL(10,2),
                stock_quantity INTEGER DEFAULT 0,
                sku TEXT UNIQUE,
                is_active BOOLEAN DEFAULT 1,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ');
        
        // Product-Category relationships (many-to-many)
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS product_categories (
                product_id INTEGER NOT NULL,
                category_id INTEGER NOT NULL,
                PRIMARY KEY (product_id, category_id),
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
                FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
            )
        ');
        
        // Product attributes (flexible properties)
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS product_attributes (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                product_id INTEGER NOT NULL,
                attribute_name TEXT NOT NULL,
                attribute_value TEXT NOT NULL,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
            )
        ');
        
        echo "E-commerce tables created!\n";
    }
    
    public function importProductsFromFiles($productsDirectory = 'products/') {
        $files = glob($productsDirectory . '*.json');
        $imported = 0;
        
        foreach ($files as $file) {
            $productData = json_decode(file_get_contents($file), true);
            
            if ($productData) {
                try {
                    // Insert product
                    $stmt = $this->db->prepare('
                        INSERT INTO products (name, slug, description, price, stock_quantity, sku)
                        VALUES (?, ?, ?, ?, ?, ?)
                    ');
                    
                    $stmt->execute([
                        $productData['name'],
                        $productData['slug'] ?? strtolower(str_replace(' ', '-', $productData['name'])),
                        $productData['description'] ?? '',
                        $productData['price'],
                        $productData['stock'] ?? 0,
                        $productData['sku'] ?? null
                    ]);
                    
                    $productId = $this->db->lastInsertId();
                    
                    // Import categories
                    if (isset($productData['categories'])) {
                        foreach ($productData['categories'] as $categoryName) {
                            $categoryId = $this->getOrCreateCategory($categoryName);
                            
                            $catStmt = $this->db->prepare('
                                INSERT OR IGNORE INTO product_categories (product_id, category_id)
                                VALUES (?, ?)
                            ');
                            $catStmt->execute([$productId, $categoryId]);
                        }
                    }
                    
                    // Import custom attributes
                    if (isset($productData['attributes'])) {
                        foreach ($productData['attributes'] as $name => $value) {
                            $attrStmt = $this->db->prepare('
                                INSERT INTO product_attributes (product_id, attribute_name, attribute_value)
                                VALUES (?, ?, ?)
                            ');
                            $attrStmt->execute([$productId, $name, $value]);
                        }
                    }
                    
                    $imported++;
                } catch (PDOException $e) {
                    echo "Error importing product from $file: " . $e->getMessage() . "\n";
                }
            }
        }
        
        echo "Imported $imported products from files to database!\n";
    }
    
    private function getOrCreateCategory($categoryName) {
        // Check if category exists
        $stmt = $this->db->prepare('SELECT id FROM categories WHERE name = ?');
        $stmt->execute([$categoryName]);
        $category = $stmt->fetch();
        
        if ($category) {
            return $category['id'];
        }
        
        // Create new category
        $stmt = $this->db->prepare('
            INSERT INTO categories (name, slug) 
            VALUES (?, ?)
        ');
        $slug = strtolower(str_replace(' ', '-', $categoryName));
        $stmt->execute([$categoryName, $slug]);
        
        return $this->db->lastInsertId();
    }
}

class DatabaseProductCatalog {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    // Powerful queries impossible with files
    public function searchProducts($query, $categoryId = null, $minPrice = null, $maxPrice = null) {
        $sql = '
            SELECT DISTINCT p.*, GROUP_CONCAT(c.name) as categories
            FROM products p
            LEFT JOIN product_categories pc ON p.id = pc.product_id
            LEFT JOIN categories c ON pc.category_id = c.id
            WHERE p.is_active = 1
        ';
        
        $params = [];
        
        if ($query) {
            $sql .= ' AND (p.name LIKE ? OR p.description LIKE ?)';
            $params[] = '%' . $query . '%';
            $params[] = '%' . $query . '%';
        }
        
        if ($categoryId) {
            $sql .= ' AND pc.category_id = ?';
            $params[] = $categoryId;
        }
        
        if ($minPrice) {
            $sql .= ' AND p.price >= ?';
            $params[] = $minPrice;
        }
        
        if ($maxPrice) {
            $sql .= ' AND p.price <= ?';
            $params[] = $maxPrice;
        }
        
        $sql .= ' GROUP BY p.id ORDER BY p.name';
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getProductsWithAttributes($categoryId = null) {
        $sql = '
            SELECT 
                p.*,
                GROUP_CONCAT(DISTINCT c.name) as categories,
                GROUP_CONCAT(DISTINCT pa.attribute_name || ": " || pa.attribute_value) as attributes
            FROM products p
            LEFT JOIN product_categories pc ON p.id = pc.product_id
            LEFT JOIN categories c ON pc.category_id = c.id
            LEFT JOIN product_attributes pa ON p.id = pa.product_id
            WHERE p.is_active = 1
        ';
        
        $params = [];
        if ($categoryId) {
            $sql .= ' AND pc.category_id = ?';
            $params[] = $categoryId;
        }
        
        $sql .= ' GROUP BY p.id ORDER BY p.name';
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getLowStockProducts($threshold = 10) {
        $stmt = $this->db->prepare('
            SELECT * FROM products 
            WHERE stock_quantity <= ? 
            AND is_active = 1
            ORDER BY stock_quantity ASC
        ');
        $stmt->execute([$threshold]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getCategorySummary() {
        $stmt = $this->db->query('
            SELECT 
                c.name,
                COUNT(pc.product_id) as product_count,
                AVG(p.price) as avg_price,
                MIN(p.price) as min_price,
                MAX(p.price) as max_price
            FROM categories c
            LEFT JOIN product_categories pc ON c.id = pc.category_id
            LEFT JOIN products p ON pc.product_id = p.id AND p.is_active = 1
            GROUP BY c.id, c.name
            ORDER BY product_count DESC
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
```

---

## Best Practices

### 1. Security First

```php
<?php
// NEVER do this (SQL Injection vulnerability):
$userId = $_GET['user_id']; // Could be "1; DROP TABLE users;"
$sql = "SELECT * FROM users WHERE id = " . $userId;
$result = $db->query($sql); // DANGEROUS!

// ALWAYS do this (Prepared statements):
$userId = $_GET['user_id'];
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$result = $stmt->fetch();

// For dynamic queries, validate inputs:
$allowedColumns = ['name', 'email', 'created_at'];
$sortBy = $_GET['sort'] ?? 'name';

if (!in_array($sortBy, $allowedColumns)) {
    $sortBy = 'name'; // Safe default
}

$stmt = $db->prepare("SELECT * FROM users ORDER BY $sortBy");
?>
```

### 2. Error Handling

```php
<?php
class SafeDatabaseOperations {
    private $db;
    
    public function __construct() {
        try {
            $this->db = new PDO('sqlite:app.db');
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new Exception("Database connection failed");
        }
    }
    
    public function createUser($username, $email) {
        try {
            $stmt = $this->db->prepare('
                INSERT INTO users (username, email) 
                VALUES (?, ?)
            ');
            
            $stmt->execute([$username, $email]);
            return $this->db->lastInsertId();
            
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Integrity constraint violation
                throw new Exception("Username or email already exists");
            }
            
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to create user");
        }
    }
}
?>
```

### 3. Performance Optimization

```php
<?php
class OptimizedDatabase {
    private $db;
    
    public function __construct() {
        $this->db = new PDO('sqlite:app.db');
        $this->createIndexes();
    }
    
    private function createIndexes() {
        // Create indexes for frequently queried columns
        $indexes = [
            'CREATE INDEX IF NOT EXISTS idx_users_email ON users(email)',
            'CREATE INDEX IF NOT EXISTS idx_posts_author ON posts(author_id)',
            'CREATE INDEX IF NOT EXISTS idx_posts_created ON posts(created_at)',
            'CREATE INDEX IF NOT EXISTS idx_comments_post ON comments(post_id)',
        ];
        
        foreach ($indexes as $index) {
            $this->db->exec($index);
        }
    }
    
    // Use LIMIT to prevent loading too much data
    public function getRecentPosts($page = 1, $perPage = 20) {
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->db->prepare('
            SELECT * FROM posts 
            WHERE published = 1 
            ORDER BY created_at DESC 
            LIMIT ? OFFSET ?
        ');
        
        $stmt->execute([$perPage, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Use transactions for multiple related operations
    public function transferMoney($fromAccount, $toAccount, $amount) {
        try {
            $this->db->beginTransaction();
            
            // Deduct from source account
            $stmt = $this->db->prepare('
                UPDATE accounts 
                SET balance = balance - ? 
                WHERE id = ? AND balance >= ?
            ');
            $stmt->execute([$amount, $fromAccount, $amount]);
            
            if ($stmt->rowCount() === 0) {
                throw new Exception("Insufficient funds");
            }
            
            // Add to destination account
            $stmt = $this->db->prepare('
                UPDATE accounts 
                SET balance = balance + ? 
                WHERE id = ?
            ');
            $stmt->execute([$amount, $toAccount]);
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
}
?>
```

### 4. Database Design Tips

```php
<?php
// Good table design examples

// DO: Use meaningful names
/*
CREATE TABLE blog_posts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    content TEXT NOT NULL,
    author_id INTEGER NOT NULL,
    published_at DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
*/

// DON'T: Use unclear names
/*
CREATE TABLE tbl1 (
    id INTEGER,
    txt TEXT,
    dt DATETIME
);
*/

// DO: Normalize related data
/*
Table: users
+----+----------+
| id | username |
+----+----------+
| 1  | john     |
+----+----------+

Table: posts  
+----+---------+-----------+
| id | title   | author_id |
+----+---------+-----------+
| 1  | My Post | 1         |
+----+---------+-----------+
*/

// DON'T: Repeat data unnecessarily  
/*
Table: posts
+----+---------+----------+
| id | title   | username |
+----+---------+----------+
| 1  | My Post | john     |
| 2  | Another | john     |  <- Repeated data
+----+---------+----------+
*/

// DO: Use appropriate data types
$goodSchema = '
    CREATE TABLE products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,                    -- Text for names
        price DECIMAL(10,2) NOT NULL,          -- Decimal for money
        stock_quantity INTEGER DEFAULT 0,      -- Integer for counts
        is_active BOOLEAN DEFAULT 1,          -- Boolean for yes/no
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP -- Datetime for dates
    )
';

// DON'T: Use TEXT for everything
$badSchema = '
    CREATE TABLE products (
        id TEXT,           -- Should be INTEGER
        name TEXT,
        price TEXT,        -- Should be DECIMAL
        stock_quantity TEXT, -- Should be INTEGER
        is_active TEXT,    -- Should be BOOLEAN
        created_at TEXT    -- Should be DATETIME
    )
';
?>
```

### 5. Backup and Maintenance

```php
<?php
class DatabaseMaintenance {
    private $db;
    
    public function __construct() {
        $this->db = new PDO('sqlite:app.db');
    }
    
    public function backupDatabase($backupPath) {
        try {
            // For SQLite, just copy the file
            if (file_exists('app.db')) {
                copy('app.db', $backupPath . '/backup_' . date('Y-m-d_H-i-s') . '.db');
                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("Backup failed: " . $e->getMessage());
            return false;
        }
    }
    
    public function optimizeDatabase() {
        try {
            // Clean up deleted data
            $this->db->exec('VACUUM');
            
            // Update table statistics
            $this->db->exec('ANALYZE');
            
            return true;
        } catch (Exception $e) {
            error_log("Optimization failed: " . $e->getMessage());
            return false;
        }
    }
    
    public function getTableSizes() {
        $tables = ['users', 'posts', 'comments'];
        $sizes = [];
        
        foreach ($tables as $table) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM $table");
            $stmt->execute();
            $sizes[$table] = $stmt->fetchColumn();
        }
        
        return $sizes;
    }
}
?>
```

---

## Summary: Making the Right Choice

### Quick Decision Tree

```php
<?php
// Decision tree for choosing between files and databases

function chooseDataStorage($requirements) {
    // Small, simple data (< 100 records)
    if ($requirements['record_count'] < 100 && 
        $requirements['complexity'] === 'simple' &&
        $requirements['concurrent_users'] < 5) {
        return "Use files (JSON, CSV, or simple text)";
    }
    
    // Configuration or static data
    if ($requirements['type'] === 'config' || 
        $requirements['changes_frequency'] === 'rarely') {
        return "Use files (JSON or YAML)";
    }
    
    // Growing data or complex queries
    if ($requirements['record_count'] > 1000 ||
        $requirements['relationships'] === true ||
        $requirements['complex_queries'] === true) {
        return "Use database (start with SQLite)";
    }
    
    // Multiple users or data integrity critical
    if ($requirements['concurrent_users'] > 10 ||
        $requirements['data_integrity'] === 'critical') {
        return "Use database (PostgreSQL or MySQL)";
    }
    
    // Default recommendation
    return "Start with files, migrate to database when needed";
}

// Examples:
$blogRequirements = [
    'record_count' => 50,
    'complexity' => 'simple',
    'concurrent_users' => 2,
    'type' => 'content'
];
echo chooseDataStorage($blogRequirements); // Use files

$ecommerceRequirements = [
    'record_count' => 5000,
    'complexity' => 'complex',
    'concurrent_users' => 100,
    'relationships' => true,
    'data_integrity' => 'critical'
];
echo chooseDataStorage($ecommerceRequirements); // Use database
?>
```

### The Migration Path

1. **Start Simple**: Begin with files for prototypes and small projects
2. **Monitor Growth**: Watch for performance problems and complexity
3. **Plan Migration**: When you hit limits, plan database structure
4. **Migrate Gradually**: Move data and update code incrementally
5. **Optimize**: Add indexes, improve queries, implement caching

### Key Takeaways

**Files are great for:**
- Configuration and settings
- Logs and temporary data
- Small datasets (< 1,000 records)
- Simple applications
- Rapid prototyping

**Databases are essential for:**
- Large datasets (> 1,000 records)
- Multiple users accessing simultaneously  
- Complex relationships between data
- Critical data integrity requirements
- Advanced search and reporting

**Remember**: There's no shame in starting with files and migrating to a database later. Many successful applications began as simple file-based systems and evolved as they grew. The key is recognizing when you've outgrown files and making the transition before performance becomes a serious problem.

Start simple, but be ready to scale!
