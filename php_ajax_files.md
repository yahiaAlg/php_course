# PHP Fundamentals for AJAX: Working with Media and Files

## Table of Contents

1. [Introduction to PHP and AJAX](#introduction)
2. [Setting Up Your Environment](#setup)
3. [PHP Basics for AJAX](#php-basics)
4. [Understanding AJAX Fundamentals](#ajax-fundamentals)
5. [File Upload with AJAX](#file-upload)
6. [Working with Images](#working-with-images)
7. [Handling Different File Types](#file-types)
8. [Security Considerations](#security)
9. [Advanced Techniques](#advanced)
10. [Troubleshooting Common Issues](#troubleshooting)

---

## 1. Introduction to PHP and AJAX {#introduction}

### What is PHP?

PHP (PHP: Hypertext Preprocessor) is a server-side scripting language designed for web development. It processes code on the server before sending the result to the user's browser. This makes it perfect for handling file uploads, database operations, and generating dynamic content.

### What is AJAX?

AJAX (Asynchronous JavaScript and XML) allows web pages to communicate with the server without refreshing the entire page. When combined with PHP, it creates seamless user experiences for file uploads and media handling.

### Why Use PHP with AJAX for Media/Files?

Traditional file uploads require page refreshes and provide poor user experience. PHP with AJAX enables:

- **Real-time progress tracking** during uploads
- **Instant feedback** without page reloads
- **Better error handling** with immediate user notification
- **Enhanced user interface** with dynamic content updates

The combination works by having JavaScript send files to PHP scripts asynchronously, while PHP processes the files and returns responses that JavaScript can use to update the page dynamically.

---

## 2. Setting Up Your Environment {#setup}

### Basic Requirements

You'll need a web server with PHP support. For local development, consider XAMPP, WAMP, or MAMP. These packages include Apache web server, PHP, and MySQL database.

### Directory Structure

Create a working directory with the following structure:

```
project/
├── uploads/          (for storing uploaded files)
├── js/              (JavaScript files)
├── css/             (stylesheets)
├── includes/        (PHP utility files)
└── index.php        (main file)
```

### PHP Configuration

Check your `php.ini` file for these important settings:

- `file_uploads = On` - enables file uploads
- `upload_max_filesize = 32M` - maximum file size
- `post_max_size = 40M` - should be larger than upload_max_filesize
- `max_execution_time = 300` - prevents timeout on large uploads

You can check these values using `phpinfo()` or by creating a simple PHP script that displays current settings.

---

## 3. PHP Basics for AJAX {#php-basics}

### Understanding $\_FILES Superglobal

When files are uploaded through forms, PHP stores information in the `$_FILES` superglobal array. This array contains crucial data about uploaded files:

```php
<?php
// Example of $_FILES structure after file upload
print_r($_FILES);
/*
Array(
    [uploaded_file] => Array(
        [name] => document.pdf
        [type] => application/pdf
        [tmp_name] => /tmp/phpXXXXXX
        [error] => 0
        [size] => 1048576
    )
)
*/
?>
```

Each uploaded file has five properties:

- **name**: Original filename from user's computer
- **type**: MIME type (e.g., image/jpeg, application/pdf)
- **tmp_name**: Temporary file path on server
- **error**: Error code (0 means no error)
- **size**: File size in bytes

### Essential PHP Functions for File Handling

#### move_uploaded_file()

This function safely moves uploaded files from temporary directory to permanent location:

```php
<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);

if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
    echo "File uploaded successfully";
} else {
    echo "Upload failed";
}
?>
```

#### pathinfo()

Extracts information about file paths:

```php
<?php
$file_path = "uploads/document.pdf";
$path_info = pathinfo($file_path);

echo $path_info['dirname'];   // "uploads"
echo $path_info['basename'];  // "document.pdf"
echo $path_info['extension']; // "pdf"
echo $path_info['filename'];  // "document"
?>
```

#### file_exists() and is_uploaded_file()

These functions help verify file status:

```php
<?php
// Check if file already exists
if (file_exists($target_file)) {
    echo "File already exists";
}

// Verify file was uploaded via HTTP POST
if (is_uploaded_file($_FILES["file"]["tmp_name"])) {
    echo "File uploaded via POST";
}
?>
```

### JSON Responses for AJAX

AJAX applications typically expect JSON responses. PHP's `json_encode()` function converts PHP arrays to JSON:

```php
<?php
header('Content-Type: application/json');

$response = array(
    'success' => true,
    'message' => 'File uploaded successfully',
    'filename' => $uploaded_filename,
    'size' => $file_size
);

echo json_encode($response);
?>
```

---

## 4. Understanding AJAX Fundamentals {#ajax-fundamentals}

### The XMLHttpRequest Object

Modern browsers provide the `XMLHttpRequest` object for AJAX communication. Here's how it works with file uploads:

```javascript
function uploadFile(file) {
  const xhr = new XMLHttpRequest();
  const formData = new FormData();

  // Add file to FormData object
  formData.append("file", file);

  // Set up event handlers
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      console.log("Upload successful:", response);
    }
  };

  // Send request to PHP script
  xhr.open("POST", "upload.php");
  xhr.send(formData);
}
```

### FormData Object

The `FormData` object is crucial for file uploads because it can handle binary data. Unlike regular form submissions, FormData preserves file structure and metadata that PHP needs to process uploads correctly.

### Progress Tracking

AJAX allows real-time upload progress monitoring:

```javascript
xhr.upload.onprogress = function (event) {
  if (event.lengthComputable) {
    const percentComplete = (event.loaded / event.total) * 100;
    updateProgressBar(percentComplete);
  }
};
```

This event fires periodically during upload, providing `loaded` bytes and `total` bytes, allowing you to calculate and display progress to users.

---

## 5. File Upload with AJAX {#file-upload}

### Basic HTML Structure

Start with a simple HTML form that won't actually submit in the traditional way:

```html
<!DOCTYPE html>
<html>
  <head>
    <title>AJAX File Upload</title>
  </head>
  <body>
    <form id="uploadForm" enctype="multipart/form-data">
      <input type="file" id="fileInput" name="file" required />
      <button type="submit">Upload File</button>
    </form>

    <div id="progress" style="display: none;">
      <div
        id="progressBar"
        style="width: 0%; height: 20px; background: blue;"
      ></div>
      <span id="progressText">0%</span>
    </div>

    <div id="result"></div>
  </body>
</html>
```

### JavaScript for File Upload

Here's the complete JavaScript code that handles the upload process:

```javascript
document.getElementById("uploadForm").addEventListener("submit", function (e) {
  e.preventDefault(); // Prevent normal form submission

  const fileInput = document.getElementById("fileInput");
  const file = fileInput.files[0];

  if (!file) {
    alert("Please select a file");
    return;
  }

  uploadFile(file);
});

function uploadFile(file) {
  const xhr = new XMLHttpRequest();
  const formData = new FormData();

  formData.append("file", file);

  // Show progress bar
  document.getElementById("progress").style.display = "block";

  // Track upload progress
  xhr.upload.onprogress = function (event) {
    if (event.lengthComputable) {
      const percentComplete = (event.loaded / event.total) * 100;
      document.getElementById("progressBar").style.width =
        percentComplete + "%";
      document.getElementById("progressText").textContent =
        Math.round(percentComplete) + "%";
    }
  };

  // Handle completion
  xhr.onload = function () {
    document.getElementById("progress").style.display = "none";

    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      if (response.success) {
        document.getElementById("result").innerHTML =
          "<p>Upload successful: " + response.filename + "</p>";
      } else {
        document.getElementById("result").innerHTML =
          "<p>Error: " + response.message + "</p>";
      }
    } else {
      document.getElementById("result").innerHTML =
        "<p>Upload failed. Server error.</p>";
    }
  };

  xhr.open("POST", "upload.php");
  xhr.send(formData);
}
```

### PHP Upload Handler

Create `upload.php` to handle the server-side processing:

```php
<?php
header('Content-Type: application/json');

// Check if file was uploaded
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
    exit;
}

$file = $_FILES['file'];
$upload_dir = 'uploads/';

// Create upload directory if it doesn't exist
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Generate unique filename to prevent conflicts
$file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$unique_filename = uniqid() . '.' . $file_extension;
$target_path = $upload_dir . $unique_filename;

// Move uploaded file
if (move_uploaded_file($file['tmp_name'], $target_path)) {
    echo json_encode([
        'success' => true,
        'message' => 'File uploaded successfully',
        'filename' => $unique_filename,
        'original_name' => $file['name'],
        'size' => $file['size']
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to move uploaded file'
    ]);
}
?>
```

### How It Works Together

1. **User selects file**: The HTML file input captures the user's file selection
2. **JavaScript intercepts**: The form submission is prevented, and JavaScript takes over
3. **FormData creation**: The file is packaged into a FormData object that preserves its binary structure
4. **AJAX request**: XMLHttpRequest sends the file to the PHP script asynchronously
5. **PHP processing**: The server-side script validates, processes, and saves the file
6. **JSON response**: PHP returns a JSON response indicating success or failure
7. **UI update**: JavaScript receives the response and updates the page accordingly

---

## 6. Working with Images {#working-with-images}

### Image Validation and Processing

Images require special handling for validation, resizing, and optimization. Here's an enhanced PHP script for image uploads:

```php
<?php
header('Content-Type: application/json');

function validateImage($file) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB

    // Check file type
    if (!in_array($file['type'], $allowed_types)) {
        return ['valid' => false, 'message' => 'Invalid file type. Only JPEG, PNG, GIF, and WebP allowed.'];
    }

    // Check file size
    if ($file['size'] > $max_size) {
        return ['valid' => false, 'message' => 'File too large. Maximum size is 5MB.'];
    }

    // Verify it's actually an image
    $image_info = getimagesize($file['tmp_name']);
    if ($image_info === false) {
        return ['valid' => false, 'message' => 'File is not a valid image.'];
    }

    return ['valid' => true, 'info' => $image_info];
}

function resizeImage($source_path, $destination_path, $max_width, $max_height) {
    $image_info = getimagesize($source_path);
    $original_width = $image_info[0];
    $original_height = $image_info[1];
    $mime_type = $image_info['mime'];

    // Calculate new dimensions
    $ratio = min($max_width / $original_width, $max_height / $original_height);
    $new_width = intval($original_width * $ratio);
    $new_height = intval($original_height * $ratio);

    // Create image resource based on type
    switch ($mime_type) {
        case 'image/jpeg':
            $source_image = imagecreatefromjpeg($source_path);
            break;
        case 'image/png':
            $source_image = imagecreatefrompng($source_path);
            break;
        case 'image/gif':
            $source_image = imagecreatefromgif($source_path);
            break;
        default:
            return false;
    }

    // Create new image
    $new_image = imagecreatetruecolor($new_width, $new_height);

    // Preserve transparency for PNG and GIF
    if ($mime_type === 'image/png' || $mime_type === 'image/gif') {
        imagealphablending($new_image, false);
        imagesavealpha($new_image, true);
    }

    // Resize image
    imagecopyresampled($new_image, $source_image, 0, 0, 0, 0,
                      $new_width, $new_height, $original_width, $original_height);

    // Save resized image
    switch ($mime_type) {
        case 'image/jpeg':
            imagejpeg($new_image, $destination_path, 90);
            break;
        case 'image/png':
            imagepng($new_image, $destination_path);
            break;
        case 'image/gif':
            imagegif($new_image, $destination_path);
            break;
    }

    // Clean up memory
    imagedestroy($source_image);
    imagedestroy($new_image);

    return true;
}

// Main upload logic
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded']);
    exit;
}

$file = $_FILES['file'];
$validation = validateImage($file);

if (!$validation['valid']) {
    echo json_encode(['success' => false, 'message' => $validation['message']]);
    exit;
}

$upload_dir = 'uploads/images/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$unique_filename = uniqid() . '.' . $file_extension;
$target_path = $upload_dir . $unique_filename;

// Move and resize image
if (move_uploaded_file($file['tmp_name'], $target_path)) {
    // Create thumbnail
    $thumbnail_path = $upload_dir . 'thumb_' . $unique_filename;
    resizeImage($target_path, $thumbnail_path, 200, 200);

    echo json_encode([
        'success' => true,
        'message' => 'Image uploaded successfully',
        'filename' => $unique_filename,
        'thumbnail' => 'thumb_' . $unique_filename,
        'dimensions' => $validation['info'][0] . 'x' . $validation['info'][1]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save image']);
}
?>
```

### Image Preview with JavaScript

Add real-time image preview functionality:

```javascript
function previewImage(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();

    reader.onload = function (e) {
      const preview = document.getElementById("imagePreview");
      preview.innerHTML =
        '<img src="' +
        e.target.result +
        '" style="max-width: 300px; max-height: 300px;">';
    };

    reader.readAsDataURL(input.files[0]);
  }
}

// Add to file input
document.getElementById("fileInput").addEventListener("change", function () {
  previewImage(this);
});
```

### Understanding Image Processing Functions

#### getimagesize()

This function returns an array with image dimensions and type information. It's crucial for validation because it actually reads the file header, not just the file extension, making it more reliable for security.

#### GD Library Functions

PHP's GD library provides powerful image manipulation capabilities:

- `imagecreatefromjpeg()`, `imagecreatefrompng()`, `imagecreatefromgif()` - create image resources from files
- `imagecreatetruecolor()` - creates a new true color image canvas
- `imagecopyresampled()` - performs high-quality image resizing
- `imagejpeg()`, `imagepng()`, `imagegif()` - save images in different formats

---

## 7. Handling Different File Types {#file-types}

### MIME Type Detection

Different file types require different handling approaches. PHP provides several methods for detecting file types:

```php
<?php
function detectFileType($file_path) {
    // Method 1: Using finfo (most reliable)
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file_path);
    finfo_close($finfo);

    // Method 2: Using mime_content_type (if available)
    // $mime_type = mime_content_type($file_path);

    return $mime_type;
}

function getFileCategory($mime_type) {
    $categories = [
        'image' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
        'document' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
        'video' => ['video/mp4', 'video/avi', 'video/mov', 'video/wmv'],
        'audio' => ['audio/mp3', 'audio/wav', 'audio/ogg', 'audio/m4a']
    ];

    foreach ($categories as $category => $types) {
        if (in_array($mime_type, $types)) {
            return $category;
        }
    }

    return 'unknown';
}
?>
```

### PDF File Handling

PDF files require special consideration for security and processing:

```php
<?php
function processPDFUpload($file) {
    $upload_dir = 'uploads/documents/';

    // Validate PDF
    if ($file['type'] !== 'application/pdf') {
        return ['success' => false, 'message' => 'Only PDF files allowed'];
    }

    // Check file signature (PDF files start with %PDF)
    $handle = fopen($file['tmp_name'], 'rb');
    $header = fread($handle, 4);
    fclose($handle);

    if ($header !== '%PDF') {
        return ['success' => false, 'message' => 'Invalid PDF file'];
    }

    $unique_filename = uniqid() . '.pdf';
    $target_path = $upload_dir . $unique_filename;

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        // Get PDF info (requires additional libraries for full functionality)
        $file_size = formatBytes($file['size']);

        return [
            'success' => true,
            'filename' => $unique_filename,
            'size' => $file_size,
            'type' => 'PDF Document'
        ];
    }

    return ['success' => false, 'message' => 'Upload failed'];
}

function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }

    return round($bytes, $precision) . ' ' . $units[$i];
}
?>
```

### Video File Processing

Video files are typically large and may require special handling:

```php
<?php
function processVideoUpload($file) {
    $allowed_types = ['video/mp4', 'video/avi', 'video/mov', 'video/wmv'];
    $max_size = 100 * 1024 * 1024; // 100MB

    if (!in_array($file['type'], $allowed_types)) {
        return ['success' => false, 'message' => 'Video format not supported'];
    }

    if ($file['size'] > $max_size) {
        return ['success' => false, 'message' => 'Video file too large (max 100MB)'];
    }

    $upload_dir = 'uploads/videos/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $unique_filename = uniqid() . '.' . $file_extension;
    $target_path = $upload_dir . $unique_filename;

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        return [
            'success' => true,
            'filename' => $unique_filename,
            'size' => formatBytes($file['size']),
            'type' => 'Video File'
        ];
    }

    return ['success' => false, 'message' => 'Upload failed'];
}
?>
```

---

## 8. Security Considerations {#security}

### File Upload Security

File uploads present significant security risks. Here are essential security measures:

```php
<?php
class SecureFileUpload {
    private $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
    private $allowed_mime_types = [
        'image/jpeg', 'image/png', 'image/gif',
        'application/pdf', 'application/msword'
    ];
    private $max_file_size = 10 * 1024 * 1024; // 10MB

    public function validateFile($file) {
        $errors = [];

        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Upload error: ' . $this->getUploadError($file['error']);
        }

        // Validate file size
        if ($file['size'] > $this->max_file_size) {
            $errors[] = 'File too large';
        }

        // Validate file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowed_extensions)) {
            $errors[] = 'File type not allowed';
        }

        // Validate MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime_type, $this->allowed_mime_types)) {
            $errors[] = 'Invalid file type';
        }

        // Check for malicious content
        if ($this->containsMaliciousContent($file['tmp_name'])) {
            $errors[] = 'File contains potentially malicious content';
        }

        return empty($errors) ? ['valid' => true] : ['valid' => false, 'errors' => $errors];
    }

    private function containsMaliciousContent($file_path) {
        $dangerous_patterns = [
            '/<\?php/i',           // PHP code
            '/<script/i',          // JavaScript
            '/javascript:/i',      // JavaScript protocol
            '/vbscript:/i',        // VBScript
            '/<iframe/i',          // iframes
            '/eval\s*\(/i',        // eval function
            '/base64_decode/i'     // base64 decode
        ];

        $content = file_get_contents($file_path);

        foreach ($dangerous_patterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }

        return false;
    }

    private function getUploadError($error_code) {
        switch ($error_code) {
            case UPLOAD_ERR_INI_SIZE:
                return 'File exceeds upload_max_filesize';
            case UPLOAD_ERR_FORM_SIZE:
                return 'File exceeds MAX_FILE_SIZE';
            case UPLOAD_ERR_PARTIAL:
                return 'File partially uploaded';
            case UPLOAD_ERR_NO_FILE:
                return 'No file uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing temporary directory';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write to disk';
            case UPLOAD_ERR_EXTENSION:
                return 'Upload stopped by extension';
            default:
                return 'Unknown upload error';
        }
    }

    public function generateSecureFilename($original_name) {
        $extension = pathinfo($original_name, PATHINFO_EXTENSION);
        return uniqid() . '_' . time() . '.' . $extension;
    }
}
?>
```

### Input Sanitization

Always sanitize user inputs, especially filenames:

```php
<?php
function sanitizeFilename($filename) {
    // Remove directory traversal attempts
    $filename = basename($filename);

    // Remove potentially dangerous characters
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);

    // Limit filename length
    if (strlen($filename) > 100) {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $filename = substr($filename, 0, 96) . '.' . $extension;
    }

    return $filename;
}

function validateUserInput($input) {
    // Remove HTML tags
    $input = strip_tags($input);

    // Remove extra whitespace
    $input = trim($input);

    // Convert special characters to HTML entities
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

    return $input;
}
?>
```

---

## 9. Advanced Techniques {#advanced}

### Multiple File Upload

Handle multiple files efficiently with AJAX:

```javascript
function uploadMultipleFiles(files) {
  const totalFiles = files.length;
  let completedFiles = 0;

  for (let i = 0; i < files.length; i++) {
    uploadSingleFile(files[i], function (response) {
      completedFiles++;
      updateOverallProgress(completedFiles, totalFiles);

      if (completedFiles === totalFiles) {
        showCompletionMessage();
      }
    });
  }
}

function uploadSingleFile(file, callback) {
  const xhr = new XMLHttpRequest();
  const formData = new FormData();

  formData.append("file", file);

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      callback(response);
    }
  };

  xhr.open("POST", "upload.php");
  xhr.send(formData);
}
```

### Chunked Upload for Large Files

For very large files, implement chunked uploading:

```php
<?php
function handleChunkedUpload() {
    $chunk_index = $_POST['chunk_index'];
    $total_chunks = $_POST['total_chunks'];
    $filename = $_POST['filename'];

    $temp_dir = 'temp_chunks/';
    if (!is_dir($temp_dir)) {
        mkdir($temp_dir, 0777, true);
    }

    // Save current chunk
    $chunk_file = $temp_dir . $filename . '_chunk_' . $chunk_index;
    move_uploaded_file($_FILES['chunk']['tmp_name'], $chunk_file);

    // Check if all chunks are uploaded
    $uploaded_chunks = glob($temp_dir . $filename . '_chunk_*');

    if (count($uploaded_chunks) === (int)$total_chunks) {
        // Combine all chunks
        $final_file = 'uploads/' . $filename;
        $output = fopen($final_file, 'wb');

        for ($i = 0; $i < $total_chunks; $i++) {
            $chunk_path = $temp_dir . $filename . '_chunk_' . $i;
            $chunk_data = file_get_contents($chunk_path);
            fwrite($output, $chunk_data);
            unlink($chunk_path); // Delete chunk after combining
        }

        fclose($output);

        echo json_encode(['success' => true, 'message' => 'File assembled successfully']);
    } else {
        echo json_encode(['success' => true, 'message' => 'Chunk uploaded', 'chunk' => $chunk_index]);
    }
}
?>
```

### Database Integration

Store file metadata in a database:

```php
<?php
function saveFileMetadata($filename, $original_name, $file_size, $file_type) {
    $pdo = new PDO('mysql:host=localhost;dbname=your_database', $username, $password);

    $stmt = $pdo->prepare("INSERT INTO uploaded_files (filename, original_name, file_size, file_type, upload_date) VALUES (?, ?, ?, ?, NOW())");

    return $stmt->execute([$filename, $original_name, $file_size, $file_type]);
}

function getFileList() {
    $pdo = new PDO('mysql:host=localhost;dbname=your_database', $username, $password);

    $stmt = $pdo->query("SELECT * FROM uploaded_files ORDER BY upload_date DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
```

---

## 10. Troubleshooting Common Issues {#troubleshooting}

### Common Upload Problems and Solutions

#### Problem: Files Not Uploading

**Symptoms**: AJAX request completes but no file appears on server

**Solutions**:

1. Check PHP configuration: `upload_max_filesize` and `post_max_size`
2. Verify directory permissions (should be 755 or 777)
3. Ensure `enctype="multipart/form-data"` in form
4. Check for PHP errors with `error_reporting(E_ALL)`

#### Problem: Large Files Timing Out

**Symptoms**: Upload fails on large files but works for small ones

**Solutions**:

1. Increase `max_execution_time` in php.ini
2. Increase `memory_limit` if processing large files
3. Implement chunked upload for very large files
4. Use `set_time_limit(0)` in PHP script for unlimited execution time

#### Problem: AJAX Not Receiving Response

**Symptoms**: Upload works but JavaScript doesn't get response

**Solutions**:

1. Ensure PHP script outputs valid JSON
2. Check for PHP errors that might corrupt JSON output
3. Use `header('Content-Type: application/json')` in PHP
4. Verify AJAX success callback is properly handling response

### Debugging Techniques

Use these methods to identify issues:

```php
<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log upload attempts
function logUploadAttempt($file, $result) {
    $log_entry = date('Y-m-d H:i:s') . ' - ' . $file['name'] . ' - ' . ($result ? 'SUCCESS' : 'FAILED') . "\n";
    file_put_contents('upload_log.txt', $log_entry, FILE_APPEND);
}

// Check PHP configuration
function checkPHPConfig() {
    return [
        'file_uploads' => ini_get('file_uploads'),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
        'max_execution_time' => ini_get('max_execution_time'),
        'memory_limit' => ini_get('memory_limit')
    ];
}
?>
```

### Performance Optimization

Improve upload performance with these techniques:

```php
<?php
// Optimize file operations
function optimizedFileMove($source, $destination) {
    // Use rename() for same-partition moves (faster than copy)
    if (dirname($source) === dirname($destination)) {
        return rename($source, $destination);
    }

    // Fallback to standard move
    return move_uploaded_file($source, $destination);
}

// Implement file caching
function generateFileHash($file_path) {
    return md5_file($file_path);
}

// Compress images on upload
function compressImage($source, $destination, $quality = 85) {
    $info = getimagesize($source);

    if ($info['mime'] === 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
        return imagejpeg($image, $destination, $quality);
    }

    return false;
}
?>
```

---

## Conclusion

This tutorial covered the essential concepts for working with PHP and AJAX for file and media uploads. You've learned how to create secure, efficient upload systems that provide excellent user experience through real-time feedback and progress tracking.

Key takeaways include:

- Understanding the `$_FILES` superglobal and how PHP handles uploads
- Implementing secure file validation and processing
- Creating responsive AJAX upload interfaces
- Handling different file types with appropriate validation
- Implementing security measures to prevent malicious uploads
- Troubleshooting common issues and optimizing performance

Remember that file uploads are a critical security point in web applications. Always validate files thoroughly, sanitize user inputs, and implement proper access controls. Start with simple implementations and gradually add advanced features as your understanding grows.

Practice these concepts by building your own file upload system, experimenting with different file types, and implementing the security measures discussed. The combination of PHP's server-side processing power and AJAX's seamless user experience creates powerful web applications that users will appreciate.
