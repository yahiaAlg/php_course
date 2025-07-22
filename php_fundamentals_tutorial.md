# PHP Fundamentals: A Complete Beginner's Guide to Superglobals

## Table of Contents

1. [PHP Superglobals](#php-superglobals)
2. [PHP Regular Expressions (RegEx)](#php-regular-expressions-regex)

---

## PHP Superglobals

### Understanding the Concept: What Are Superglobals?

Imagine you're working in a large office building with multiple floors and departments. Normally, if you want to share information between different departments, you'd need to physically carry documents or make phone calls. But what if there was a magical intercom system that could instantly share information with any department, anywhere in the building, without you having to do anything special?

That's exactly what PHP superglobals are like. They're special variables that automatically exist everywhere in your PHP script, ready to be used at any time, in any function, class, or file, without any extra setup. Think of them as PHP's built-in messenger system that carries important information throughout your entire application.

The key difference between regular variables and superglobals is scope. Regular variables have limited visibility - they only exist within the specific area where they're created. Superglobals, however, have global scope, meaning they're visible and accessible from anywhere in your script. This is incredibly powerful because it eliminates the need to pass certain types of data around manually.

### Why Are Superglobals Important?

In web development, your PHP scripts need to interact with the outside world. They need to know what the user clicked on, what form data was submitted, what files were uploaded, and what's happening on the server. Superglobals are PHP's way of automatically collecting and organizing this information for you.

Without superglobals, you'd have to manually gather this information every time you needed it, which would be extremely tedious and error-prone. Instead, PHP automatically populates these special variables with relevant data, making them instantly available whenever you need them.

### The Complete Family of PHP Superglobals

PHP provides nine main superglobals, each serving a specific purpose in web development. Let's explore each one in detail, understanding not just how to use them, but why they exist and when you'd need them.

---

### $GLOBALS: The Master Container

The `$GLOBALS` superglobal is like a master filing cabinet that contains copies of all global variables in your script. To understand why this is useful, let's first understand the concept of variable scope.

#### Understanding Variable Scope

When you create a variable in PHP, it has a specific scope - meaning it's only accessible within certain parts of your code. Variables created outside of functions are global variables, while variables created inside functions are local variables. Here's where it gets tricky: local variables inside functions can't normally access global variables directly.

```php
<?php
// This is a global variable - it exists in the global scope
$userName = "Alice";
$userAge = 28;
$userEmail = "alice@example.com";

// This function can't directly access the global variables above
function displayUserInfo() {
    // This would cause an error because $userName doesn't exist here
    // echo "User: " . $userName; // ERROR!

    // But we can access them through $GLOBALS
    echo "User: " . $GLOBALS['userName'] . "\n";
    echo "Age: " . $GLOBALS['userAge'] . "\n";
    echo "Email: " . $GLOBALS['userEmail'] . "\n";
}

displayUserInfo();
?>
```

#### Why $GLOBALS Exists

The `$GLOBALS` superglobal solves a fundamental problem in programming: how do you access global data from within functions without explicitly passing it as parameters? While passing parameters is often the better approach, there are times when you need quick access to global data, and `$GLOBALS` provides that access.

Think of `$GLOBALS` as a special window that lets you peek into the global scope from anywhere in your code. The variable names become keys in this associative array, but without the dollar sign.

```php
<?php
// Global configuration variables
$databaseHost = "localhost";
$databaseName = "myapp";
$appVersion = "1.0.0";
$debugMode = true;

// Function that needs access to configuration
function getDatabaseConnection() {
    // Using $GLOBALS to access configuration without passing parameters
    $host = $GLOBALS['databaseHost'];
    $dbname = $GLOBALS['databaseName'];

    echo "Connecting to database: {$dbname} on {$host}\n";

    // In a real application, you'd create a database connection here
    return "Database connection established";
}

// Function that modifies global variables
function toggleDebugMode() {
    // We can also modify global variables through $GLOBALS
    $GLOBALS['debugMode'] = !$GLOBALS['debugMode'];

    $status = $GLOBALS['debugMode'] ? "enabled" : "disabled";
    echo "Debug mode is now {$status}\n";
}

// Function that lists all global variables
function listGlobalVariables() {
    echo "All global variables:\n";
    foreach ($GLOBALS as $key => $value) {
        // Skip the $GLOBALS variable itself to avoid infinite recursion
        if ($key !== 'GLOBALS') {
            echo "  {$key} = {$value}\n";
        }
    }
}

// Test the functions
echo getDatabaseConnection() . "\n";
toggleDebugMode();
listGlobalVariables();
?>
```

#### Important Considerations with $GLOBALS

While `$GLOBALS` is powerful, it should be used judiciously. Overusing global variables can make your code harder to understand and maintain. It's generally better to pass data as function parameters when possible, as this makes your functions more predictable and easier to test.

However, `$GLOBALS` is particularly useful for configuration settings, debugging information, or when working with legacy code that relies heavily on global variables.

---

### $\_SERVER: Your Window Into the Server Environment

The `$_SERVER` superglobal is like a detailed information panel that tells you everything about the current request and the server environment. When a user visits your website, their browser sends a wealth of information along with the request, and the web server adds even more details about the environment. All of this information gets automatically organized into the `$_SERVER` array.

#### Understanding Web Requests

Before diving into `$_SERVER`, it's important to understand what happens when someone visits your website. The user's browser sends an HTTP request to your web server, which includes information about what page they want, what browser they're using, where they came from, and much more. The server then adds information about itself and the environment, creating a complete picture of the request context.

```php
<?php
// Let's create a comprehensive server information display
function displayServerInfo() {
    echo "=== COMPLETE SERVER INFORMATION ===\n\n";

    // 1. REQUEST INFORMATION
    echo "REQUEST DETAILS:\n";
    echo "---------------\n";

    // The HTTP method used (GET, POST, PUT, DELETE, etc.)
    echo "Request Method: " . $_SERVER['REQUEST_METHOD'] . "\n";

    // The URL path that was requested
    echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";

    // The complete URL that was requested
    $fullUrl = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' .
               $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    echo "Full URL: " . $fullUrl . "\n";

    // The HTTP protocol version being used
    echo "Protocol: " . $_SERVER['SERVER_PROTOCOL'] . "\n\n";

    // 2. SERVER INFORMATION
    echo "SERVER DETAILS:\n";
    echo "---------------\n";

    // The server's hostname
    echo "Server Name: " . $_SERVER['SERVER_NAME'] . "\n";

    // The port number the server is listening on
    echo "Server Port: " . $_SERVER['SERVER_PORT'] . "\n";

    // The server software being used (Apache, Nginx, etc.)
    echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "\n";

    // The document root directory
    echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n\n";

    // 3. SCRIPT INFORMATION
    echo "SCRIPT DETAILS:\n";
    echo "---------------\n";

    // The path to the current script
    echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "\n";

    // The absolute path to the current script
    echo "Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "\n";

    // The query string (everything after the ? in the URL)
    echo "Query String: " . (isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : 'None') . "\n\n";

    // 4. CLIENT INFORMATION
    echo "CLIENT DETAILS:\n";
    echo "---------------\n";

    // The IP address of the client
    echo "Client IP: " . $_SERVER['REMOTE_ADDR'] . "\n";

    // The user agent string (browser information)
    echo "User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";

    // The page that linked to this page (if any)
    echo "Referer: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Direct access') . "\n\n";

    // 5. SECURITY INFORMATION
    echo "SECURITY DETAILS:\n";
    echo "-----------------\n";

    // Check if HTTPS is being used
    $isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    echo "HTTPS Enabled: " . ($isSecure ? 'Yes' : 'No') . "\n";

    // Check if the request came from the same server
    $isSameHost = $_SERVER['HTTP_HOST'] === $_SERVER['SERVER_NAME'];
    echo "Same Host Request: " . ($isSameHost ? 'Yes' : 'No') . "\n";
}

// Function to safely retrieve server variables
function getServerVar($key, $default = 'Not Available') {
    /*
    This function provides a safe way to access $_SERVER variables.
    Sometimes certain variables might not be set, which would cause
    errors if we try to access them directly.
    */
    return isset($_SERVER[$key]) ? $_SERVER[$key] : $default;
}

// Display all the information
displayServerInfo();

// Example of using the helper function
echo "\n=== USING HELPER FUNCTION ===\n";
echo "Accept Language: " . getServerVar('HTTP_ACCEPT_LANGUAGE', 'Not specified') . "\n";
echo "Accept Encoding: " . getServerVar('HTTP_ACCEPT_ENCODING', 'Not specified') . "\n";
echo "Connection Type: " . getServerVar('HTTP_CONNECTION', 'Not specified') . "\n";
?>
```

#### Practical Applications of $\_SERVER

Understanding `$_SERVER` is crucial for building robust web applications. Here are some common use cases:

```php
<?php
// 1. Building absolute URLs
function buildAbsoluteUrl($relativePath) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    return $protocol . '://' . $host . $relativePath;
}

// 2. Detecting mobile devices
function isMobileDevice() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $mobileKeywords = ['Mobile', 'Android', 'iPhone', 'iPad', 'Windows Phone'];

    foreach ($mobileKeywords as $keyword) {
        if (stripos($userAgent, $keyword) !== false) {
            return true;
        }
    }
    return false;
}

// 3. Logging request information
function logRequest() {
    $logEntry = date('Y-m-d H:i:s') . ' - ' .
                $_SERVER['REMOTE_ADDR'] . ' - ' .
                $_SERVER['REQUEST_METHOD'] . ' - ' .
                $_SERVER['REQUEST_URI'] . ' - ' .
                $_SERVER['HTTP_USER_AGENT'];

    // In a real application, you'd write this to a log file
    echo "Log Entry: " . $logEntry . "\n";
}

// 4. Redirecting users
function redirectTo($url) {
    // Use $_SERVER to build proper redirect headers
    header('Location: ' . $url);
    exit();
}

// 5. Checking request methods
function handleRequest() {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            echo "Handling GET request\n";
            break;
        case 'POST':
            echo "Handling POST request\n";
            break;
        case 'PUT':
            echo "Handling PUT request\n";
            break;
        case 'DELETE':
            echo "Handling DELETE request\n";
            break;
        default:
            echo "Unsupported request method: " . $_SERVER['REQUEST_METHOD'] . "\n";
    }
}

// Test the functions
echo "Absolute URL: " . buildAbsoluteUrl('/about') . "\n";
echo "Mobile Device: " . (isMobileDevice() ? 'Yes' : 'No') . "\n";
logRequest();
handleRequest();
?>
```

---

### $\_GET: Capturing URL Parameters

The `$_GET` superglobal captures data that's sent through the URL itself. When you see a URL like `https://example.com/search?q=php&category=tutorial`, everything after the question mark is called the query string, and this data becomes available through `$_GET`.

#### Understanding Query Strings

Query strings are a way to pass data to your PHP script through the URL. They're called "GET" parameters because they're typically sent with HTTP GET requests. The format is straightforward: after the question mark, you have key-value pairs separated by ampersands.

For example: `?name=John&age=30&city=NewYork` would create three `$_GET` variables:

- `$_GET['name']` would contain "John"
- `$_GET['age']` would contain "30"
- `$_GET['city']` would contain "NewYork"

```php
<?php
// Let's create a comprehensive example of working with GET parameters
echo "=== WORKING WITH GET PARAMETERS ===\n\n";

// First, let's understand what GET parameters we received
if (!empty($_GET)) {
    echo "GET parameters received:\n";
    echo "-----------------------\n";

    foreach ($_GET as $key => $value) {
        echo $key . " = " . $value . "\n";
    }
    echo "\n";
} else {
    echo "No GET parameters received.\n";
    echo "Try accessing this script with parameters like: ?name=John&age=30\n\n";
}

// Safe way to access GET parameters
function getParam($key, $defaultValue = null, $type = 'string') {
    /*
    This function safely retrieves GET parameters with several benefits:
    1. It checks if the parameter exists before accessing it
    2. It provides a default value if the parameter doesn't exist
    3. It can convert the parameter to the appropriate data type
    4. It helps prevent errors from undefined variables
    */

    if (!isset($_GET[$key])) {
        return $defaultValue;
    }

    $value = $_GET[$key];

    // Convert to appropriate type
    switch ($type) {
        case 'int':
        case 'integer':
            return (int)$value;
        case 'float':
        case 'double':
            return (float)$value;
        case 'bool':
        case 'boolean':
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        case 'string':
        default:
            return (string)$value;
    }
}

// Example usage of the safe parameter function
$userName = getParam('name', 'Guest');
$userAge = getParam('age', 0, 'int');
$isActive = getParam('active', false, 'bool');
$score = getParam('score', 0.0, 'float');

echo "Processed parameters:\n";
echo "--------------------\n";
echo "Name: " . $userName . " (type: " . gettype($userName) . ")\n";
echo "Age: " . $userAge . " (type: " . gettype($userAge) . ")\n";
echo "Active: " . ($isActive ? 'true' : 'false') . " (type: " . gettype($isActive) . ")\n";
echo "Score: " . $score . " (type: " . gettype($score) . ")\n\n";

// Building a search functionality
function performSearch() {
    $query = getParam('q', '');
    $category = getParam('category', 'all');
    $sortBy = getParam('sort', 'relevance');
    $page = getParam('page', 1, 'int');

    echo "SEARCH FUNCTIONALITY:\n";
    echo "--------------------\n";

    if (empty($query)) {
        echo "No search query provided. Please add ?q=your_search_term\n";
        return;
    }

    echo "Search Query: " . htmlspecialchars($query) . "\n";
    echo "Category: " . htmlspecialchars($category) . "\n";
    echo "Sort By: " . htmlspecialchars($sortBy) . "\n";
    echo "Page: " . $page . "\n";

    // In a real application, you'd perform the actual search here
    echo "Searching for '" . htmlspecialchars($query) . "' in category '" . htmlspecialchars($category) . "'...\n";
    echo "Results would be displayed here.\n";
}

// Pagination example
function displayPagination() {
    $currentPage = getParam('page', 1, 'int');
    $totalPages = 10; // This would come from your database in a real app

    echo "\nPAGINATION:\n";
    echo "-----------\n";

    echo "Current Page: " . $currentPage . " of " . $totalPages . "\n";

    // Generate pagination links
    if ($currentPage > 1) {
        $prevPage = $currentPage - 1;
        echo "Previous Page: ?page=" . $prevPage . "\n";
    }

    if ($currentPage < $totalPages) {
        $nextPage = $currentPage + 1;
        echo "Next Page: ?page=" . $nextPage . "\n";
    }
}

// Input validation and sanitization
function validateAndSanitizeInput() {
    echo "\nINPUT VALIDATION:\n";
    echo "----------------\n";

    $email = getParam('email', '');
    $age = getParam('age', 0, 'int');
    $website = getParam('website', '');

    // Validate email
    if (!empty($email)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Valid email: " . htmlspecialchars($email) . "\n";
        } else {
            echo "Invalid email format: " . htmlspecialchars($email) . "\n";
        }
    }

    // Validate age
    if ($age > 0) {
        if ($age >= 13 && $age <= 120) {
            echo "Valid age: " . $age . "\n";
        } else {
            echo "Age must be between 13 and 120\n";
        }
    }

    // Validate website URL
    if (!empty($website)) {
        if (filter_var($website, FILTER_VALIDATE_URL)) {
            echo "Valid website: " . htmlspecialchars($website) . "\n";
        } else {
            echo "Invalid website URL: " . htmlspecialchars($website) . "\n";
        }
    }
}

// Run the examples
performSearch();
displayPagination();
validateAndSanitizeInput();

// Security considerations
echo "\n=== SECURITY REMINDERS ===\n";
echo "1. Always validate and sanitize GET parameters\n";
echo "2. Use htmlspecialchars() when displaying user input\n";
echo "3. Never trust user input - always validate\n";
echo "4. Consider using filter_var() for validation\n";
echo "5. Be aware that GET parameters are visible in URLs\n";
?>
```

#### When to Use $\_GET

GET parameters are perfect for:

- Search queries
- Pagination
- Filtering and sorting
- Navigation parameters
- Any data that should be bookmarkable

However, never use GET for:

- Sensitive information (passwords, personal data)
- Large amounts of data
- Data that modifies the server state

---

### $\_POST: Handling Form Submissions

The `$_POST` superglobal is your primary tool for handling form submissions and receiving data that users have entered into web forms. Unlike GET parameters, POST data is not visible in the URL, making it suitable for sensitive information and larger amounts of data.

#### Understanding HTTP POST Requests

When a user fills out a form and clicks submit, their browser packages up all the form data and sends it to your server using an HTTP POST request. This data travels in the request body rather than the URL, which makes it more secure and allows for larger amounts of data to be transmitted.

```php
<?php
// Let's create a comprehensive form handling system
echo "=== FORM HANDLING WITH POST DATA ===\n\n";

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Form was submitted via POST method\n";
    echo "Processing form data...\n\n";

    // Display all POST data for debugging
    if (!empty($_POST)) {
        echo "POST data received:\n";
        echo "-------------------\n";
        foreach ($_POST as $key => $value) {
            if (is_array($value)) {
                echo $key . " = [" . implode(", ", $value) . "]\n";
            } else {
                echo $key . " = " . $value . "\n";
            }
        }
        echo "\n";
    }

    // Process user registration form
    processUserRegistration();

} else {
    echo "No POST data received. This page expects form submissions.\n";
    displaySampleForm();
}

function processUserRegistration() {
    echo "USER REGISTRATION PROCESSING:\n";
    echo "-----------------------------\n";

    // Helper function to safely get POST data
    function getPostData($key, $defaultValue = '', $sanitize = true) {
        if (!isset($_POST[$key])) {
            return $defaultValue;
        }

        $value = $_POST[$key];

        // Sanitize the input if requested
        if ($sanitize && is_string($value)) {
            $value = trim($value); // Remove whitespace
            $value = stripslashes($value); // Remove backslashes
            $value = htmlspecialchars($value); // Convert special characters
        }

        return $value;
    }

    // Get form data
    $firstName = getPostData('first_name');
    $lastName = getPostData('last_name');
    $email = getPostData('email');
    $password = getPostData('password');
    $confirmPassword = getPostData('confirm_password');
    $age = getPostData('age');
    $gender = getPostData('gender');
    $interests = isset($_POST['interests']) ? $_POST['interests'] : [];
    $newsletter = isset($_POST['newsletter']) ? true : false;
    $terms = isset($_POST['terms']) ? true : false;

    // Validation array to store errors
    $errors = [];

    // Validate required fields
    if (empty($firstName)) {
        $errors[] = "First name is required";
    }

    if (empty($lastName)) {
        $errors[] = "Last name is required";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match";
    }

    if (empty($age) || !is_numeric($age) || $age < 13 || $age > 120) {
        $errors[] = "Please enter a valid age between 13 and 120";
    }

    if (!$terms) {
        $errors[] = "You must accept the terms and conditions";
    }

    // Display results
    if (empty($errors)) {
        echo "Registration successful!\n";
        echo "User Details:\n";
        echo "Name: " . $firstName . " " . $lastName . "\n";
        echo "Email: " . $email . "\n";
        echo "Age: " . $age . "\n";
        echo "Gender: " . $gender . "\n";
        echo "Interests: " . implode(", ", $interests) . "\n";
        echo "Newsletter: " . ($newsletter ? "Yes" : "No") . "\n";

        // In a real application, you would:
        // 1. Hash the password
        // 2. Store user data in a database
        // 3. Send confirmation email
        // 4. Create user session

    } else {
        echo "Registration failed. Please fix the following errors:\n";
        foreach ($errors as $error) {
            echo "- " . $error . "\n";
        }
    }
}

function displaySampleForm() {
    echo "\n=== SAMPLE REGISTRATION FORM ===\n";
    echo "Here's what a registration form might look like:\n\n";

    // Note: In a real application, this would be HTML
    echo '<form method="POST" action="">' . "\n";
    echo '    <input type="text" name="first_name" placeholder="First Name" required>' . "\n";
    echo '    <input type="text" name="last_name" placeholder="Last Name" required>' . "\n";
    echo '    <input type="email" name="email" placeholder="Email" required>' . "\n";
    echo '    <input type="password" name="password" placeholder="Password" required>' . "\n";
    echo '    <input type="password" name="confirm_password" placeholder="Confirm Password" required>' . "\n";
    echo '    <input type="number" name="age" placeholder="Age" min="13" max="120" required>' . "\n";
    echo '    ' . "\n";
    echo '    <select name="gender">' . "\n";
    echo '        <option value="">Select Gender</option>' . "\n";
    echo '        <option value="male">Male</option>' . "\n";
    echo '        <option value="female">Female</option>' . "\n";
    echo '        <option value="other">Other</option>' . "\n";
    echo '    </select>' . "\n";
    echo '    ' . "\n";
    echo '    <input type="checkbox" name="interests[]" value="sports"> Sports' . "\n";
    echo '    <input type="checkbox" name="interests[]" value="music"> Music' . "\n";
    echo '    <input type="checkbox" name="interests[]" value="reading"> Reading' . "\n";
    echo '    <input type="checkbox" name="interests[]" value="travel"> Travel' . "\n";
    echo '    ' . "\n";
    echo '    <input type="checkbox" name="newsletter"> Subscribe to newsletter' . "\n";
    echo '    <input type="checkbox" name="terms" required> I accept the terms and conditions' . "\n";
    echo '    ' . "\n";
    echo '    <input type="submit" value="Register">' . "\n";
    echo '</form>' . "\n";
}

// Advanced POST handling techniques
function demonstrateAdvancedTechniques() {
    echo "\n=== ADVANCED POST HANDLING TECHNIQUES ===\n";

    // 1. Handling file uploads in POST (files come through $_FILES)
    echo "1. File uploads are handled through \$_FILES, not \$_POST\n";

    // 2. Handling JSON POST data
    echo "2. For JSON POST data, use: json_decode(file_get_contents('php://input'), true)\n";

    // 3. Handling large POST data
    echo "3. Check post_max_size and max_input_vars in php.ini for large forms\n";

    // 4. CSRF protection
    echo "4. Always implement CSRF protection for POST forms\n";

    // 5. Content-Type handling
    echo "5. Different content types require different handling approaches\n";
}

demonstrateAdvancedTechniques();
?>
```

#### Understanding Different Form Input Types

Different HTML form elements send data in different ways through POST:

```php
<?php
// Demonstration of how different form elements appear in $_POST
function demonstrateFormElements() {
    echo "=== HOW FORM ELEMENTS APPEAR IN POST DATA ===\n\n";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Text inputs (text, email, password, etc.)
        echo "Text inputs:\n";
        echo "- Regular text: " . ($_POST['text_input'] ?? 'Not provided') . "\n";
        echo "- Email: " . ($_POST['email_input'] ?? 'Not provided') . "\n";
        echo "- Password: " . (isset($_POST['password_input']) ? '[HIDDEN]' : 'Not provided') . "\n\n";

        // Select dropdowns
        echo "Select dropdown:\n";
        echo "- Selected option: " . ($_POST['select_input'] ?? 'Not selected') . "\n\n";

        // Radio buttons (only one can be selected)
        echo "Radio buttons:\n";
        echo "- Selected radio: " . ($_POST['radio_input'] ?? 'None selected') . "\n\n";

        // Checkboxes (can be multiple)
        echo "Checkboxes:\n";
        if (isset($_POST['checkbox_single'])) {
            echo "- Single checkbox: Checked\n";
        } else {
            echo "- Single checkbox: Not checked\n";
        }

        if (isset($_POST['checkbox_multiple'])) {
            echo "- Multiple checkboxes: " . implode(", ", $_POST['checkbox_multiple']) . "\n";
        } else {
            echo "- Multiple checkboxes: None selected\n";
        }
        echo "\n";

        // Textarea
        echo "Textarea:\n";
        echo "- Content: " . ($_POST['textarea_input'] ?? 'Empty') . "\n\n";

        // Hidden inputs
        echo "Hidden inputs:\n";
        echo "- Hidden value: " . ($_POST['hidden_input'] ?? 'Not present') . "\n\n";

        // Number inputs
        echo "Number inputs:\n";
        echo "- Number: " . ($_POST['number_input'] ?? 'Not provided') . "\n\n";

        // Date inputs
        echo "Date inputs:\n";
        echo "- Date: " . ($_POST['date_input'] ?? 'Not provided') . "\n\n";

    } else {
        echo "Submit the form to see how different elements appear in POST data.\n";
    }
}

demonstrateFormElements();
?>
```

---

### $\_FILES: Handling File Uploads

The `$_FILES` superglobal is specifically designed to handle file uploads from web forms. When users upload files through your website, PHP automatically processes these files and makes information about them available through `$_FILES`.

#### Understanding File Upload Process

When a user selects a file and submits a form, several things happen:

1. The browser reads the file and includes its contents in the HTTP request
2. The web server receives the file and stores it in a temporary location
3. PHP creates an entry in `$_FILES` with information about the uploaded file
4. Your PHP script processes the file information and decides what to do with it

```php
<?php
// Comprehensive file upload handling
echo "=== FILE UPLOAD HANDLING ===\n\n";

// Check if files were uploaded
if (!empty($_FILES)) {
    echo "Files uploaded! Processing...\n\n";

    // Display information about each uploaded file
    foreach ($_FILES as $inputName => $fileInfo) {
        echo "Input field name: " . $inputName . "\n";
        echo "File details:\n";
        echo "  Original name: " . $fileInfo['name'] . "\n";
        echo "  MIME type: " . $fileInfo['type'] . "\n";
        echo "  File size: " . $fileInfo['size'] . " bytes\n";
        echo "  Temporary location: " . $fileInfo['tmp_name'] . "\n";
        echo "  Upload error code: " . $fileInfo['error'] . "\n\n";
    }

    // Process a specific file upload
    processFileUpload();

} else {
    echo "No files uploaded. To test file uploads, create an HTML form like this:\n\n";
    displayFileUploadForm();
}

function processFileUpload() {
    echo "PROCESSING FILE UPLOAD:\n";
    echo "----------------------\n";

    // Let's assume we're working with a file input named 'user_file'
    if (isset($_FILES['user_file'])) {
        $file = $_FILES['user_file'];

        // First, let's understand the structure of a file upload
        echo "Understanding the file structure:\n";
        echo "- Name: " . $file['name'] . " (Original filename from user's computer)\n";
        echo "- Type: " . $file['type'] . " (MIME type reported by browser)\n";
        echo "- Size: " . $file['size'] . " bytes (File size in bytes)\n";
        echo "- Tmp_name: " . $file['tmp_name'] . " (Temporary file location on server)\n";
        echo "- Error: " . $file['error'] . " (Error code - 0 means success)\n\n";

        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            echo "Upload error occurred: " . getUploadErrorMessage($file['error']) . "\n";
            return;
        }

        // Validate the file
        if (validateUploadedFile($file)) {
            echo "File validation passed!\n";

            // Move the file to permanent location
            $uploadDir = 'uploads/';
            $fileName = generateSafeFileName($file['name']);
            $destination = $uploadDir . $fileName;

            // In a real application, ensure the upload directory exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                echo "File successfully uploaded to: " . $destination . "\n";

                // Get additional file information
                displayFileInfo($destination);
            } else {
                echo "Failed to move uploaded file to destination.\n";
            }
        } else {
            echo "File validation failed. Upload rejected.\n";
        }
    } else {
        echo "No file found with the name 'user_file'\n";
    }
}

function getUploadErrorMessage($errorCode) {
    /*
    PHP provides specific error codes for file upload problems.
    Understanding these codes helps you provide better error messages to users.
    */
    switch ($errorCode) {
        case UPLOAD_ERR_INI_SIZE:
            return "File exceeds the upload_max_filesize directive in php.ini";
        case UPLOAD_ERR_FORM_SIZE:
            return "File exceeds the MAX_FILE_SIZE directive in the HTML form";
        case UPLOAD_ERR_PARTIAL:
            return "File was only partially uploaded";
        case UPLOAD_ERR_NO_FILE:
            return "No file was uploaded";
        case UPLOAD_ERR_NO_TMP_DIR:
            return "Missing a temporary folder";
        case UPLOAD_ERR_CANT_WRITE:
            return "Failed to write file to disk";
        case UPLOAD_ERR_EXTENSION:
            return "A PHP extension stopped the file upload";
        default:
            return "Unknown upload error";
    }
}

function validateUploadedFile($file) {
    /*
    File validation is crucial for security and functionality.
    We need to check multiple aspects of the uploaded file.
    */

    // Check if file actually exists
    if (!file_exists($file['tmp_name'])) {
        echo "Validation failed: File doesn't exist in temporary location\n";
        return false;
    }

    // Check file size (example: max 5MB)
    $maxFileSize = 5 * 1024 * 1024; // 5MB in bytes
    if ($file['size'] > $maxFileSize) {
        echo "Validation failed: File too large (max 5MB allowed)\n";
        return false;
    }

    // Check if file is empty
    if ($file['size'] == 0) {
        echo "Validation failed: File is empty\n";
        return false;
    }

    // Validate file type by extension
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt'];
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedExtensions)) {
        echo "Validation failed: File type not allowed (." . $fileExtension . ")\n";
        return false;
    }

    // Validate MIME type (more secure than just checking extension)
    $allowedMimeTypes = [
        'image/jpeg', 'image/png', 'image/gif',
        'application/pdf', 'text/plain',
        'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    $fileMimeType = mime_content_type($file['tmp_name']);
    if (!in_array($fileMimeType, $allowedMimeTypes)) {
        echo "Validation failed: MIME type not allowed (" . $fileMimeType . ")\n";
        return false;
    }

    // Additional security check: ensure it's actually an uploaded file
    if (!is_uploaded_file($file['tmp_name'])) {
        echo "Validation failed: Security check failed\n";
        return false;
    }

    return true;
}

function generateSafeFileName($originalName) {
    /*
    Creating safe filenames prevents various security issues and ensures
    the file can be stored and accessed reliably across different systems.
    */

    // Get the file extension
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    // Get the base name without extension
    $baseName = pathinfo($originalName, PATHINFO_FILENAME);

    // Remove dangerous characters and spaces
    $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $baseName);

    // Ensure the name isn't too long
    $safeName = substr($safeName, 0, 50);

    // Add timestamp to prevent naming conflicts
    $timestamp = date('Y-m-d_H-i-s');

    // Generate a unique identifier
    $uniqueId = uniqid();

    return $safeName . '_' . $timestamp . '_' . $uniqueId . '.' . $extension;
}

function displayFileInfo($filePath) {
    echo "\nFile Information:\n";
    echo "----------------\n";
    echo "File path: " . $filePath . "\n";
    echo "File size: " . filesize($filePath) . " bytes\n";
    echo "MIME type: " . mime_content_type($filePath) . "\n";
    echo "Last modified: " . date('Y-m-d H:i:s', filemtime($filePath)) . "\n";
    echo "Is readable: " . (is_readable($filePath) ? 'Yes' : 'No') . "\n";
    echo "Is writable: " . (is_writable($filePath) ? 'Yes' : 'No') . "\n";
}

function displayFileUploadForm() {
    echo "Sample HTML form for file uploads:\n\n";
    echo '<!-- IMPORTANT: Form must have enctype="multipart/form-data" for file uploads -->' . "\n";
    echo '<form method="POST" enctype="multipart/form-data">' . "\n";
    echo '    <input type="file" name="user_file" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt">' . "\n";
    echo '    <input type="submit" value="Upload File">' . "\n";
    echo '</form>' . "\n\n";
    echo "Key points about file upload forms:\n";
    echo "- Method must be POST\n";
    echo "- enctype must be 'multipart/form-data'\n";
    echo "- Use accept attribute to limit file types\n";
    echo "- Consider adding MAX_FILE_SIZE hidden input\n";
}

// Handle multiple file uploads
function handleMultipleFileUploads() {
    echo "\n=== HANDLING MULTIPLE FILE UPLOADS ===\n";

    /*
    When you have multiple file inputs or a single input that accepts multiple files,
    PHP organizes them differently in the $_FILES array.
    */

    // For multiple files from the same input (HTML: <input type="file" name="files[]" multiple>)
    if (isset($_FILES['files']) && is_array($_FILES['files']['name'])) {
        echo "Processing multiple files from single input:\n";

        $fileCount = count($_FILES['files']['name']);
        echo "Number of files: " . $fileCount . "\n\n";

        for ($i = 0; $i < $fileCount; $i++) {
            echo "File " . ($i + 1) . ":\n";
            echo "  Name: " . $_FILES['files']['name'][$i] . "\n";
            echo "  Type: " . $_FILES['files']['type'][$i] . "\n";
            echo "  Size: " . $_FILES['files']['size'][$i] . " bytes\n";
            echo "  Error: " . $_FILES['files']['error'][$i] . "\n\n";
        }
    }

    // For separate file inputs (HTML: <input type="file" name="file1">, <input type="file" name="file2">)
    echo "Processing separate file inputs:\n";
    foreach ($_FILES as $inputName => $fileInfo) {
        if ($inputName !== 'files') { // Skip the multiple files array we handled above
            echo "Input '" . $inputName . "': " . $fileInfo['name'] . "\n";
        }
    }
}

handleMultipleFileUploads();
?>
```

---

## $\_REQUEST: The Universal Data Collector

The `$_REQUEST` superglobal is like a universal inbox that contains data from three sources combined: `$_GET`, `$_POST`, and `$_COOKIE`. Think of it as a convenient way to access user input regardless of how it was sent to your server.

### Understanding $\_REQUEST

Imagine you're running a customer service desk where people can contact you through email, phone, or in-person visits. Instead of having three separate systems to track these interactions, you have one universal system that captures all communications regardless of their source. That's essentially what `$_REQUEST` does for web data.

```php
<?php
echo "=== UNDERSTANDING \$_REQUEST ===\n\n";

// $_REQUEST combines data from multiple sources
echo "What's in \$_REQUEST?\n";
echo "--------------------\n";

if (!empty($_REQUEST)) {
    foreach ($_REQUEST as $key => $value) {
        echo $key . " = " . $value . "\n";
    }
    echo "\n";
} else {
    echo "No request data found.\n";
    echo "Try accessing this page with GET parameters (?name=John) or POST data.\n\n";
}

// Demonstrate the relationship between $_REQUEST and other superglobals
function demonstrateRequestRelationship() {
    echo "RELATIONSHIP BETWEEN \$_REQUEST AND OTHER SUPERGLOBALS:\n";
    echo "-----------------------------------------------------\n";

    // Show what's in each superglobal
    echo "GET data:\n";
    if (!empty($_GET)) {
        foreach ($_GET as $key => $value) {
            echo "  " . $key . " = " . $value . "\n";
        }
    } else {
        echo "  (empty)\n";
    }

    echo "\nPOST data:\n";
    if (!empty($_POST)) {
        foreach ($_POST as $key => $value) {
            echo "  " . $key . " = " . $value . "\n";
        }
    } else {
        echo "  (empty)\n";
    }

    echo "\nCOOKIE data:\n";
    if (!empty($_COOKIE)) {
        foreach ($_COOKIE as $key => $value) {
            echo "  " . $key . " = " . $value . "\n";
        }
    } else {
        echo "  (empty)\n";
    }

    echo "\nREQUEST data (combination of all above):\n";
    if (!empty($_REQUEST)) {
        foreach ($_REQUEST as $key => $value) {
            echo "  " . $key . " = " . $value . "\n";
        }
    } else {
        echo "  (empty)\n";
    }
}

// Understanding precedence in $_REQUEST
function understandPrecedence() {
    echo "\n=== UNDERSTANDING PRECEDENCE IN \$_REQUEST ===\n";
    echo "When the same key exists in multiple superglobals, \$_REQUEST follows this order:\n";
    echo "1. Cookies (lowest priority)\n";
    echo "2. GET parameters\n";
    echo "3. POST data (highest priority)\n\n";

    /*
    This means if you have:
    - A cookie named 'user' with value 'cookie_user'
    - A GET parameter ?user=get_user
    - A POST field named 'user' with value 'post_user'

    Then $_REQUEST['user'] will contain 'post_user' because POST has the highest priority.
    */

    echo "Example scenario:\n";
    echo "If you have a cookie 'theme=dark', GET parameter ?theme=light, and POST data theme=auto\n";
    echo "Then \$_REQUEST['theme'] will be 'auto' (from POST)\n";
}

// Practical example: Universal form handler
function createUniversalFormHandler() {
    echo "\n=== PRACTICAL EXAMPLE: UNIVERSAL FORM HANDLER ===\n";

    /*
    Sometimes you want to handle data that could come from either GET or POST.
    For example, a search form that works with both methods.
    */

    // Get search query from either GET or POST
    $searchQuery = getRequestData('q', '');
    $category = getRequestData('category', 'all');
    $sortBy = getRequestData('sort', 'relevance');

    echo "Universal Search Handler:\n";
    echo "------------------------\n";
    echo "Search query: " . htmlspecialchars($searchQuery) . "\n";
    echo "Category: " . htmlspecialchars($category) . "\n";
    echo "Sort by: " . htmlspecialchars($sortBy) . "\n";

    if (!empty($searchQuery)) {
        echo "Performing search for: '" . htmlspecialchars($searchQuery) . "'\n";
        echo "Method used: " . $_SERVER['REQUEST_METHOD'] . "\n";

        // Determine the source of the data
        if (isset($_POST['q'])) {
            echo "Data source: POST (form submission)\n";
        } elseif (isset($_GET['q'])) {
            echo "Data source: GET (URL parameter)\n";
        } elseif (isset($_COOKIE['q'])) {
            echo "Data source: COOKIE (stored preference)\n";
        }
    } else {
        echo "No search query provided.\n";
        echo "Try: ?q=php&category=tutorials&sort=date\n";
    }
}

// Helper function to safely get request data
function getRequestData($key, $defaultValue = '', $sanitize = true) {
    /*
    This function safely retrieves data from $_REQUEST with proper handling.
    It's similar to our previous helper functions but works with any request method.
    */

    if (!isset($_REQUEST[$key])) {
        return $defaultValue;
    }

    $value = $_REQUEST[$key];

    if ($sanitize && is_string($value)) {
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
    }

    return $value;
}

// When to use $_REQUEST vs specific superglobals
function whenToUseRequest() {
    echo "\n=== WHEN TO USE \$_REQUEST ===\n";
    echo "Use \$_REQUEST when:\n";
    echo "- You don't care about the source of the data\n";
    echo "- You're building flexible APIs that accept data from multiple sources\n";
    echo "- You're working with legacy code that uses mixed input methods\n";
    echo "- You want to simplify form handling for data that could come from anywhere\n\n";

    echo "Don't use \$_REQUEST when:\n";
    echo "- Security is a concern (use specific superglobals for better control)\n";
    echo "- You need to know the exact source of the data\n";
    echo "- You're handling sensitive operations (always use POST for sensitive data)\n";
    echo "- You want to follow modern PHP best practices\n\n";

    echo "Modern recommendation: Use specific superglobals (\$_GET, \$_POST, \$_COOKIE) instead of \$_REQUEST\n";
    echo "This makes your code more explicit and secure.\n";
}

// Run the demonstrations
demonstrateRequestRelationship();
understandPrecedence();
createUniversalFormHandler();
whenToUseRequest();
?>
```

---

## $\_ENV: Accessing Environment Variables

The `$_ENV` superglobal provides access to environment variables, which are settings that exist at the operating system level. Think of environment variables as global configuration settings that are available to all programs running on a system.

### Understanding Environment Variables

Imagine your computer is like a large office building, and environment variables are like building-wide announcements posted on bulletin boards. Every program (like every office worker) can read these announcements to understand important information about the environment they're operating in.

```php
<?php
echo "=== UNDERSTANDING ENVIRONMENT VARIABLES ===\n\n";

// Display all environment variables
echo "Environment variables available:\n";
echo "--------------------------------\n";

if (!empty($_ENV)) {
    foreach ($_ENV as $key => $value) {
        echo $key . " = " . $value . "\n";
    }
    echo "\n";
} else {
    echo "No environment variables found in \$_ENV.\n";
    echo "This might be because variables_order in php.ini doesn't include 'E'.\n";
    echo "Try using getenv() function instead.\n\n";
}

// Alternative way to access environment variables
function demonstrateGetenv() {
    echo "USING getenv() FUNCTION:\n";
    echo "------------------------\n";

    // Common environment variables across different systems
    $commonVars = ['PATH', 'HOME', 'USER', 'USERNAME', 'OS', 'SHELL', 'LANG', 'PWD'];

    foreach ($commonVars as $var) {
        $value = getenv($var);
        if ($value !== false) {
            echo $var . " = " . $value . "\n";
        } else {
            echo $var . " = (not set)\n";
        }
    }
}

// Understanding different types of environment variables
function exploreEnvironmentTypes() {
    echo "\n=== TYPES OF ENVIRONMENT VARIABLES ===\n";

    echo "1. System Environment Variables:\n";
    echo "   These are set by the operating system\n";
    echo "   PATH = " . getenv('PATH') . "\n";
    echo "   USER = " . getenv('USER') . "\n";
    echo "   HOME = " . getenv('HOME') . "\n\n";

    echo "2. Web Server Environment Variables:\n";
    echo "   These are set by your web server (Apache, Nginx, etc.)\n";
    echo "   DOCUMENT_ROOT = " . getenv('DOCUMENT_ROOT') . "\n";
    echo "   SERVER_NAME = " . getenv('SERVER_NAME') . "\n\n";

    echo "3. PHP Environment Variables:\n";
    echo "   These are set by PHP itself\n";
    echo "   PHP_SELF = " . getenv('PHP_SELF') . "\n\n";

    echo "4. Custom Environment Variables:\n";
    echo "   These are set by you or your application\n";
    echo "   APP_ENV = " . getenv('APP_ENV') . "\n";
    echo "   DEBUG_MODE = " . getenv('DEBUG_MODE') . "\n";
}

// Practical application: Configuration management
function demonstrateConfigurationManagement() {
    echo "\n=== PRACTICAL APPLICATION: CONFIGURATION MANAGEMENT ===\n";

    /*
    Environment variables are excellent for storing configuration that changes
    between different environments (development, staging, production).
    This follows the "12-factor app" methodology.
    */

    // Database configuration from environment variables
    $dbHost = getenv('DB_HOST') ?: 'localhost';
    $dbName = getenv('DB_NAME') ?: 'myapp';
    $dbUser = getenv('DB_USER') ?: 'root';
    $dbPass = getenv('DB_PASS') ?: '';

    echo "Database Configuration:\n";
    echo "----------------------\n";
    echo "Host: " . $dbHost . "\n";
    echo "Database: " . $dbName . "\n";
    echo "User: " . $dbUser . "\n";
    echo "Password: " . (empty($dbPass) ? '(not set)' : '[HIDDEN]') . "\n\n";

    // Application configuration
    $appEnv = getenv('APP_ENV') ?: 'development';
    $debugMode = getenv('DEBUG_MODE') === 'true';
    $appUrl = getenv('APP_URL') ?: 'http://localhost';

    echo "Application Configuration:\n";
    echo "-------------------------\n";
    echo "Environment: " . $appEnv . "\n";
    echo "Debug Mode: " . ($debugMode ? 'Enabled' : 'Disabled') . "\n";
    echo "Application URL: " . $appUrl . "\n\n";

    // Configure application based on environment
    if ($appEnv === 'production') {
        echo "Production mode: Error reporting disabled, caching enabled\n";
    } elseif ($appEnv === 'development') {
        echo "Development mode: Error reporting enabled, caching disabled\n";
    } else {
        echo "Testing mode: Minimal logging, temporary database\n";
    }
}

// Setting environment variables in PHP
function demonstrateSettingEnvironmentVariables() {
    echo "\n=== SETTING ENVIRONMENT VARIABLES IN PHP ===\n";

    /*
    You can set environment variables within PHP using putenv(),
    but remember that these changes only affect the current script
    and any child processes it spawns.
    */

    // Set a custom environment variable
    putenv('MY_CUSTOM_VAR=Hello from PHP!');

    // Verify it was set
    echo "Custom variable set: " . getenv('MY_CUSTOM_VAR') . "\n";

    // Set multiple variables
    putenv('APP_NAME=My PHP Application');
    putenv('APP_VERSION=1.0.0');
    putenv('TEMP_DIR=/tmp/myapp');

    echo "App Name: " . getenv('APP_NAME') . "\n";
    echo "App Version: " . getenv('APP_VERSION') . "\n";
    echo "Temp Directory: " . getenv('TEMP_DIR') . "\n\n";

    echo "Note: Variables set with putenv() are only available to the current script.\n";
    echo "For persistent environment variables, set them in your system or web server configuration.\n";
}

// Security considerations
function discussSecurityConsiderations() {
    echo "\n=== SECURITY CONSIDERATIONS ===\n";
    echo "1. Never put sensitive data in environment variables on shared hosting\n";
    echo "2. Environment variables can be visible to other processes on the system\n";
    echo "3. Use .env files for development, proper secrets management for production\n";
    echo "4. Be careful when logging or displaying environment variables\n";
    echo "5. Consider using encrypted configuration files for highly sensitive data\n\n";

    // Example of safe environment variable handling
    echo "Safe environment variable handling:\n";
    echo "-----------------------------------\n";

    $apiKey = getenv('API_KEY');
    if ($apiKey) {
        echo "API Key: " . substr($apiKey, 0, 4) . "..." . substr($apiKey, -4) . " (masked)\n";
    } else {
        echo "API Key: Not configured\n";
    }
}

// Run the demonstrations
demonstrateGetenv();
exploreEnvironmentTypes();
demonstrateConfigurationManagement();
demonstrateSettingEnvironmentVariables();
discussSecurityConsiderations();
?>
```

---

## $\_COOKIE: Managing Browser Cookies

The `$_COOKIE` superglobal contains all the cookies that the user's browser has sent to your server. Cookies are small pieces of data that your website can store in the user's browser, which are then sent back to your server with each subsequent request.

### Understanding Cookies

Think of cookies like a coat check system at a restaurant. When you arrive, you give the attendant your coat and receive a numbered ticket. The restaurant keeps your coat and remembers which number belongs to you. Every time you need something, you show your ticket, and they know who you are and what belongs to you.

Cookies work similarly: your website gives the browser a small piece of information (the ticket), and the browser stores it and sends it back with every request to your site.

```php
<?php
echo "=== UNDERSTANDING COOKIES ===\n\n";

// Display all cookies
echo "Cookies received from browser:\n";
echo "-----------------------------\n";

if (!empty($_COOKIE)) {
    foreach ($_COOKIE as $name => $value) {
        echo $name . " = " . $value . "\n";
    }
    echo "\n";
} else {
    echo "No cookies found.\n";
    echo "This might be because:\n";
    echo "1. No cookies have been set yet\n";
    echo "2. The user has disabled cookies\n";
    echo "3. Cookies were set but haven't been sent back yet\n\n";
}

// Function to safely get cookie values
function getCookie($name, $defaultValue = null) {
    /*
    This function safely retrieves cookie values with proper handling.
    Cookies should always be treated as potentially untrusted user input.
    */

    if (!isset($_COOKIE[$name])) {
        return $defaultValue;
    }

    $value = $_COOKIE[$name];

    // Sanitize cookie value (cookies can be modified by users)
    $value = trim($value);
    $value = htmlspecialchars($value);

    return $value;
}

// Setting cookies
function demonstrateSettingCookies() {
    echo "=== SETTING COOKIES ===\n";

    /*
    To set a cookie, you use the setcookie() function.
    Important: Cookies must be set BEFORE any output is sent to the browser.
    */

    // Basic cookie setting
    $cookieSet = setcookie('user_preference', 'dark_mode', time() + 3600); // Expires in 1 hour

    if ($cookieSet) {
        echo "Cookie 'user_preference' set successfully\n";
    } else {
        echo "Failed to set cookie (headers might have been sent already)\n";
    }

    // Setting a cookie with more options
    $cookieOptions = [
        'expires' => time() + (86400 * 30), // 30 days
        'path' => '/',                       // Available across entire site
        'domain' => '',                      // Current domain only
        'secure' => isset($_SERVER['HTTPS']), // Only send over HTTPS
        'httponly' => true,                  // Not accessible via JavaScript
        'samesite' => 'Strict'               // CSRF protection
    ];

    setcookie('secure_token', 'abc123def456', $cookieOptions);
    echo "Secure cookie 'secure_token' set with advanced options\n\n";

    echo "Note: Cookies set in this request won't appear in \$_COOKIE until the next request.\n";
}

// Understanding cookie attributes
function explainCookieAttributes() {
    echo "\n=== UNDERSTANDING COOKIE ATTRIBUTES ===\n";

    echo "Cookie attributes control how cookies behave:\n\n";

    echo "1. Expires/Max-Age:\n";
    echo "   - Controls when the cookie expires\n";
    echo "   - If not set, cookie expires when browser closes (session cookie)\n";
    echo "   - Example: time() + 3600 = expires in 1 hour\n\n";

    echo "2. Path:\n";
    echo "   - Controls which URLs the cookie is sent to\n";
    echo "   - '/' means entire website\n";
    echo "   - '/admin' means only admin pages\n\n";

    echo "3. Domain:\n";
    echo "   - Controls which domains can access the cookie\n";
    echo "   - '.example.com' allows subdomains\n";
    echo "   - 'example.com' only allows exact domain\n\n";

    echo "4. Secure:\n";
    echo "   - Cookie only sent over HTTPS connections\n";
    echo "   - Essential for sensitive data\n\n";

    echo "5. HttpOnly:\n";
    echo "   - Cookie cannot be accessed via JavaScript\n";
    echo "   - Prevents XSS attacks\n\n";

    echo "6. SameSite:\n";
    echo "   - Controls cross-site request behavior\n";
    echo "   - 'Strict' = never sent with cross-site requests\n";
    echo "   - 'Lax' = sent with safe cross-site requests\n";
    echo "   - 'None' = always sent (requires Secure)\n";
}

// Practical cookie applications
function demonstratePracticalCookieUse() {
    echo "\n=== PRACTICAL COOKIE APPLICATIONS ===\n";

    // 1. User preferences
    echo "1. User Preferences:\n";
    echo "-------------------\n";

    $theme = getCookie('theme', 'light');
    $language = getCookie('language', 'en');
    $fontSize = getCookie('font_size', 'medium');

    echo "Current theme: " . $theme . "\n";
    echo "Current language: " . $language . "\n";
    echo "Font size: " . $fontSize . "\n\n";

    // Apply preferences
    if ($theme === 'dark') {
        echo "Applying dark theme styles...\n";
    }

    // 2. Shopping cart persistence
    echo "2. Shopping Cart Persistence:\n";
    echo "----------------------------\n";

    $cartItems = getCookie('cart_items', '[]');
    $cartData = json_decode($cartItems, true);

    if (!empty($cartData)) {
        echo "Items in cart: " . count($cartData) . "\n";
        foreach ($cartData as $item) {
            echo "  - " . $item['name'] . " (Qty: " . $item['quantity'] . ")\n";
        }
    } else {
        echo "Cart is empty\n";
    }

    // 3. Remember login status
    echo "\n3. Remember Login Status:\n";
    echo "------------------------\n";

    $rememberToken = getCookie('remember_token', '');
    $lastVisit = getCookie('last_visit', '');

    if (!empty($rememberToken)) {
        echo "User has 'remember me' cookie\n";
        echo "Token: " . substr($rememberToken, 0, 8) . "... (truncated for security)\n";
    }

    if (!empty($lastVisit)) {
        echo "Last visit: " . $lastVisit . "\n";
    }

    // Update last visit
    setcookie('last_visit', date('Y-m-d H:i:s'), time() + (86400 * 30));
}

// Cookie security best practices
function demonstrateCookieSecurity() {
    echo "\n=== COOKIE SECURITY BEST PRACTICES ===\n";

    echo "Understanding Cookie Vulnerabilities:\n";
    echo "------------------------------------\n";

    echo "1. Session Hijacking:\n";
    echo "   Problem: Attacker steals session cookie and impersonates user\n";
    echo "   Solution: Use secure, httponly cookies with proper regeneration\n\n";

    echo "2. Cross-Site Scripting (XSS):\n";
    echo "   Problem: Malicious JavaScript can read cookies\n";
    echo "   Solution: Use httponly flag to prevent JavaScript access\n\n";

    echo "3. Cross-Site Request Forgery (CSRF):\n";
    echo "   Problem: Cookies sent with requests from other sites\n";
    echo "   Solution: Use SameSite attribute and CSRF tokens\n\n";

    echo "4. Man-in-the-Middle Attacks:\n";
    echo "   Problem: Cookies intercepted over unsecured connections\n";
    echo "   Solution: Use secure flag to ensure HTTPS-only transmission\n\n";

    // Example of secure cookie implementation
    echo "Secure Cookie Implementation Example:\n";
    echo "------------------------------------\n";

    function setSecureCookie($name, $value, $expiry = 3600) {
        $options = [
            'expires' => time() + $expiry,
            'path' => '/',
            'domain' => '',
            'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
            'httponly' => true,
            'samesite' => 'Strict'
        ];

        return setcookie($name, $value, $options);
    }

    // Set a secure authentication token
    $authToken = bin2hex(random_bytes(32)); // Generate cryptographically secure token
    setSecureCookie('auth_token', $authToken, 86400); // 24 hours

    echo "Secure authentication token set with all security flags enabled\n";
}

// Deleting cookies
function demonstrateDeleteCookies() {
    echo "\n=== DELETING COOKIES ===\n";

    echo "Understanding Cookie Deletion:\n";
    echo "-----------------------------\n";
    echo "Cookies cannot be directly 'deleted' from the browser.\n";
    echo "Instead, you set them to expire in the past, causing the browser to remove them.\n\n";

    // Method 1: Set expiry to past time
    setcookie('user_preference', '', time() - 3600);
    echo "Cookie 'user_preference' deleted by setting expiry to past time\n";

    // Method 2: Use built-in shortcut
    setcookie('secure_token', '', 1); // Set to expire at timestamp 1 (January 1, 1970)
    echo "Cookie 'secure_token' deleted using timestamp 1\n";

    // Method 3: Complete deletion function
    function deleteCookie($name, $path = '/', $domain = '') {
        /*
        This function properly deletes a cookie by setting it to expire
        in the past with the same path and domain it was created with.
        */

        return setcookie($name, '', time() - 3600, $path, $domain);
    }

    echo "\nComplete cookie deletion function created\n";
    echo "Usage: deleteCookie('cookie_name', '/admin', '.example.com')\n";
}

// Cookie limitations and alternatives
function discussCookieLimitations() {
    echo "\n=== COOKIE LIMITATIONS AND ALTERNATIVES ===\n";

    echo "Cookie Limitations:\n";
    echo "------------------\n";
    echo "1. Size limit: Maximum 4KB per cookie\n";
    echo "2. Quantity limit: Usually 50-100 cookies per domain\n";
    echo "3. User control: Users can disable or delete cookies\n";
    echo "4. Security concerns: Sent with every request, visible to user\n";
    echo "5. Limited data types: Only strings (must serialize complex data)\n\n";

    echo "When to Use Cookies:\n";
    echo "-------------------\n";
    echo "- User preferences that should persist across sessions\n";
    echo "- Shopping cart contents for guest users\n";
    echo "- Remember login functionality\n";
    echo "- Tracking user behavior (with proper consent)\n";
    echo "- Small amounts of data that need to be sent with every request\n\n";

    echo "Alternatives to Cookies:\n";
    echo "-----------------------\n";
    echo "1. Sessions: For temporary server-side storage\n";
    echo "2. Local Storage: For client-side storage that doesn't send with requests\n";
    echo "3. Database: For permanent, complex data storage\n";
    echo "4. URL parameters: For temporary state passing\n";
    echo "5. Hidden form fields: For maintaining state across form submissions\n";
}

// Run cookie demonstrations
demonstratePracticalCookieUse();
demonstrateCookieSecurity();
demonstrateDeleteCookies();
discussCookieLimitations();
?>
```

---

## $\_SESSION: Server-Side Session Management

While cookies store data on the user's browser, sessions store data on your server. The `$_SESSION` superglobal provides access to session data, which is temporary information that persists for the duration of a user's visit to your website.

### Understanding Sessions

Think of sessions like a locker system at a gym. When you arrive, you're given a locker number (session ID) and a key. You can store your belongings (data) in the locker throughout your visit. The gym (server) keeps track of which locker belongs to which person, and you can access your belongings anytime during your visit. When you leave, the locker is cleaned out and reassigned to someone else.

In web terms, PHP creates a unique session ID for each visitor and stores their session data on the server. The session ID is typically sent to the browser as a cookie, but the actual data remains secure on the server.

```php
<?php
echo "=== UNDERSTANDING SESSIONS ===\n\n";

// Starting a session
echo "Starting a session...\n";
session_start();
echo "Session started successfully!\n";
echo "Session ID: " . session_id() . "\n";
echo "Session name: " . session_name() . "\n\n";

// Understanding session mechanics
function explainSessionMechanics() {
    echo "HOW SESSIONS WORK:\n";
    echo "-----------------\n";

    echo "1. User visits your website\n";
    echo "2. PHP generates a unique session ID (like: " . session_id() . ")\n";
    echo "3. Session ID is sent to browser as a cookie (usually named PHPSESSID)\n";
    echo "4. Session data is stored on the server in files or database\n";
    echo "5. On subsequent requests, browser sends session ID back\n";
    echo "6. PHP uses session ID to retrieve the user's data from server storage\n\n";

    echo "Session Storage Location:\n";
    echo "------------------------\n";
    echo "Sessions are typically stored in: " . session_save_path() . "\n";
    echo "Session file for this user would be: sess_" . session_id() . "\n\n";
}

// Working with session data
function demonstrateSessionData() {
    echo "=== WORKING WITH SESSION DATA ===\n";

    // Setting session variables
    $_SESSION['username'] = 'john_doe';
    $_SESSION['user_id'] = 123;
    $_SESSION['login_time'] = time();
    $_SESSION['user_preferences'] = [
        'theme' => 'dark',
        'language' => 'en',
        'notifications' => true
    ];

    echo "Session variables set:\n";
    echo "---------------------\n";
    echo "Username: " . $_SESSION['username'] . "\n";
    echo "User ID: " . $_SESSION['user_id'] . "\n";
    echo "Login time: " . date('Y-m-d H:i:s', $_SESSION['login_time']) . "\n";
    echo "Theme preference: " . $_SESSION['user_preferences']['theme'] . "\n\n";

    // Reading session data safely
    echo "Safe session data reading:\n";
    echo "-------------------------\n";

    $username = getSessionData('username', 'Guest');
    $userRole = getSessionData('user_role', 'visitor');
    $cartItems = getSessionData('cart_items', []);

    echo "Current user: " . $username . "\n";
    echo "User role: " . $userRole . "\n";
    echo "Cart items: " . count($cartItems) . "\n\n";

    // Modifying session data
    $_SESSION['page_views'] = isset($_SESSION['page_views']) ? $_SESSION['page_views'] + 1 : 1;
    $_SESSION['last_activity'] = time();

    echo "Page views this session: " . $_SESSION['page_views'] . "\n";
    echo "Last activity: " . date('H:i:s', $_SESSION['last_activity']) . "\n";
}

// Helper function for safe session data access
function getSessionData($key, $defaultValue = null) {
    /*
    This function safely retrieves session data with proper error handling.
    Always check if session data exists before using it.
    */

    if (!isset($_SESSION[$key])) {
        return $defaultValue;
    }

    return $_SESSION[$key];
}

// Practical session applications
function demonstrateSessionApplications() {
    echo "\n=== PRACTICAL SESSION APPLICATIONS ===\n";

    // 1. User Authentication
    echo "1. User Authentication System:\n";
    echo "-----------------------------\n";

    function loginUser($username, $password) {
        // In a real application, you'd verify credentials against a database
        if ($username === 'admin' && $password === 'secret') {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['user_role'] = 'administrator';
            $_SESSION['login_time'] = time();

            return true;
        }

        return false;
    }

    function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    function requireLogin() {
        if (!isLoggedIn()) {
            echo "Access denied: Please log in to continue\n";
            return false;
        }

        return true;
    }

    // Check current authentication status
    if (isLoggedIn()) {
        echo "User is logged in as: " . $_SESSION['username'] . "\n";
        echo "Role: " . $_SESSION['user_role'] . "\n";
        echo "Logged in since: " . date('Y-m-d H:i:s', $_SESSION['login_time']) . "\n";
    } else {
        echo "User is not logged in\n";
        echo "Attempting login...\n";

        if (loginUser('admin', 'secret')) {
            echo "Login successful!\n";
        } else {
            echo "Login failed!\n";
        }
    }

    // 2. Shopping Cart Implementation
    echo "\n2. Shopping Cart Implementation:\n";
    echo "-------------------------------\n";

    function addToCart($productId, $productName, $price, $quantity = 1) {
        // Initialize cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if item already exists in cart
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = [
                'name' => $productName,
                'price' => $price,
                'quantity' => $quantity
            ];
        }

        echo "Added " . $quantity . " x " . $productName . " to cart\n";
    }

    function removeFromCart($productId) {
        if (isset($_SESSION['cart'][$productId])) {
            $itemName = $_SESSION['cart'][$productId]['name'];
            unset($_SESSION['cart'][$productId]);
            echo "Removed " . $itemName . " from cart\n";
        }
    }

    function getCartTotal() {
        $total = 0;

        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        }

        return $total;
    }

    function displayCart() {
        if (empty($_SESSION['cart'])) {
            echo "Cart is empty\n";
            return;
        }

        echo "Cart Contents:\n";
        foreach ($_SESSION['cart'] as $productId => $item) {
            echo "  " . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . "\n";
        }
        echo "Total: $" . number_format(getCartTotal(), 2) . "\n";
    }

    // Demonstrate cart functionality
    addToCart(1, 'PHP Book', 29.99, 1);
    addToCart(2, 'JavaScript Guide', 34.99, 2);
    addToCart(1, 'PHP Book', 29.99, 1); // This will increase quantity

    displayCart();

    // 3. Multi-step Form Wizard
    echo "\n3. Multi-step Form Wizard:\n";
    echo "-------------------------\n";

    function initializeWizard() {
        $_SESSION['wizard'] = [
            'step' => 1,
            'data' => [],
            'completed_steps' => []
        ];
    }

    function saveWizardStep($step, $data) {
        if (!isset($_SESSION['wizard'])) {
            initializeWizard();
        }

        $_SESSION['wizard']['data']['step_' . $step] = $data;
        $_SESSION['wizard']['completed_steps'][] = $step;
        $_SESSION['wizard']['step'] = $step + 1;

        echo "Step " . $step . " data saved\n";
    }

    function getWizardData($step = null) {
        if (!isset($_SESSION['wizard'])) {
            return null;
        }

        if ($step === null) {
            return $_SESSION['wizard']['data'];
        }

        return $_SESSION['wizard']['data']['step_' . $step] ?? null;
    }

    // Simulate wizard steps
    if (!isset($_SESSION['wizard'])) {
        initializeWizard();
        saveWizardStep(1, ['name' => 'John', 'email' => 'john@example.com']);
        saveWizardStep(2, ['address' => '123 Main St', 'city' => 'Anytown']);
        saveWizardStep(3, ['payment_method' => 'credit_card', 'card_type' => 'visa']);
    }

    echo "Wizard current step: " . $_SESSION['wizard']['step'] . "\n";
    echo "Completed steps: " . implode(', ', $_SESSION['wizard']['completed_steps']) . "\n";
    echo "All wizard data: " . print_r($_SESSION['wizard']['data'], true) . "\n";
}

// Session security considerations
function demonstrateSessionSecurity() {
    echo "\n=== SESSION SECURITY CONSIDERATIONS ===\n";

    echo "1. Session Hijacking Prevention:\n";
    echo "-------------------------------\n";

    function regenerateSessionId() {
        /*
        Regenerating the session ID prevents session fixation attacks.
        This should be done after authentication and periodically.
        */

        $oldSessionId = session_id();
        session_regenerate_id(true); // true = delete old session file
        $newSessionId = session_id();

        echo "Session ID regenerated\n";
        echo "Old ID: " . $oldSessionId . "\n";
        echo "New ID: " . $newSessionId . "\n";
    }

    regenerateSessionId();

    echo "\n2. Session Timeout Implementation:\n";
    echo "---------------------------------\n";

    function checkSessionTimeout($timeoutMinutes = 30) {
        $timeoutSeconds = $timeoutMinutes * 60;

        if (isset($_SESSION['last_activity'])) {
            $inactive = time() - $_SESSION['last_activity'];

            if ($inactive >= $timeoutSeconds) {
                echo "Session timed out after " . $timeoutMinutes . " minutes of inactivity\n";
                session_destroy();
                return false;
            }
        }

        $_SESSION['last_activity'] = time();
        return true;
    }

    if (checkSessionTimeout(30)) {
        echo "Session is active\n";
    } else {
        echo "Session expired\n";
    }

    echo "\n3. Session Fingerprinting:\n";
    echo "-------------------------\n";

    function createSessionFingerprint() {
        /*
        Session fingerprinting helps detect session hijacking by
        tracking characteristics of the user's environment.
        */

        $fingerprint = md5(
            $_SERVER['HTTP_USER_AGENT'] .
            $_SERVER['REMOTE_ADDR'] .
            $_SERVER['HTTP_ACCEPT_LANGUAGE']
        );

        if (!isset($_SESSION['fingerprint'])) {
            $_SESSION['fingerprint'] = $fingerprint;
            echo "Session fingerprint created\n";
        } else {
            if ($_SESSION['fingerprint'] !== $fingerprint) {
                echo "WARNING: Session fingerprint mismatch - possible hijacking attempt\n";
                session_destroy();
                return false;
            }
            echo "Session fingerprint verified\n";
        }

        return true;
    }

    createSessionFingerprint();
}

// Session configuration and cleanup
function demonstrateSessionManagement() {
    echo "\n=== SESSION MANAGEMENT ===\n";

    echo "Current Session Configuration:\n";
    echo "-----------------------------\n";
    echo "Session name: " . session_name() . "\n";
    echo "Session ID: " . session_id() . "\n";
    echo "Session save path: " . session_save_path() . "\n";
    echo "Session cookie lifetime: " . ini_get('session.cookie_lifetime') . " seconds\n";
    echo "Session garbage collection maxlifetime: " . ini_get('session.gc_maxlifetime') . " seconds\n\n";

    echo "Session Data Summary:\n";
    echo "--------------------\n";
    echo "Total session variables: " . count($_SESSION) . "\n";
    echo "Session data size: " . strlen(serialize($_SESSION)) . " bytes\n";

    if (!empty($_SESSION)) {
        echo "Session variables:\n";
        foreach ($_SESSION as $key => $value) {
            $type = gettype($value);
            $size = strlen(serialize($value));
            echo "  " . $key . " (" . $type . ", " . $size . " bytes)\n";
        }
    }

    echo "\nSession Cleanup Functions:\n";
    echo "-------------------------\n";

    function clearSessionData($keys = null) {
        if ($keys === null) {
            // Clear all session data
            $_SESSION = [];
            echo "All session data cleared\n";
        } else {
            // Clear specific keys
            foreach ($keys as $key) {
                if (isset($_SESSION[$key])) {
                    unset($_SESSION[$key]);
                    echo "Cleared session variable: " . $key . "\n";
                }
            }
        }
    }

    function destroySession() {
        // Complete session destruction
        $_SESSION = [];

        // Delete session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Destroy session file
        session_destroy();
        echo "Session completely destroyed\n";
    }

    // Example of selective cleanup
    clearSessionData(['wizard', 'temporary_data']);

    echo "\nSession cleanup functions are ready to use\n";
}

// Run session demonstrations
explainSessionMechanics();
demonstrateSessionData();
demonstrateSessionApplications();
demonstrateSessionSecurity();
demonstrateSessionManagement();
?>
```

---
