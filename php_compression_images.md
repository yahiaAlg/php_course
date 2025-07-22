# PHP Fundamentals: File Handling, Compression, and Media Processing

## Introduction to PHP File Operations

PHP provides powerful built-in capabilities for working with files, compression, and media handling. These features are essential for creating web applications that need to process uploads, manage documents, compress data for storage or transmission, and handle various media formats.

File handling in PHP revolves around the concept of file streams and resources. When you open a file, PHP creates a resource that represents that file connection, allowing you to read from or write to it. This resource-based approach ensures proper memory management and file system interaction.

## Basic File Operations

### Opening and Closing Files

The foundation of file handling starts with the `fopen()` function, which creates a connection to a file. This function requires two parameters: the file path and the mode in which you want to access the file.

```php
<?php
// Opening a file for reading
$file = fopen("example.txt", "r");

// Always check if the file opened successfully
if ($file) {
    echo "File opened successfully";
    fclose($file); // Always close files when done
} else {
    echo "Failed to open file";
}
?>
```

The mode parameter determines how you can interact with the file. Common modes include "r" for reading, "w" for writing (which overwrites existing content), "a" for appending, and "r+" for both reading and writing. Understanding these modes is crucial because using the wrong mode can lead to data loss or unexpected behavior.

### Reading File Content

PHP offers several approaches to read file content, each suited for different scenarios. The `file_get_contents()` function is the simplest way to read an entire file into a string variable.

```php
<?php
// Read entire file at once
$content = file_get_contents("data.txt");
if ($content !== false) {
    echo "File content: " . $content;
} else {
    echo "Could not read file";
}

// Reading line by line for large files
$file = fopen("large_file.txt", "r");
if ($file) {
    while (($line = fgets($file)) !== false) {
        echo "Line: " . trim($line) . "<br>";
    }
    fclose($file);
}
?>
```

The line-by-line approach using `fgets()` is particularly important when dealing with large files because it doesn't load the entire file into memory at once. This prevents memory exhaustion and makes your application more efficient.

### Writing to Files

Writing data to files involves similar principles but requires careful consideration of file modes and data formatting. The `file_put_contents()` function provides a convenient way to write data to a file in a single operation.

```php
<?php
$data = "Hello, World!\nThis is a new line.";

// Write data to file (overwrites existing content)
$bytes_written = file_put_contents("output.txt", $data);
if ($bytes_written !== false) {
    echo "Successfully wrote $bytes_written bytes";
}

// Append data to existing file
$additional_data = "\nAppended content";
file_put_contents("output.txt", $additional_data, FILE_APPEND);

// Writing using fopen for more control
$file = fopen("detailed_output.txt", "w");
if ($file) {
    fwrite($file, "First line\n");
    fwrite($file, "Second line\n");
    fclose($file);
}
?>
```

The `FILE_APPEND` flag is crucial when you want to add content to an existing file without losing the original data. Without this flag, `file_put_contents()` will overwrite the entire file.

## File Information and Management

### Getting File Information

PHP provides comprehensive functions to retrieve file metadata, which is essential for file management applications and security checks.

```php
<?php
$filename = "example.txt";

// Check if file exists before accessing it
if (file_exists($filename)) {
    echo "File size: " . filesize($filename) . " bytes<br>";
    echo "Last modified: " . date("Y-m-d H:i:s", filemtime($filename)) . "<br>";
    echo "Is readable: " . (is_readable($filename) ? "Yes" : "No") . "<br>";
    echo "Is writable: " . (is_writable($filename) ? "Yes" : "No") . "<br>";

    // Get file extension
    $path_info = pathinfo($filename);
    echo "Extension: " . $path_info['extension'] . "<br>";
    echo "Filename: " . $path_info['filename'] . "<br>";
    echo "Directory: " . $path_info['dirname'] . "<br>";
} else {
    echo "File does not exist";
}
?>
```

These functions are particularly important for security validation. Always check file permissions and existence before attempting operations, and use `pathinfo()` to safely extract file components without relying on string manipulation.

### Directory Operations

Working with directories is fundamental for organizing files and creating file management systems. PHP provides intuitive functions for directory manipulation.

```php
<?php
$directory = "uploads";

// Create directory if it doesn't exist
if (!is_dir($directory)) {
    if (mkdir($directory, 0755, true)) {
        echo "Directory created successfully";
    } else {
        echo "Failed to create directory";
    }
}

// List directory contents
if (is_dir($directory)) {
    $files = scandir($directory);
    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            echo "Found file: $file<br>";
        }
    }
}

// Using DirectoryIterator for more advanced operations
$iterator = new DirectoryIterator($directory);
foreach ($iterator as $fileinfo) {
    if (!$fileinfo->isDot()) {
        echo $fileinfo->getFilename() . " - Size: " . $fileinfo->getSize() . " bytes<br>";
    }
}
?>
```

The `mkdir()` function's third parameter enables recursive directory creation, allowing you to create nested directory structures in a single call. The `DirectoryIterator` class provides object-oriented access to directory contents with additional metadata.

## File Upload Handling

### Basic Upload Processing

File uploads are a common requirement in web applications, but they require careful security considerations and error handling. PHP's `$_FILES` superglobal contains information about uploaded files.

```php
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['upload'])) {
    $upload = $_FILES['upload'];

    // Check for upload errors
    if ($upload['error'] === UPLOAD_ERR_OK) {
        $temp_name = $upload['tmp_name'];
        $original_name = $upload['name'];
        $file_size = $upload['size'];

        // Validate file size (2MB limit)
        if ($file_size > 2 * 1024 * 1024) {
            echo "File too large. Maximum 2MB allowed.";
            exit;
        }

        // Validate file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($temp_name);

        if (in_array($file_type, $allowed_types)) {
            $destination = "uploads/" . basename($original_name);
            if (move_uploaded_file($temp_name, $destination)) {
                echo "File uploaded successfully to $destination";
            } else {
                echo "Failed to move uploaded file";
            }
        } else {
            echo "Invalid file type. Only JPEG, PNG, and GIF allowed.";
        }
    } else {
        echo "Upload error: " . $upload['error'];
    }
}
?>

<!-- HTML form for file upload -->
<form method="post" enctype="multipart/form-data">
    <input type="file" name="upload" accept="image/*">
    <button type="submit">Upload File</button>
</form>
```

Always use `move_uploaded_file()` instead of `copy()` or `rename()` for uploaded files. This function includes security checks to ensure the file was actually uploaded through PHP's upload mechanism and not through other means.

### Advanced Upload Validation

Security in file uploads extends beyond basic type checking. Implementing comprehensive validation protects your application from various attack vectors.

```php
<?php
function validateUpload($file) {
    $errors = [];

    // Check if file was uploaded
    if (!is_uploaded_file($file['tmp_name'])) {
        $errors[] = "File was not uploaded properly";
        return $errors;
    }

    // File size validation (5MB max)
    $max_size = 5 * 1024 * 1024;
    if ($file['size'] > $max_size) {
        $errors[] = "File size exceeds 5MB limit";
    }

    // MIME type validation
    $allowed_mimes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'application/pdf' => 'pdf'
    ];

    $detected_mime = mime_content_type($file['tmp_name']);
    if (!array_key_exists($detected_mime, $allowed_mimes)) {
        $errors[] = "File type not allowed";
    }

    // File extension validation
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($detected_mime && $allowed_mimes[$detected_mime] !== $file_extension) {
        $errors[] = "File extension doesn't match content type";
    }

    return $errors;
}

// Usage example
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['document'])) {
    $validation_errors = validateUpload($_FILES['document']);

    if (empty($validation_errors)) {
        $safe_filename = uniqid() . '_' . basename($_FILES['document']['name']);
        $destination = "secure_uploads/" . $safe_filename;

        if (move_uploaded_file($_FILES['document']['tmp_name'], $destination)) {
            echo "File uploaded successfully as: $safe_filename";
        }
    } else {
        foreach ($validation_errors as $error) {
            echo "Error: $error<br>";
        }
    }
}
?>
```

This validation approach checks both the MIME type detected by examining the file content and ensures it matches the file extension. Using `uniqid()` to generate unique filenames prevents conflicts and potential security issues from filename manipulation.

## Compression Techniques

### Working with ZIP Archives

ZIP compression is invaluable for reducing file sizes and bundling multiple files together. PHP's ZipArchive class provides comprehensive ZIP file manipulation capabilities.

```php
<?php
// Creating a ZIP archive
$zip = new ZipArchive();
$zip_filename = "archive.zip";

if ($zip->open($zip_filename, ZipArchive::CREATE) === TRUE) {
    // Add files to the archive
    $zip->addFile("document.txt", "docs/document.txt");
    $zip->addFile("image.jpg", "images/image.jpg");

    // Add content directly without a source file
    $zip->addFromString("readme.txt", "This is the readme content");

    // Add an entire directory
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator("source_folder"),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $file) {
        if (!$file->isDir()) {
            $file_path = $file->getRealPath();
            $relative_path = substr($file_path, strlen(realpath("source_folder")) + 1);
            $zip->addFile($file_path, $relative_path);
        }
    }

    echo "Archive created with " . $zip->numFiles . " files";
    $zip->close();
} else {
    echo "Cannot create ZIP archive";
}
?>
```

The `RecursiveIteratorIterator` combined with `RecursiveDirectoryIterator` provides an elegant way to traverse directory structures and add all files to the archive while maintaining the directory structure.

### Extracting ZIP Archives

Extracting ZIP files requires careful path validation to prevent directory traversal attacks and ensure files are extracted to safe locations.

```php
<?php
function extractZipSafely($zip_file, $extract_to) {
    $zip = new ZipArchive();

    if ($zip->open($zip_file) === TRUE) {
        // Validate extraction path
        $extract_to = realpath($extract_to);
        if (!$extract_to) {
            throw new Exception("Invalid extraction path");
        }

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);

            // Security check: prevent directory traversal
            if (strpos($filename, '../') !== false || strpos($filename, '..\\') !== false) {
                echo "Skipping potentially dangerous file: $filename<br>";
                continue;
            }

            $file_info = $zip->statIndex($i);
            echo "Extracting: " . $filename . " (" . $file_info['size'] . " bytes)<br>";
        }

        $result = $zip->extractTo($extract_to);
        $zip->close();

        return $result;
    } else {
        throw new Exception("Cannot open ZIP file");
    }
}

// Usage
try {
    if (extractZipSafely("archive.zip", "extracted_files/")) {
        echo "ZIP file extracted successfully";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
```

Always validate file paths within ZIP archives to prevent malicious archives from writing files outside the intended extraction directory. This security measure is crucial for protecting your server's file system.

### GZIP Compression

GZIP compression is particularly useful for compressing text data and reducing bandwidth usage. PHP provides both file-based and string-based GZIP operations.

```php
<?php
// Compress a file using GZIP
$original_file = "large_text_file.txt";
$compressed_file = "large_text_file.txt.gz";

// Read original file and compress
$original_data = file_get_contents($original_file);
$compressed_data = gzencode($original_data, 9); // 9 is maximum compression level

file_put_contents($compressed_file, $compressed_data);

echo "Original size: " . strlen($original_data) . " bytes<br>";
echo "Compressed size: " . strlen($compressed_data) . " bytes<br>";
echo "Compression ratio: " . round((1 - strlen($compressed_data) / strlen($original_data)) * 100, 2) . "%<br>";

// Decompress the file
$decompressed_data = gzdecode(file_get_contents($compressed_file));
echo "Decompressed successfully: " . (($decompressed_data === $original_data) ? "Yes" : "No");

// Working with GZIP file streams
$gz_file = gzopen($compressed_file, "rb");
if ($gz_file) {
    while (!gzeof($gz_file)) {
        $line = gzgets($gz_file);
        // Process each line
        echo "Line: " . trim($line) . "<br>";
    }
    gzclose($gz_file);
}
?>
```

The compression level parameter in `gzencode()` ranges from 1 (fastest, least compression) to 9 (slowest, best compression). Choose based on your priorities: processing speed versus file size reduction.

## Image Processing Fundamentals

### Basic Image Information

Before manipulating images, you need to gather information about them and ensure they're valid image files. PHP's image functions provide comprehensive image metadata access.

```php
<?php
function analyzeImage($image_path) {
    // Check if file exists and is readable
    if (!file_exists($image_path) || !is_readable($image_path)) {
        throw new Exception("Image file not found or not readable");
    }

    // Get image information
    $image_info = getimagesize($image_path);
    if ($image_info === false) {
        throw new Exception("Not a valid image file");
    }

    list($width, $height, $type, $attr) = $image_info;

    $image_types = [
        IMAGETYPE_GIF => 'GIF',
        IMAGETYPE_JPEG => 'JPEG',
        IMAGETYPE_PNG => 'PNG',
        IMAGETYPE_BMP => 'BMP',
        IMAGETYPE_WEBP => 'WEBP'
    ];

    $analysis = [
        'width' => $width,
        'height' => $height,
        'type' => $image_types[$type] ?? 'Unknown',
        'size' => filesize($image_path),
        'mime' => $image_info['mime'],
        'attributes' => $attr
    ];

    return $analysis;
}

// Example usage
try {
    $info = analyzeImage("sample.jpg");
    echo "Image Analysis:<br>";
    echo "Dimensions: {$info['width']} x {$info['height']}<br>";
    echo "Type: {$info['type']}<br>";
    echo "File size: " . number_format($info['size']) . " bytes<br>";
    echo "MIME type: {$info['mime']}<br>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
```

The `getimagesize()` function is crucial for validating images because it actually examines the image data rather than relying solely on file extensions, which can be misleading or manipulated.

### Image Resizing and Thumbnails

Creating thumbnails and resizing images is a common requirement for web applications. Proper resizing maintains aspect ratios and image quality while reducing file sizes.

```php
<?php
function createThumbnail($source_path, $destination_path, $max_width, $max_height, $quality = 85) {
    // Get original image information
    $image_info = getimagesize($source_path);
    if (!$image_info) {
        throw new Exception("Invalid image file");
    }

    list($orig_width, $orig_height, $image_type) = $image_info;

    // Calculate new dimensions maintaining aspect ratio
    $ratio = min($max_width / $orig_width, $max_height / $orig_height);
    $new_width = intval($orig_width * $ratio);
    $new_height = intval($orig_height * $ratio);

    // Create image resource from source
    switch ($image_type) {
        case IMAGETYPE_JPEG:
            $source_image = imagecreatefromjpeg($source_path);
            break;
        case IMAGETYPE_PNG:
            $source_image = imagecreatefrompng($source_path);
            break;
        case IMAGETYPE_GIF:
            $source_image = imagecreatefromgif($source_path);
            break;
        default:
            throw new Exception("Unsupported image type");
    }

    // Create new image with calculated dimensions
    $new_image = imagecreatetruecolor($new_width, $new_height);

    // Preserve transparency for PNG and GIF
    if ($image_type == IMAGETYPE_PNG || $image_type == IMAGETYPE_GIF) {
        imagealphablending($new_image, false);
        imagesavealpha($new_image, true);
        $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
        imagefill($new_image, 0, 0, $transparent);
    }

    // Resize the image
    imagecopyresampled($new_image, $source_image, 0, 0, 0, 0,
                      $new_width, $new_height, $orig_width, $orig_height);

    // Save the resized image
    $result = false;
    switch ($image_type) {
        case IMAGETYPE_JPEG:
            $result = imagejpeg($new_image, $destination_path, $quality);
            break;
        case IMAGETYPE_PNG:
            $result = imagepng($new_image, $destination_path);
            break;
        case IMAGETYPE_GIF:
            $result = imagegif($new_image, $destination_path);
            break;
    }

    // Clean up memory
    imagedestroy($source_image);
    imagedestroy($new_image);

    return $result;
}

// Example usage
try {
    if (createThumbnail("large_image.jpg", "thumbnail.jpg", 200, 200)) {
        echo "Thumbnail created successfully";
    }
} catch (Exception $e) {
    echo "Error creating thumbnail: " . $e->getMessage();
}
?>
```

The `imagecopyresampled()` function provides better quality than `imagecopyresized()` because it uses pixel interpolation. Always preserve transparency settings for PNG and GIF images to maintain their visual integrity.

### Image Format Conversion

Converting between image formats allows you to optimize images for different use cases. JPEG for photographs, PNG for images with transparency, and WebP for modern web applications.

```php
<?php
function convertImage($source_path, $destination_path, $target_format, $quality = 85) {
    $source_info = getimagesize($source_path);
    if (!$source_info) {
        throw new Exception("Invalid source image");
    }

    $source_type = $source_info[2];

    // Create source image resource
    switch ($source_type) {
        case IMAGETYPE_JPEG:
            $source_image = imagecreatefromjpeg($source_path);
            break;
        case IMAGETYPE_PNG:
            $source_image = imagecreatefrompng($source_path);
            break;
        case IMAGETYPE_GIF:
            $source_image = imagecreatefromgif($source_path);
            break;
        case IMAGETYPE_WEBP:
            $source_image = imagecreatefromwebp($source_path);
            break;
        default:
            throw new Exception("Unsupported source format");
    }

    // Convert and save in target format
    $result = false;
    switch (strtolower($target_format)) {
        case 'jpeg':
        case 'jpg':
            $result = imagejpeg($source_image, $destination_path, $quality);
            break;
        case 'png':
            // PNG compression level (0-9, where 9 is highest compression)
            $png_quality = 9 - intval($quality / 10);
            $result = imagepng($source_image, $destination_path, $png_quality);
            break;
        case 'gif':
            $result = imagegif($source_image, $destination_path);
            break;
        case 'webp':
            if (function_exists('imagewebp')) {
                $result = imagewebp($source_image, $destination_path, $quality);
            } else {
                throw new Exception("WebP support not available");
            }
            break;
        default:
            throw new Exception("Unsupported target format");
    }

    imagedestroy($source_image);
    return $result;
}

// Batch conversion example
$conversions = [
    ['source.png', 'output.jpg', 'jpeg'],
    ['photo.jpg', 'optimized.webp', 'webp'],
    ['logo.gif', 'logo.png', 'png']
];

foreach ($conversions as $conversion) {
    try {
        if (convertImage($conversion[0], $conversion[1], $conversion[2])) {
            echo "Converted {$conversion[0]} to {$conversion[1]}<br>";
        }
    } catch (Exception $e) {
        echo "Conversion failed: " . $e->getMessage() . "<br>";
    }
}
?>
```

WebP format typically provides 25-35% better compression than JPEG while maintaining similar quality. Always check if WebP support is available using `function_exists('imagewebp')` before attempting WebP operations.

## Advanced File Operations

### CSV File Processing

CSV files are ubiquitous in data exchange, and PHP provides specialized functions for parsing and generating CSV data efficiently and safely.

```php
<?php
function readCSVFile($filename, $has_header = true) {
    if (!file_exists($filename)) {
        throw new Exception("CSV file not found");
    }

    $data = [];
    $headers = [];

    if (($file = fopen($filename, "r")) !== FALSE) {
        $row_number = 0;

        while (($row = fgetcsv($file, 1000, ",")) !== FALSE) {
            $row_number++;

            if ($has_header && $row_number === 1) {
                $headers = $row;
                continue;
            }

            if ($has_header && !empty($headers)) {
                // Create associative array with headers as keys
                $row_data = [];
                for ($i = 0; $i < count($headers); $i++) {
                    $row_data[$headers[$i]] = isset($row[$i]) ? $row[$i] : '';
                }
                $data[] = $row_data;
            } else {
                $data[] = $row;
            }
        }
        fclose($file);
    }

    return ['headers' => $headers, 'data' => $data];
}

function writeCSVFile($filename, $data, $headers = null) {
    if (($file = fopen($filename, "w")) !== FALSE) {
        // Write headers if provided
        if ($headers) {
            fputcsv($file, $headers);
        }

        // Write data rows
        foreach ($data as $row) {
            if (is_array($row)) {
                fputcsv($file, $row);
            }
        }

        fclose($file);
        return true;
    }
    return false;
}

// Example usage
$sample_data = [
    ['Name', 'Email', 'Age'],
    ['John Doe', 'john@example.com', '30'],
    ['Jane Smith', 'jane@example.com', '25'],
    ['Bob Johnson', 'bob@example.com', '35']
];

// Write CSV file
if (writeCSVFile("users.csv", array_slice($sample_data, 1), $sample_data[0])) {
    echo "CSV file created successfully<br>";
}

// Read CSV file
try {
    $csv_result = readCSVFile("users.csv", true);
    echo "Read " . count($csv_result['data']) . " records<br>";

    foreach ($csv_result['data'] as $record) {
        echo "Name: {$record['Name']}, Email: {$record['Email']}<br>";
    }
} catch (Exception $e) {
    echo "Error reading CSV: " . $e->getMessage();
}
?>
```

The `fgetcsv()` and `fputcsv()` functions handle CSV parsing complexities like quoted fields, embedded commas, and line breaks within fields. They're much more reliable than manual string splitting.

### JSON File Operations

JSON has become the standard format for data exchange in web applications. PHP's JSON functions provide robust encoding and decoding capabilities with comprehensive error handling.

```php
<?php
function readJSONFile($filename) {
    if (!file_exists($filename)) {
        throw new Exception("JSON file not found");
    }

    $json_string = file_get_contents($filename);
    $data = json_decode($json_string, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("JSON decode error: " . json_last_error_msg());
    }

    return $data;
}

function writeJSONFile($filename, $data, $pretty_print = true) {
    $flags = JSON_UNESCAPED_UNICODE;
    if ($pretty_print) {
        $flags |= JSON_PRETTY_PRINT;
    }

    $json_string = json_encode($data, $flags);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("JSON encode error: " . json_last_error_msg());
    }

    return file_put_contents($filename, $json_string) !== false;
}

// Example: Configuration management
$config = [
    'database' => [
        'host' => 'localhost',
        'port' => 3306,
        'name' => 'myapp'
    ],
    'features' => [
        'debug_mode' => false,
        'cache_enabled' => true,
        'max_upload_size' => '10MB'
    ],
    'supported_languages' => ['en', 'es', 'fr', 'de']
];

// Save configuration
try {
    if (writeJSONFile("config.json", $config)) {
        echo "Configuration saved successfully<br>";
    }

    // Load and modify configuration
    $loaded_config = readJSONFile("config.json");
    $loaded_config['features']['debug_mode'] = true;
    $loaded_config['last_updated'] = date('Y-m-d H:i:s');

    writeJSONFile("config.json", $loaded_config);
    echo "Configuration updated<br>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
```

Always use the second parameter of `json_decode()` as `true` to get associative arrays instead of objects, unless you specifically need object notation. This makes data manipulation more straightforward in PHP.

## Security Considerations

### File Upload Security

Security in file handling cannot be overstated. Malicious files can compromise your entire server if not handled properly.

```php
<?php
class SecureFileUpload {
    private $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'txt'];
    private $allowed_mimes = [
        'image/jpeg', 'image/png', 'image/gif',
        'application/pdf', 'text/plain'
    ];
    private $max_file_size = 5242880; // 5MB
    private $upload_path = 'secure_uploads/';

    public function validateAndUpload($file) {
        $errors = [];

        // Basic upload checks
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Upload failed with error code: " . $file['error'];
            return $errors;
        }

        // File size check
        if ($file['size'] > $this->max_file_size) {
            $errors[] = "File too large. Maximum size: " .
                       number_format($this->max_file_size / 1024 / 1024, 1) . "MB";
        }

        // Extension validation
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($file_extension, $this->allowed_extensions)) {
            $errors[] = "File extension not allowed";
        }

        // MIME type validation
        $detected_mime = mime_content_type($file['tmp_name']);
        if (!in_array($detected_mime, $this->allowed_mimes)) {
            $errors[] = "File type not allowed";
        }

        // Additional security checks for images
        if (strpos($detected_mime, 'image/') === 0) {
            $image_info = getimagesize($file['tmp_name']);
            if ($image_info === false) {
                $errors[] = "Invalid image file";
            }
        }

        // If validation passes, move the file
        if (empty($errors)) {
            $safe_filename = $this->generateSafeFilename($file['name']);
            $destination = $this->upload_path . $safe_filename;

            if (!is_dir($this->upload_path)) {
                mkdir($this->upload_path, 0755, true);
            }

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                return ['success' => true, 'filename' => $safe_filename];
            } else {
                $errors[] = "Failed to move uploaded file";
            }
        }

        return $errors;
    }

    private function generateSafeFilename($original_name) {
        $extension = pathinfo($original_name, PATHINFO_EXTENSION);
        $base_name = pathinfo($original_name, PATHINFO_FILENAME);

        // Remove potentially dangerous characters
        $safe_base = preg_replace('/[^a-zA-Z0-9._-]/', '_', $base_name);
        $safe_base = substr($safe_base, 0, 50); // Limit length

        return uniqid() . '_' . $safe_base . '.' . $extension;
    }
}

// Usage example
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['upload'])) {
    $uploader = new SecureFileUpload();
    $result = $uploader->validateAndUpload($_FILES['upload']);

    if (isset($result['success'])) {
        echo "File uploaded successfully: " . $result['filename'];
    } else {
        echo "Upload errors:<br>";
        foreach ($result as $error) {
            echo "- $error<br>";
        }
    }
}
?>
```

This security approach implements multiple validation layers: file size limits, extension whitelisting, MIME type verification, and safe filename generation. Never trust user-supplied filenames or rely solely on client-side validation.

## Performance Optimization

### Efficient Large File Processing

When dealing with large files, memory management becomes crucial. Processing files in chunks prevents memory exhaustion and improves application responsiveness.

```php
<?php
function processLargeFile($filename, $chunk_size = 8192) {
    if (!file_exists($filename)) {
        throw new Exception("File not found");
    }

    $file = fopen($filename, 'rb');
    if (!$file) {
        throw new Exception("Cannot open file");
    }

    $total_size = filesize($filename);
    $processed = 0;

    echo "Processing file: $filename (" . number_format($total_size) . " bytes)<br>";

    while (!feof($file)) {
        $chunk = fread($file, $chunk_size);
        $processed += strlen($chunk);

        // Process the chunk (example: count characters)
        $char_count = strlen($chunk);

        // Show progress
        $progress = ($processed / $total_size) * 100;
        echo "Progress: " . number_format($progress, 1) . "% - Processed chunk: $char_count bytes<br>";

        // In real applications, you might:
        // - Parse CSV data chunk by chunk
        // - Transform data
        // - Write to another file
        // - Send data over network

        // Prevent timeout on large files
        if (function_exists('set_time_limit')) {
            set_time_limit(30);
        }
    }

    fclose($file);
    echo "File processing completed<br>";
}

// Stream copy for large file transfers
function copyLargeFile($source, $destination, $buffer_size = 65536) {
    $source_file = fopen($source, 'rb');
    $dest_file = fopen($destination, 'wb');

    if (!$source_file || !$dest_file) {
        throw new Exception("Cannot open files for copying");
    }

    $bytes_copied = 0;
    while (!feof($source_file)) {
        $buffer = fread($source_file, $buffer_size);
        fwrite($dest_file, $buffer);
        $bytes_copied += strlen($buffer);
    }

    fclose($source_file);
    fclose($dest_file);

    return $bytes_copied;
}

// Example usage
try {
    // Create a sample large file for testing
    $test_content = str_repeat("This is a line of text for testing large file processing.\n", 10000);
    file_put_contents("large_test_file.txt", $test_content);

    processLargeFile("large_test_file.txt", 1024);

    $copied_bytes = copyLargeFile("large_test_file.txt", "copied_large_file.txt");
    echo "Copied $copied_bytes bytes successfully<br>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
```

Chunk-based processing is essential for handling files larger than available memory. The chunk size should be balanced between memory usage and I/O efficiency - typically between 8KB and 64KB works well for most applications.

## Conclusion

PHP's file handling, compression, and media processing capabilities provide a robust foundation for building sophisticated web applications. The key to successful implementation lies in understanding the underlying concepts: resource management, security validation, error handling, and performance optimization.

Always validate file operations thoroughly, implement proper error handling, and consider security implications at every step. Use appropriate functions for specific tasks - for example, `fgetcsv()` for CSV files rather than manual parsing, and `move_uploaded_file()` for uploaded files rather than generic file operations.

Memory management becomes critical when working with large files. Implement chunk-based processing and streaming operations to ensure your applications remain responsive and don't exceed server memory limits. Regular practice with these concepts and functions will help you build reliable, secure, and efficient file processing systems in PHP.

Remember that file operations are often system-dependent, so always test your code across different environments and implement comprehensive error handling to gracefully manage unexpected situations.
