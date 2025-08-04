# Complete PHP File System and File Handling Tutorial

## Table of Contents

1. [File System Fundamentals](#file-system-fundamentals)
2. [Understanding Paths](#understanding-paths)
3. [Users, Groups, and Permissions](#users-groups-and-permissions)
4. [PHP File System Functions](#php-file-system-functions)
5. [Reading Files](#reading-files)
6. [Writing Files](#writing-files)
7. [File Information and Properties](#file-information-and-properties)
8. [Directory Operations](#directory-operations)
9. [File Permissions in PHP](#file-permissions-in-php)
10. [Error Handling](#error-handling)
11. [Best Practices](#best-practices)
12. [Common Workflows](#common-workflows)

---

## File System Fundamentals

### What is a File System?

A file system is how your computer organizes and stores data on storage devices (hard drives, SSDs, etc.). Think of it like a filing cabinet where:

- **Files** are individual documents (like `.txt`, `.php`, `.jpg` files)
- **Directories (folders)** are containers that hold files and other directories
- **Links** are shortcuts that point to other files or directories

### Files vs Directories

**Files** contain data and have:

- A name (e.g., `document.txt`)
- An extension that usually indicates the file type (`.txt`, `.php`, `.html`)
- Content (text, binary data, etc.)
- Properties (size, creation date, permissions)

**Directories** are containers that:

- Hold files and other directories
- Have names but typically no extensions
- Can be empty or contain multiple items
- Have their own permissions

### Types of Links

1. **Hard Links**: Direct references to file data on disk
2. **Soft Links (Symbolic Links)**: Pointers to other files/directories (like shortcuts)

---

## Understanding Paths

A **path** is the address that tells you exactly where a file or directory is located.

### Types of Paths

**Absolute Paths**: Start from the root of the file system

```
Linux/Mac:   /home/user/documents/file.txt
Windows:     C:\Users\User\Documents\file.txt
```

**Relative Paths**: Start from your current location

```
./file.txt          (current directory)
../file.txt         (parent directory)
folder/file.txt     (subdirectory)
```

### Path Separators

- **Unix/Linux/Mac**: Forward slash `/`
- **Windows**: Backslash `\` (but PHP accepts `/` on Windows too)

### Special Path Components

- `.` = Current directory
- `..` = Parent directory
- `~` = Home directory (Unix/Linux/Mac)

---

## Users, Groups, and Permissions

### Users and Groups

**Users**: Individual accounts on the system

- Each file/directory has an owner (user)
- The owner can set permissions

**Groups**: Collections of users

- Files can belong to a group
- Group members share certain permissions

### Permission Types

Each file/directory has three types of permissions:

1. **Read (r)**: Can view/read the content
2. **Write (w)**: Can modify/delete the content
3. **Execute (x)**: Can run the file (for files) or access the directory

### Permission Categories

Permissions are set for three categories:

1. **Owner**: The user who owns the file
2. **Group**: Users in the file's group
3. **Other**: Everyone else

### Numeric Permissions

Permissions are often represented as 3-digit numbers:

- **4** = Read
- **2** = Write
- **1** = Execute

Common combinations:

- **755**: Owner can read/write/execute, others can read/execute
- **644**: Owner can read/write, others can only read
- **777**: Everyone can do everything (dangerous!)

---

## PHP File System Functions

Now let's dive into PHP's built-in functions for working with files and directories.

### Basic File Operations

#### `file_exists()` - Check if a file exists

```php
<?php
$filename = 'example.txt';

if (file_exists($filename)) {
    echo "File exists!";
} else {
    echo "File does not exist.";
}
?>
```

**Explanation**: This function returns `true` if the file or directory exists, `false` otherwise. Always check if a file exists before trying to work with it.

#### `is_file()` and `is_dir()` - Check file type

```php
<?php
$path = 'example.txt';

if (is_file($path)) {
    echo "$path is a file";
} elseif (is_dir($path)) {
    echo "$path is a directory";
} else {
    echo "$path doesn't exist or is neither file nor directory";
}
?>
```

**Explanation**: `is_file()` returns true only for actual files, `is_dir()` returns true only for directories. These are more specific than `file_exists()`.

---

## Reading Files

### `file_get_contents()` - Read entire file into string

```php
<?php
$filename = 'data.txt';

// Check if file exists first
if (file_exists($filename)) {
    $content = file_get_contents($filename);
    echo $content;
} else {
    echo "File not found!";
}
?>
```

**Explanation**: This function reads the entire file content into a single string variable. It's the easiest way to read small to medium files.

### `file()` - Read file into array

```php
<?php
$filename = 'lines.txt';

if (file_exists($filename)) {
    $lines = file($filename);

    // Each line becomes an array element
    foreach ($lines as $lineNumber => $line) {
        echo "Line " . ($lineNumber + 1) . ": " . $line;
    }
}
?>
```

**Explanation**: The `file()` function reads each line of the file into an array element. Useful when you need to process files line by line.

### `fopen()`, `fread()`, `fclose()` - File handle operations

```php
<?php
$filename = 'large_file.txt';

// Open file for reading
$handle = fopen($filename, 'r');

if ($handle) {
    // Read file in chunks
    while (!feof($handle)) {
        $chunk = fread($handle, 1024); // Read 1024 bytes at a time
        echo $chunk;
    }

    // Always close the file handle
    fclose($handle);
} else {
    echo "Could not open file!";
}
?>
```

**Explanation**:

- `fopen()` opens a file and returns a file handle
- `fread()` reads a specified number of bytes
- `feof()` checks if we've reached the end of file
- `fclose()` closes the file handle (important for memory management)

### File Opening Modes

```php
<?php
// Different modes for fopen()
$modes = [
    'r'  => 'Read only',
    'w'  => 'Write only (truncates file)',
    'a'  => 'Write only (appends to end)',
    'r+' => 'Read and write',
    'w+' => 'Read and write (truncates file)',
    'a+' => 'Read and write (appends to end)'
];

// Example: Open for reading
$file = fopen('data.txt', 'r');
?>
```

---

## Writing Files

### `file_put_contents()` - Write string to file

```php
<?php
$filename = 'output.txt';
$data = "Hello, World!\nThis is a new line.";

// Write data to file (creates file if it doesn't exist)
$bytes_written = file_put_contents($filename, $data);

if ($bytes_written !== false) {
    echo "Successfully wrote $bytes_written bytes to $filename";
} else {
    echo "Failed to write to file!";
}
?>
```

**Explanation**: `file_put_contents()` writes data to a file in one operation. It returns the number of bytes written or `false` on failure.

### Appending to Files

```php
<?php
$filename = 'log.txt';
$new_entry = date('Y-m-d H:i:s') . " - User logged in\n";

// Append to file (don't overwrite existing content)
file_put_contents($filename, $new_entry, FILE_APPEND | LOCK_EX);

echo "Log entry added!";
?>
```

**Explanation**:

- `FILE_APPEND` flag appends data instead of overwriting
- `LOCK_EX` flag prevents other processes from writing simultaneously

### Using `fwrite()` for More Control

```php
<?php
$filename = 'data.txt';
$handle = fopen($filename, 'w');

if ($handle) {
    $data = [
        "First line\n",
        "Second line\n",
        "Third line\n"
    ];

    foreach ($data as $line) {
        fwrite($handle, $line);
    }

    fclose($handle);
    echo "File written successfully!";
} else {
    echo "Could not open file for writing!";
}
?>
```

**Explanation**: `fwrite()` gives you more control over the writing process, allowing you to write data piece by piece.

---

## File Information and Properties

### Getting File Information

```php
<?php
$filename = 'document.pdf';

if (file_exists($filename)) {
    echo "File: $filename\n";
    echo "Size: " . filesize($filename) . " bytes\n";
    echo "Last modified: " . date('Y-m-d H:i:s', filemtime($filename)) . "\n";
    echo "Last accessed: " . date('Y-m-d H:i:s', fileatime($filename)) . "\n";
    echo "Permissions: " . substr(sprintf('%o', fileperms($filename)), -4) . "\n";
    echo "Owner: " . fileowner($filename) . "\n";
    echo "Group: " . filegroup($filename) . "\n";
}
?>
```

**Explanation**:

- `filesize()` returns file size in bytes
- `filemtime()` returns last modification time as Unix timestamp
- `fileatime()` returns last access time
- `fileperms()` returns permissions as octal number
- `fileowner()` and `filegroup()` return owner/group IDs

### `pathinfo()` - Parse file paths

```php
<?php
$filepath = '/home/user/documents/report.pdf';
$info = pathinfo($filepath);

echo "Directory: " . $info['dirname'] . "\n";      // /home/user/documents
echo "Filename: " . $info['basename'] . "\n";      // report.pdf
echo "Name: " . $info['filename'] . "\n";          // report
echo "Extension: " . $info['extension'] . "\n";    // pdf

// You can also get specific parts
echo "Just extension: " . pathinfo($filepath, PATHINFO_EXTENSION) . "\n";
?>
```

**Explanation**: `pathinfo()` breaks down a file path into its components, making it easy to work with different parts of the path.

---

## Directory Operations

### Creating Directories

```php
<?php
$directory = 'uploads/images';

// Create directory (including parent directories)
if (!is_dir($directory)) {
    if (mkdir($directory, 0755, true)) {
        echo "Directory created successfully!";
    } else {
        echo "Failed to create directory!";
    }
} else {
    echo "Directory already exists!";
}
?>
```

**Explanation**:

- `mkdir()` creates a directory
- Second parameter is permissions (0755 is common)
- Third parameter `true` creates parent directories if needed

### Listing Directory Contents

```php
<?php
$directory = './';

// Method 1: Using scandir()
$files = scandir($directory);

echo "Files in directory:\n";
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {  // Skip special entries
        if (is_dir($directory . $file)) {
            echo "[DIR]  $file\n";
        } else {
            echo "[FILE] $file\n";
        }
    }
}
?>
```

**Explanation**: `scandir()` returns an array of files and directories. The special entries `.` and `..` represent current and parent directories.

### Alternative Method with opendir()

```php
<?php
$directory = './documents';

// Method 2: Using opendir() and readdir()
if (is_dir($directory)) {
    $handle = opendir($directory);

    echo "Files in $directory:\n";
    while (($file = readdir($handle)) !== false) {
        if ($file != '.' && $file != '..') {  // Skip special entries
            $fullPath = $directory . '/' . $file;

            if (is_dir($fullPath)) {
                echo "[DIR]  $file\n";
            } else {
                echo "[FILE] $file (" . filesize($fullPath) . " bytes)\n";
            }
        }
    }

    closedir($handle);
}
?>
```

**Explanation**: `opendir()` opens a directory handle, `readdir()` reads entries one by one, and `closedir()` closes the handle. This method gives you more control over the reading process.

### Removing Directories

```php
<?php
function removeDirectory($dir) {
    if (!is_dir($dir)) {
        return false;
    }

    $files = scandir($dir);

    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $path = $dir . '/' . $file;

            if (is_dir($path)) {
                removeDirectory($path);  // Recursive call for subdirectories
            } else {
                unlink($path);  // Delete file
            }
        }
    }

    return rmdir($dir);  // Remove the empty directory
}

// Usage
if (removeDirectory('./temp_folder')) {
    echo "Directory removed successfully!";
} else {
    echo "Failed to remove directory!";
}
?>
```

**Explanation**: This recursive function deletes all files and subdirectories before removing the main directory. PHP's `rmdir()` only works on empty directories.

---

## File Permissions in PHP

### Checking Permissions

```php
<?php
$filename = 'important.txt';

if (file_exists($filename)) {
    echo "File permissions check:\n";
    echo "Readable: " . (is_readable($filename) ? 'Yes' : 'No') . "\n";
    echo "Writable: " . (is_writable($filename) ? 'Yes' : 'No') . "\n";
    echo "Executable: " . (is_executable($filename) ? 'Yes' : 'No') . "\n";
}
?>
```

**Explanation**: These functions check if the current PHP process has specific permissions on the file.

### Setting Permissions

```php
<?php
$filename = 'config.txt';

// Set permissions (owner: read/write, group: read, others: read)
if (chmod($filename, 0644)) {
    echo "Permissions set successfully!";
} else {
    echo "Failed to set permissions!";
}

// Common permission examples:
$permissions = [
    0644 => 'Owner: read/write, Others: read only',
    0755 => 'Owner: read/write/execute, Others: read/execute',
    0600 => 'Owner: read/write, Others: no access',
    0777 => 'Everyone: full access (dangerous!)'
];
?>
```

**Explanation**: `chmod()` changes file permissions. The octal notation (starting with 0) is commonly used.

---

## Error Handling

### Basic Error Checking

```php
<?php
$filename = 'data.txt';

// Always check for errors
$content = file_get_contents($filename);

if ($content === false) {
    $error = error_get_last();
    echo "Error reading file: " . $error['message'];
} else {
    echo "File content: " . $content;
}
?>
```

### Using Try-Catch with Exceptions

```php
<?php
function safeFileRead($filename) {
    if (!file_exists($filename)) {
        throw new Exception("File does not exist: $filename");
    }

    if (!is_readable($filename)) {
        throw new Exception("File is not readable: $filename");
    }

    $content = file_get_contents($filename);

    if ($content === false) {
        throw new Exception("Failed to read file: $filename");
    }

    return $content;
}

// Usage with error handling
try {
    $content = safeFileRead('important.txt');
    echo "File content: $content";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
```

**Explanation**: This approach uses exceptions for better error handling and more readable code.

---

## Best Practices

### 1. Always Validate Input

```php
<?php
function safeFilename($filename) {
    // Remove dangerous characters
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);

    // Prevent directory traversal
    $filename = basename($filename);

    // Limit length
    if (strlen($filename) > 255) {
        $filename = substr($filename, 0, 255);
    }

    return $filename;
}

// Usage
$userFilename = $_POST['filename'] ?? '';
$safeFilename = safeFilename($userFilename);
?>
```

### 2. Use Absolute Paths When Possible

```php
<?php
// Better approach: define a base directory
define('UPLOAD_DIR', '/var/www/uploads/');

function getUploadPath($filename) {
    return UPLOAD_DIR . basename($filename);
}

$filepath = getUploadPath('user_document.pdf');
?>
```

### 3. Implement Proper Lock Mechanisms

```php
<?php
function safeFileWrite($filename, $data) {
    $handle = fopen($filename, 'w');

    if (!$handle) {
        return false;
    }

    // Lock file exclusively while writing
    if (flock($handle, LOCK_EX)) {
        fwrite($handle, $data);
        flock($handle, LOCK_UN);  // Unlock
        fclose($handle);
        return true;
    } else {
        fclose($handle);
        return false;
    }
}
?>
```

---

## Common Workflows

### 1. Simple File Upload Handler

```php
<?php
// Simple file upload processing
if (isset($_FILES['upload']) && $_FILES['upload']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = './uploads/';

    // Create upload directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Get file information
    $originalName = $_FILES['upload']['name'];
    $tempName = $_FILES['upload']['tmp_name'];
    $fileSize = $_FILES['upload']['size'];

    // Create a safe filename
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $newFilename = uniqid() . '.' . $extension;
    $destination = $uploadDir . $newFilename;

    // Check file size (limit to 5MB)
    if ($fileSize > 5 * 1024 * 1024) {
        echo "File too large! Maximum size is 5MB.";
    } else {
        // Move the uploaded file
        if (move_uploaded_file($tempName, $destination)) {
            chmod($destination, 0644);  // Set appropriate permissions
            echo "File uploaded successfully as: $newFilename";
        } else {
            echo "Failed to upload file!";
        }
    }
} else {
    echo "No file uploaded or upload error occurred.";
}
?>
```

### 2. Reading and Processing CSV Files

```php
<?php
$csvFile = 'data.csv';

if (file_exists($csvFile)) {
    // Read CSV file line by line
    $handle = fopen($csvFile, 'r');

    if ($handle) {
        $rowNumber = 0;

        while (($data = fgetcsv($handle)) !== false) {
            $rowNumber++;

            if ($rowNumber === 1) {
                // First row contains headers
                echo "Headers: " . implode(', ', $data) . "\n";
            } else {
                // Process data rows
                echo "Row $rowNumber: " . implode(' | ', $data) . "\n";
            }
        }

        fclose($handle);
    }
} else {
    echo "CSV file not found!";
}
?>
```

### 3. Simple Configuration File Handler

```php
<?php
$configFile = 'config.txt';

// Function to read configuration
function loadConfig($filename) {
    $config = [];

    if (file_exists($filename)) {
        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Skip comments
            if (strpos($line, '#') === 0) {
                continue;
            }

            // Parse key=value pairs
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $config[trim($key)] = trim($value);
            }
        }
    }

    return $config;
}

// Function to save configuration
function saveConfig($filename, $config) {
    $content = "# Configuration File\n";
    $content .= "# Generated on " . date('Y-m-d H:i:s') . "\n\n";

    foreach ($config as $key => $value) {
        $content .= "$key=$value\n";
    }

    return file_put_contents($filename, $content, LOCK_EX) !== false;
}

// Usage example
$config = loadConfig($configFile);

// Add or modify settings
$config['database_host'] = 'localhost';
$config['database_name'] = 'mydb';
$config['debug_mode'] = 'true';

// Save back to file
if (saveConfig($configFile, $config)) {
    echo "Configuration saved successfully!";
} else {
    echo "Failed to save configuration!";
}

// Display current settings
echo "\nCurrent settings:\n";
foreach ($config as $key => $value) {
    echo "$key: $value\n";
}
?>
```

### 4. Simple Logging Function

```php
<?php
function writeLog($message, $level = 'INFO', $logFile = 'app.log') {
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] [$level] $message\n";

    // Append to log file
    if (file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX) !== false) {
        return true;
    } else {
        return false;
    }
}

// Function to read recent log entries
function readRecentLogs($logFile = 'app.log', $lines = 10) {
    if (!file_exists($logFile)) {
        return [];
    }

    $allLines = file($logFile, FILE_IGNORE_NEW_LINES);
    return array_slice($allLines, -$lines);  // Get last N lines
}

// Usage examples
writeLog('Application started');
writeLog('User login attempt', 'INFO');
writeLog('Database connection failed', 'ERROR');
writeLog('Low disk space', 'WARNING');

// Display recent log entries
echo "Recent log entries:\n";
$recentLogs = readRecentLogs('app.log', 5);
foreach ($recentLogs as $logEntry) {
    echo $logEntry . "\n";
}
?>
```

### 5. File Backup Function

```php
<?php
function createFileBackup($sourceFile, $backupDir = './backups/') {
    if (!file_exists($sourceFile)) {
        return false;
    }

    // Create backup directory if it doesn't exist
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755, true);
    }

    // Generate backup filename with timestamp
    $filename = basename($sourceFile);
    $timestamp = date('Y-m-d_H-i-s');
    $backupFilename = pathinfo($filename, PATHINFO_FILENAME) . '_' . $timestamp . '.' . pathinfo($filename, PATHINFO_EXTENSION);
    $backupPath = $backupDir . $backupFilename;

    // Copy the file
    if (copy($sourceFile, $backupPath)) {
        echo "Backup created: $backupPath\n";
        return $backupPath;
    } else {
        echo "Failed to create backup!\n";
        return false;
    }
}

function listBackups($backupDir = './backups/') {
    if (!is_dir($backupDir)) {
        echo "Backup directory doesn't exist.\n";
        return;
    }

    $files = scandir($backupDir);
    $backupFiles = [];

    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && is_file($backupDir . $file)) {
            $backupFiles[] = [
                'name' => $file,
                'size' => filesize($backupDir . $file),
                'modified' => filemtime($backupDir . $file)
            ];
        }
    }

    // Sort by modification time (newest first)
    usort($backupFiles, function($a, $b) {
        return $b['modified'] - $a['modified'];
    });

    echo "Available backups:\n";
    foreach ($backupFiles as $backup) {
        $date = date('Y-m-d H:i:s', $backup['modified']);
        $size = round($backup['size'] / 1024, 2);
        echo "- {$backup['name']} ({$size} KB, $date)\n";
    }
}

// Usage
$importantFile = 'important_data.txt';

// Create a backup
createFileBackup($importantFile);

// List all backups
listBackups();
?>
```

### 6. Directory Size Calculator

```php
<?php
function calculateDirectorySize($directory) {
    $size = 0;

    if (is_dir($directory)) {
        $files = scandir($directory);

        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $filePath = $directory . '/' . $file;

                if (is_dir($filePath)) {
                    // Recursively calculate subdirectory size
                    $size += calculateDirectorySize($filePath);
                } else {
                    $size += filesize($filePath);
                }
            }
        }
    }

    return $size;
}

function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }

    return round($bytes, $precision) . ' ' . $units[$i];
}

// Usage
$directory = './documents';
$totalSize = calculateDirectorySize($directory);

echo "Directory: $directory\n";
echo "Total size: " . formatBytes($totalSize) . "\n";
echo "Total size (bytes): $totalSize\n";
?>
```

---

## Summary

This tutorial covered:

1. **File System Basics**: Understanding files, directories, and paths
2. **Permissions**: How user/group permissions work
3. **PHP Functions**: Essential functions for file operations
4. **Reading/Writing**: Different methods for file I/O
5. **Directory Operations**: Creating, listing, and managing directories
6. **Error Handling**: Proper ways to handle file operation errors
7. **Best Practices**: Security and reliability considerations
8. **Practical Examples**: Real-world workflows you can implement

Remember to always:

- Check if files exist before operating on them
- Handle errors gracefully
- Validate user input
- Use appropriate file permissions
- Close file handles when done
- Consider security implications

Practice these concepts with small examples before implementing them in larger applications!
