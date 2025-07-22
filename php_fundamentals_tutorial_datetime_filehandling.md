# PHP Fundamentals Tutorial: Advanced Topics for Beginners

## Introduction

Welcome to your journey into PHP's more advanced features! While these topics might seem intimidating at first, they're actually fundamental building blocks that every PHP developer needs to understand. Think of this tutorial as your bridge from basic PHP syntax to practical, real-world programming skills.

Before we dive in, remember that PHP is a server-side scripting language, which means it runs on the web server before the page is sent to your browser. This makes it perfect for handling dynamic content, working with databases, and managing files - all of which we'll explore today.

---

## Chapter 1: PHP Date and Time - Working with Temporal Data

### Understanding Time in Programming

Time in programming isn't just about displaying clocks on websites. It's about understanding when events happen, scheduling tasks, calculating durations, and managing user interactions across different time zones. PHP provides powerful built-in functions to handle all these scenarios.

### The Foundation: Unix Timestamps

At its core, PHP represents time as a Unix timestamp - the number of seconds that have elapsed since January 1, 1970, 00:00:00 UTC. This might seem arbitrary, but it's actually a universal standard that makes time calculations incredibly efficient.

```php
<?php
// Get the current timestamp
$currentTime = time();
echo "Current timestamp: " . $currentTime; // Output: 1642780800 (example)

// This number represents seconds since January 1, 1970
// It's like a universal clock that all computers can understand
?>
```

### The date() Function - Your Primary Time Formatter

The `date()` function is your Swiss Army knife for formatting timestamps into human-readable strings. It takes a format string and an optional timestamp, returning a formatted date string.

```php
<?php
// Basic date formatting
echo date('Y-m-d'); // Output: 2024-01-21 (YYYY-MM-DD format)
echo date('F j, Y'); // Output: January 21, 2024 (Full month name)
echo date('l, F j, Y'); // Output: Sunday, January 21, 2024

// Time formatting
echo date('H:i:s'); // Output: 14:30:45 (24-hour format)
echo date('h:i:s A'); // Output: 02:30:45 PM (12-hour format)

// Using a specific timestamp instead of current time
$specificTime = mktime(15, 30, 0, 12, 25, 2024); // 3:30 PM on Dec 25, 2024
echo date('l, F j, Y \a\t g:i A', $specificTime); // Sunday, December 25, 2024 at 3:30 PM
?>
```

The format characters might look confusing at first, but they follow a logical pattern. 'Y' gives you a four-digit year, 'm' gives you a two-digit month, 'd' gives you a two-digit day, and so on. The backslashes in the last example escape literal characters that would otherwise be interpreted as format codes.

### Creating Specific Dates with mktime()

Sometimes you need to create a timestamp for a specific date and time. The `mktime()` function is perfect for this, taking parameters in the order: hour, minute, second, month, day, year.

```php
<?php
// Create a timestamp for a specific date and time
$birthday = mktime(0, 0, 0, 7, 4, 1990); // July 4, 1990 at midnight
echo "Birthday: " . date('F j, Y', $birthday);

// Calculate age (this demonstrates timestamp arithmetic)
$currentTime = time();
$ageInSeconds = $currentTime - $birthday;
$ageInYears = floor($ageInSeconds / (365.25 * 24 * 60 * 60)); // Account for leap years
echo "Age: " . $ageInYears . " years old";
?>
```

### Working with Different Time Zones

In our globally connected world, handling time zones correctly is crucial. PHP provides several functions to manage this complexity.

```php
<?php
// Set the default timezone for your application
date_default_timezone_set('America/New_York');
echo "New York time: " . date('Y-m-d H:i:s') . "\n";

// Temporarily work with a different timezone
date_default_timezone_set('Europe/London');
echo "London time: " . date('Y-m-d H:i:s') . "\n";

// Get a list of all available timezones
$timezones = timezone_identifiers_list();
echo "Total timezones available: " . count($timezones);
?>
```

### Practical Example: Event Countdown Timer

Let's create a practical example that combines several date functions to build a countdown timer for an event.

```php
<?php
// Set timezone for consistency
date_default_timezone_set('America/New_York');

// Define the event date (New Year's Day 2025)
$eventDate = mktime(0, 0, 0, 1, 1, 2025);
$currentTime = time();

// Calculate the difference
$timeDifference = $eventDate - $currentTime;

if ($timeDifference > 0) {
    // Event is in the future
    $days = floor($timeDifference / (24 * 60 * 60));
    $hours = floor(($timeDifference % (24 * 60 * 60)) / (60 * 60));
    $minutes = floor(($timeDifference % (60 * 60)) / 60);
    
    echo "Time until New Year: {$days} days, {$hours} hours, {$minutes} minutes";
} else {
    // Event has passed
    echo "New Year has already passed!";
}
?>
```

This example demonstrates how timestamps make time calculations straightforward - you can subtract one timestamp from another to get the difference in seconds, then convert that to meaningful units.

---

## Chapter 2: PHP Include - Building Modular Applications

### The Philosophy of Code Reuse

As your PHP applications grow, you'll quickly realize that copying and pasting code between files is inefficient and error-prone. The include system in PHP allows you to write code once and use it in multiple places, making your applications more maintainable and organized.

Think of include statements as a way to tell PHP: "Stop what you're doing, go read this other file, execute its code as if it were written right here, then come back and continue." This simple concept is the foundation of modular programming.

### Understanding include vs require

PHP provides four main functions for including files: `include`, `require`, `include_once`, and `require_once`. The key difference lies in how they handle errors and whether they can include the same file multiple times.

```php
<?php
// include: If file doesn't exist, show warning but continue execution
include 'config.php';
echo "This will still execute even if config.php doesn't exist";

// require: If file doesn't exist, show error and stop execution
require 'database.php';
echo "This will NOT execute if database.php doesn't exist";
?>
```

The choice between `include` and `require` depends on how critical the file is to your application. If your script can continue running without the file, use `include`. If the file is essential, use `require`.

### The "_once" Variants

The `_once` variants prevent the same file from being included multiple times, which is crucial for preventing function redefinition errors and improving performance.

```php
<?php
// This is safe - even if called multiple times, functions.php will only be included once
include_once 'functions.php';
include_once 'functions.php'; // This does nothing
include_once 'functions.php'; // This also does nothing

// Without _once, this would cause a fatal error if functions.php contains function definitions
include 'functions.php';
include 'functions.php'; // Fatal error: Cannot redeclare function...
?>
```

### Practical Example: Building a Website Header System

Let's create a practical example that demonstrates how to use includes to build a modular website structure.

First, create a configuration file (`config.php`):

```php
<?php
// config.php - Site-wide configuration
$siteTitle = "My Awesome Website";
$siteDescription = "A demonstration of PHP includes";
$navLinks = [
    'Home' => 'index.php',
    'About' => 'about.php',
    'Contact' => 'contact.php'
];

// Database configuration (we'll use this in the file handling section)
$dbConfig = [
    'host' => 'localhost',
    'username' => 'myuser',
    'password' => 'mypassword',
    'database' => 'mydb'
];
?>
```

Next, create a header template (`header.php`):

```php
<?php
// header.php - Reusable header template
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? $siteTitle; ?></title>
    <meta name="description" content="<?php echo $siteDescription; ?>">
</head>
<body>
    <header>
        <h1><?php echo $siteTitle; ?></h1>
        <nav>
            <?php foreach ($navLinks as $title => $url): ?>
                <a href="<?php echo $url; ?>"><?php echo $title; ?></a>
            <?php endforeach; ?>
        </nav>
    </header>
    <main>
```

Now, create a main page that uses these includes (`index.php`):

```php
<?php
// index.php - Main page
require_once 'config.php';
$pageTitle = "Welcome to " . $siteTitle;

include 'header.php';
?>

<h2>Welcome to Our Website</h2>
<p>This page demonstrates how PHP includes work together to create a modular website structure.</p>
<p>The header above was included from header.php, which uses configuration from config.php.</p>

<?php include 'footer.php'; ?>
```

### Understanding Variable Scope in Includes

When you include a file, it inherits the variable scope of the line where the include occurs. This means variables defined before the include are available in the included file, and variables defined in the included file become available after the include.

```php
<?php
// main.php
$greeting = "Hello";
$name = "World";

include 'message.php'; // This file can use $greeting and $name

echo $fullMessage; // This variable was defined in message.php
?>
```

```php
<?php
// message.php
$fullMessage = $greeting . ", " . $name . "!";
echo $fullMessage;
?>
```

### Best Practices for Using Includes

When working with includes, follow these guidelines to keep your code organized and maintainable:

Always use absolute paths or paths relative to your application root to avoid confusion. PHP provides several constants to help with this:

```php
<?php
// Using __DIR__ to get the directory of the current file
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// Using dirname() to go up directory levels
require_once dirname(__FILE__) . '/../config/settings.php';
?>
```

Create a consistent folder structure for your includes. A typical structure might look like:

```
project/
├── config/
│   ├── database.php
│   └── settings.php
├── includes/
│   ├── header.php
│   ├── footer.php
│   └── functions.php
├── pages/
│   ├── home.php
│   ├── about.php
│   └── contact.php
└── index.php
```

---

## Chapter 3: PHP File Handling - Working with the File System

### Understanding File Systems in Web Development

File handling is one of the most practical skills you'll use in PHP development. Whether you're logging user actions, storing configuration data, processing uploads, or caching content, understanding how to work with files is essential.

In PHP, file handling follows a simple pattern: open a file, perform operations (read, write, or both), then close the file. This pattern ensures that system resources are properly managed and prevents conflicts when multiple scripts try to access the same file.

### File Permissions and Security Considerations

Before diving into code, it's important to understand that web servers run with specific user permissions. Your PHP scripts can only access files that the web server has permission to read or write. This is a security feature that prevents malicious scripts from accessing sensitive system files.

```php
<?php
// Check if a file exists before trying to work with it
$filename = 'data.txt';

if (file_exists($filename)) {
    echo "File exists and is accessible";
    
    // Check specific permissions
    if (is_readable($filename)) {
        echo "File is readable";
    }
    
    if (is_writable($filename)) {
        echo "File is writable";
    }
} else {
    echo "File does not exist or is not accessible";
}
?>
```

### Reading Files: Different Approaches for Different Needs

PHP provides several ways to read files, each optimized for different scenarios. Let's explore the most common approaches:

#### Reading Entire Files at Once

When you need to read a small to medium-sized file completely, `file_get_contents()` is your best friend. It reads the entire file into a string variable in one operation.

```php
<?php
// Read an entire file into a string
$content = file_get_contents('sample.txt');

if ($content !== false) {
    echo "File content: " . $content;
    echo "File size: " . strlen($content) . " bytes";
} else {
    echo "Could not read file";
}

// Reading from a URL (yes, file_get_contents works with URLs too!)
$webpage = file_get_contents('https://api.example.com/data.json');
if ($webpage !== false) {
    $data = json_decode($webpage, true);
    // Process the API data
}
?>
```

#### Reading Files Line by Line

For larger files or when you need to process content line by line, the `file()` function or file handle approach is more memory-efficient.

```php
<?php
// Read file into an array, one line per element
$lines = file('large_log.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $lineNumber => $line) {
    echo "Line " . ($lineNumber + 1) . ": " . $line . "\n";
}

// Alternative approach using file handle for very large files
$handle = fopen('very_large_file.txt', 'r');
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        // Process each line individually
        echo "Processing: " . trim($line) . "\n";
    }
    fclose($handle);
}
?>
```

### Writing Files: Storing Data Persistently

Writing files is equally important for storing user data, caching results, or logging application events.

#### Simple File Writing

The `file_put_contents()` function is the easiest way to write data to a file. It handles opening, writing, and closing the file automatically.

```php
<?php
// Write a simple string to a file
$data = "Hello, World!\nThis is a test file.";
$bytesWritten = file_put_contents('output.txt', $data);

if ($bytesWritten !== false) {
    echo "Successfully wrote {$bytesWritten} bytes to file";
} else {
    echo "Failed to write file";
}

// Append data to an existing file
$additionalData = "\nThis line was appended.";
file_put_contents('output.txt', $additionalData, FILE_APPEND);
?>
```

#### Advanced File Writing with Error Handling

For more control over the writing process, especially when dealing with critical data, use file handles with proper error checking.

```php
<?php
function writeLogEntry($message) {
    $logFile = 'application.log';
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[{$timestamp}] {$message}\n";
    
    $handle = fopen($logFile, 'a'); // 'a' for append mode
    if ($handle) {
        if (fwrite($handle, $logEntry) !== false) {
            fclose($handle);
            return true;
        } else {
            fclose($handle);
            return false;
        }
    }
    return false;
}

// Usage
if (writeLogEntry("User logged in successfully")) {
    echo "Log entry written";
} else {
    echo "Failed to write log entry";
}
?>
```

### Practical Example: Building a Simple Guest Book

Let's create a practical application that demonstrates both reading and writing files - a guest book where visitors can leave messages.

```php
<?php
// guestbook.php - A simple guest book application
$guestbookFile = 'guestbook.txt';

// Handle form submission
if ($_POST['action'] === 'sign' && !empty($_POST['name']) && !empty($_POST['message'])) {
    $name = htmlspecialchars($_POST['name']); // Security: escape HTML
    $message = htmlspecialchars($_POST['message']);
    $timestamp = date('Y-m-d H:i:s');
    
    // Format the entry
    $entry = "---\n";
    $entry .= "Name: {$name}\n";
    $entry .= "Date: {$timestamp}\n";
    $entry .= "Message: {$message}\n";
    $entry .= "---\n\n";
    
    // Append to guestbook file
    if (file_put_contents($guestbookFile, $entry, FILE_APPEND | LOCK_EX)) {
        $success = "Thank you for signing our guestbook!";
    } else {
        $error = "Sorry, we couldn't save your entry. Please try again.";
    }
}

// Read existing entries
$entries = [];
if (file_exists($guestbookFile)) {
    $content = file_get_contents($guestbookFile);
    if ($content !== false) {
        // Parse entries (simple approach)
        $rawEntries = explode("---\n", $content);
        foreach ($rawEntries as $rawEntry) {
            $rawEntry = trim($rawEntry);
            if (!empty($rawEntry) && $rawEntry !== "---") {
                $entries[] = $rawEntry;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Guest Book</title>
</head>
<body>
    <h1>Guest Book</h1>
    
    <?php if (isset($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <form method="post">
        <input type="hidden" name="action" value="sign">
        <p>
            <label>Name: <input type="text" name="name" required></label>
        </p>
        <p>
            <label>Message: <textarea name="message" required></textarea></label>
        </p>
        <p>
            <button type="submit">Sign Guest Book</button>
        </p>
    </form>
    
    <h2>Previous Entries</h2>
    <?php if (!empty($entries)): ?>
        <?php foreach (array_reverse($entries) as $entry): ?>
            <pre><?php echo htmlspecialchars($entry); ?></pre>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No entries yet. Be the first to sign!</p>
    <?php endif; ?>
</body>
</html>
```

### Working with Different File Types

PHP can handle various file types, from plain text to binary files. Here's how to work with some common formats:

#### CSV Files

CSV (Comma-Separated Values) files are common for data exchange. PHP provides specialized functions for working with them.

```php
<?php
// Reading CSV files
$csvFile = 'users.csv';
$handle = fopen($csvFile, 'r');

if ($handle) {
    // Read header row
    $headers = fgetcsv($handle);
    
    // Read data rows
    while (($row = fgetcsv($handle)) !== false) {
        $userData = array_combine($headers, $row);
        echo "User: " . $userData['name'] . ", Email: " . $userData['email'] . "\n";
    }
    
    fclose($handle);
}

// Writing CSV files
$users = [
    ['John Doe', 'john@example.com', '25'],
    ['Jane Smith', 'jane@example.com', '30']
];

$handle = fopen('output.csv', 'w');
if ($handle) {
    // Write header
    fputcsv($handle, ['Name', 'Email', 'Age']);
    
    // Write data
    foreach ($users as $user) {
        fputcsv($handle, $user);
    }
    
    fclose($handle);
}
?>
```

#### JSON Files

JSON is perfect for storing structured data that needs to be easily readable by both PHP and JavaScript.

```php
<?php
// Writing JSON data
$config = [
    'database' => [
        'host' => 'localhost',
        'port' => 3306,
        'name' => 'myapp'
    ],
    'features' => [
        'user_registration' => true,
        'email_notifications' => false
    ]
];

$jsonString = json_encode($config, JSON_PRETTY_PRINT);
file_put_contents('config.json', $jsonString);

// Reading JSON data
$jsonContent = file_get_contents('config.json');
$config = json_decode($jsonContent, true);

echo "Database host: " . $config['database']['host'];
?>
```

### File System Operations Beyond Reading and Writing

PHP provides many functions for working with the file system beyond just reading and writing content.

```php
<?php
// File information
$file = 'document.pdf';
if (file_exists($file)) {
    echo "File size: " . filesize($file) . " bytes\n";
    echo "Last modified: " . date('Y-m-d H:i:s', filemtime($file)) . "\n";
    echo "File type: " . mime_content_type($file) . "\n";
}

// Directory operations
$directory = 'uploads';
if (!is_dir($directory)) {
    mkdir($directory, 0755); // Create directory with permissions
}

// List files in a directory
$files = scandir($directory);
foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        echo "Found file: " . $file . "\n";
    }
}

// Copy and move files
copy('original.txt', 'backup.txt');
rename('old_name.txt', 'new_name.txt');

// Delete files (be careful!)
if (file_exists('temporary.txt')) {
    unlink('temporary.txt');
}
?>
```

---

## Chapter 4: File Open/Read Operations - Advanced File Handling

### Understanding File Handles and Resources

When you open a file in PHP, you're not directly working with the file itself. Instead, PHP creates a resource - think of it as a pointer or reference to the file. This resource allows PHP to keep track of your current position in the file, manage memory efficiently, and handle multiple files simultaneously.

The concept of file handles becomes particularly important when dealing with large files or when you need precise control over how you read data. Unlike `file_get_contents()`, which loads everything into memory at once, file handles let you read small chunks at a time.

### The fopen() Function: Your Gateway to File Resources

The `fopen()` function is the foundation of advanced file handling in PHP. It opens a file and returns a resource that you can use with other file functions.

```php
<?php
// Basic file opening
$handle = fopen('data.txt', 'r'); // 'r' means read-only

if ($handle) {
    echo "File opened successfully";
    // Remember to always close the file when done
    fclose($handle);
} else {
    echo "Could not open file";
}
?>
```

### File Modes: Controlling How Files Are Opened

The second parameter of `fopen()` is the mode, which determines how the file can be accessed. Understanding these modes is crucial for proper file handling.

```php
<?php
// Different file modes demonstrated
$modes = [
    'r'  => 'Read only. File pointer starts at beginning.',
    'r+' => 'Read/write. File pointer starts at beginning.',
    'w'  => 'Write only. Truncates file to zero length or creates new file.',
    'w+' => 'Read/write. Truncates file to zero length or creates new file.',
    'a'  => 'Write only. File pointer starts at end (append mode).',
    'a+' => 'Read/write. File pointer starts at end for writing.',
    'x'  => 'Create and open for writing only. Fails if file already exists.',
    'x+' => 'Create and open for reading and writing. Fails if file already exists.'
];

// Example: Open file for reading
$readHandle = fopen('input.txt', 'r');
if ($readHandle) {
    // Read operations here
    fclose($readHandle);
}

// Example: Open file for appending
$appendHandle = fopen('log.txt', 'a');
if ($appendHandle) {
    fwrite($appendHandle, "New log entry\n");
    fclose($appendHandle);
}
?>
```

### Reading Data: Different Techniques for Different Scenarios

PHP provides several functions for reading data from file handles, each optimized for different types of reading operations.

#### Character-by-Character Reading

Sometimes you need to process a file one character at a time, particularly when parsing complex file formats or when memory usage is critical.

```php
<?php
function analyzeTextFile($filename) {
    $handle = fopen($filename, 'r');
    if (!$handle) {
        return false;
    }
    
    $charCount = 0;
    $wordCount = 0;
    $lineCount = 0;
    $inWord = false;
    
    while (($char = fgetc($handle)) !== false) {
        $charCount++;
        
        if ($char === "\n") {
            $lineCount++;
        }
        
        if (ctype_space($char)) {
            if ($inWord) {
                $wordCount++;
                $inWord = false;
            }
        } else {
            $inWord = true;
        }
    }
    
    // Count the last word if file doesn't end with whitespace
    if ($inWord) {
        $wordCount++;
    }
    
    fclose($handle);
    
    return [
        'characters' => $charCount,
        'words' => $wordCount,
        'lines' => $lineCount
    ];
}

// Usage
$stats = analyzeTextFile('document.txt');
if ($stats) {
    echo "Characters: {$stats['characters']}\n";
    echo "Words: {$stats['words']}\n";
    echo "Lines: {$stats['lines']}\n";
}
?>
```

#### Line-by-Line Reading

Reading line by line is perfect for processing log files, CSV data, or any file where each line represents a discrete piece of information.

```php
<?php
function processLogFile($filename) {
    $handle = fopen($filename, 'r');
    if (!$handle) {
        echo "Could not open log file";
        return;
    }
    
    $lineNumber = 0;
    $errorCount = 0;
    $warningCount = 0;
    
    while (($line = fgets($handle)) !== false) {
        $lineNumber++;
        $line = trim($line); // Remove whitespace
        
        if (strpos($line, 'ERROR') !== false) {
            $errorCount++;
            echo "Error on line {$lineNumber}: {$line}\n";
        } elseif (strpos($line, 'WARNING') !== false) {
            $warningCount++;
            echo "Warning on line {$lineNumber}: {$line}\n";
        }
    }
    
    fclose($handle);
    
    echo "Summary: {$errorCount} errors, {$warningCount} warnings in {$lineNumber} lines\n";
}

// Usage
processLogFile('application.log');
?>
```

#### Fixed-Size Block Reading

When dealing with large files or binary data, reading fixed-size blocks can be more efficient than reading line by line.

```php
<?php
function copyFileInChunks($source, $destination, $chunkSize = 8192) {
    $sourceHandle = fopen($source, 'rb'); // 'b' for binary mode
    $destHandle = fopen($destination, 'wb');
    
    if (!$sourceHandle || !$destHandle) {
        echo "Could not open files for copying";
        return false;
    }
    
    $totalBytes = 0;
    
    while (!feof($sourceHandle)) {
        $chunk = fread($sourceHandle, $chunkSize);
        if ($chunk !== false) {
            fwrite($destHandle, $chunk);
            $totalBytes += strlen($chunk);
        }
    }
    
    fclose($sourceHandle);
    fclose($destHandle);
    
    echo "Copied {$totalBytes} bytes successfully\n";
    return true;
}

// Usage
copyFileInChunks('large_video.mp4', 'backup_video.mp4');
?>
```

### File Pointer Management

Understanding how to control the file pointer - the current position in the file - gives you powerful control over file reading operations.

```php
<?php
$handle = fopen('data.txt', 'r');
if ($handle) {
    // Read first 10 characters
    $beginning = fread($handle, 10);
    echo "First 10 chars: " . $beginning . "\n";
    
    // Check current position
    $position = ftell($handle);
    echo "Current position: " . $position . "\n";
    
    // Jump to position 50
    fseek($handle, 50);
    
    // Read next 20 characters
    $middle = fread($handle, 20);
    echo "20 chars from position 50: " . $middle . "\n";
    
    // Jump to 100 characters from the end
    fseek($handle, -100, SEEK_END);
    $nearEnd = fread($handle, 50);
    echo "50 chars from near end: " . $nearEnd . "\n";
    
    // Go back to beginning
    rewind($handle);
    
    fclose($handle);
}
?>
```

### Error Handling and File Validation

Robust file handling requires proper error checking at every step. Here's a comprehensive approach to error handling:

```php
<?php
function safeReadFile($filename, $maxSize = 1048576) { // 1MB default limit
    // Check if file exists
    if (!file_exists($filename)) {
        throw new Exception("File does not exist: {$filename}");
    }
    
    // Check if file is readable
    if (!is_readable($filename)) {
        throw new Exception("File is not readable: {$filename}");
    }
    
    // Check file size
    $filesize = filesize($filename);
    if ($filesize > $maxSize) {
        throw new Exception("File too large: {$filesize} bytes (max: {$maxSize})");
    }
    
    // Open file
    $handle = fopen($filename, 'r');
    if (!$handle) {
        throw new Exception("Could not open file: {$filename}");
    }
    
    // Read file content
    $content = fread($handle, $filesize);
    if ($content === false) {
        fclose($handle);
        throw new Exception("Could not read file content: {$filename}");
    }
    
    fclose($handle);
    return $content;
}

// Usage with error handling
try {
    $content = safeReadFile('important_data.txt');
    echo "Successfully read file: " . strlen($content) . " bytes\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
```

### Practical Example: