# Complete PHP Forms Tutorial for Beginners

## Table of Contents

1. [Introduction to PHP Forms](#introduction)
2. [Understanding HTTP Methods](#http-methods)
3. [PHP Form Handling Basics](#form-handling)
4. [PHP Form Validation](#form-validation)
5. [Required Fields](#required-fields)
6. [URL and Email Validation](#url-email-validation)
7. [Complete Form Example](#complete-form)
8. [Security Best Practices](#security)

---

## Introduction to PHP Forms {#introduction}

Think of PHP forms as a conversation between your website and your users. Just like when you fill out a paper form at a doctor's office, web forms collect information from users and send it somewhere to be processed. The key difference is that with PHP, we can immediately process, validate, and respond to that information.

When a user fills out a form on your website, PHP acts like a skilled receptionist who receives the information, checks if it's complete and correct, and then decides what to do with it. This might mean saving it to a database, sending an email, or displaying a thank you message.

### Why Forms Are Essential

Forms are the primary way websites collect user input. Whether it's a contact form, registration page, or survey, forms bridge the gap between static web pages and interactive web applications. PHP's strength lies in its ability to handle form data securely and efficiently on the server side.

---

## Understanding HTTP Methods {#http-methods}

Before diving into PHP form handling, we need to understand how data travels from the browser to your server. There are two main methods for sending form data: GET and POST.

### GET Method

The GET method sends data through the URL itself. Imagine writing a note and taping it to the outside of an envelope where everyone can see it. This method is visible, limited in size, and primarily used for retrieving information.

```php
<?php
// Example: http://example.com/search.php?query=php&category=tutorial
if (isset($_GET['query'])) {
    $searchTerm = $_GET['query'];
    echo "You searched for: " . $searchTerm;
}
?>

<!-- HTML form using GET method -->
<form action="search.php" method="GET">
    <input type="text" name="query" placeholder="Enter search term">
    <input type="submit" value="Search">
</form>
```

### POST Method

The POST method sends data inside the request body, like putting a letter inside a sealed envelope. This method is more secure, can handle larger amounts of data, and is ideal for sensitive information like passwords or personal details.

```php
<?php
// POST data is not visible in the URL
if (isset($_POST['username'])) {
    $username = $_POST['username'];
    echo "Welcome, " . $username;
}
?>

<!-- HTML form using POST method -->
<form action="process.php" method="POST">
    <input type="text" name="username" placeholder="Enter username">
    <input type="password" name="password" placeholder="Enter password">
    <input type="submit" value="Login">
</form>
```

---

## PHP Form Handling Basics {#form-handling}

PHP provides several superglobal arrays to handle form data. Think of these as special containers that PHP automatically fills with form data when a request is made.

### The $\_POST Superglobal

The $\_POST array contains all data sent via the POST method. It's an associative array where the keys are the names of form fields and the values are what the user entered.

```php
<?php
// contact.php - A simple contact form processor
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if form was submitted via POST method

    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Process the data (for now, just display it)
    echo "<h2>Thank you for your message!</h2>";
    echo "<p><strong>Name:</strong> " . $name . "</p>";
    echo "<p><strong>Email:</strong> " . $email . "</p>";
    echo "<p><strong>Message:</strong> " . $message . "</p>";
} else {
    // Display the form if not submitted
    ?>
    <form action="contact.php" method="POST">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="message">Message:</label><br>
        <textarea id="message" name="message" rows="5" cols="40" required></textarea><br><br>

        <input type="submit" value="Send Message">
    </form>
    <?php
}
?>
```

### The $\_GET Superglobal

The $\_GET array works similarly but contains data sent via the GET method. This is commonly used for search forms or navigation parameters.

```php
<?php
// search.php - A simple search form
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $category = $_GET['category'] ?? 'all'; // Default to 'all' if not set

    echo "<h2>Search Results</h2>";
    echo "<p>Searching for: <strong>" . htmlspecialchars($searchTerm) . "</strong></p>";
    echo "<p>Category: <strong>" . htmlspecialchars($category) . "</strong></p>";
} else {
    ?>
    <form action="search.php" method="GET">
        <label for="search">Search Term:</label><br>
        <input type="text" id="search" name="search" placeholder="Enter search term"><br><br>

        <label for="category">Category:</label><br>
        <select id="category" name="category">
            <option value="all">All Categories</option>
            <option value="tutorials">Tutorials</option>
            <option value="documentation">Documentation</option>
            <option value="examples">Examples</option>
        </select><br><br>

        <input type="submit" value="Search">
    </form>
    <?php
}
?>
```

---

## PHP Form Validation {#form-validation}

Form validation is like having a quality control inspector check products before they leave the factory. It ensures that the data users submit meets your requirements and helps prevent errors and security issues.

### Basic Validation Techniques

There are two types of validation: client-side (JavaScript) and server-side (PHP). While client-side validation improves user experience, server-side validation is essential for security because users can bypass client-side checks.

```php
<?php
// registration.php - User registration with validation
$errors = array();
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = "Username can only contain letters, numbers, and underscores";
    }

    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    }

    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long";
    }

    // Validate password confirmation
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    // If no errors, process the registration
    if (empty($errors)) {
        $success = true;
        // Here you would typically save to database
        // For now, we'll just show a success message
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <style>
        .error { color: red; }
        .success { color: green; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 300px;
            padding: 8px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h2>User Registration</h2>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <h3>Please fix the following errors:</h3>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success">
            <h3>Registration successful!</h3>
            <p>Welcome, <?php echo htmlspecialchars($username); ?>!</p>
        </div>
    <?php else: ?>
        <form method="POST" action="registration.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username"
                       value="<?php echo htmlspecialchars($username ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email"
                       value="<?php echo htmlspecialchars($email ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>

            <input type="submit" value="Register">
        </form>
    <?php endif; ?>
</body>
</html>
```

### Understanding Validation Logic

The validation process follows a systematic approach. First, we collect all the form data and store it in variables. Then, we apply specific checks to each field, accumulating any errors in an array. Finally, we either process the form if there are no errors or display the errors to the user.

The `trim()` function removes whitespace from the beginning and end of strings, preventing issues with accidental spaces. The `empty()` function checks if a variable is empty, which includes empty strings, null values, and zero.

---

## Required Fields {#required-fields}

Making fields required is fundamental to ensuring you receive the essential information you need. Think of required fields as the minimum information needed to complete a task, like needing a name and phone number to make a reservation.

```php
<?php
// contact_form.php - Contact form with required field validation
$errors = array();
$form_data = array();

// Define required fields
$required_fields = array('name', 'email', 'subject', 'message');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $form_data['name'] = trim($_POST['name'] ?? '');
    $form_data['email'] = trim($_POST['email'] ?? '');
    $form_data['subject'] = trim($_POST['subject'] ?? '');
    $form_data['message'] = trim($_POST['message'] ?? '');
    $form_data['phone'] = trim($_POST['phone'] ?? ''); // Optional field

    // Check required fields
    foreach ($required_fields as $field) {
        if (empty($form_data[$field])) {
            $errors[] = ucfirst($field) . " is required";
        }
    }

    // Additional validation for specific fields
    if (!empty($form_data['email']) && !filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    }

    if (!empty($form_data['phone']) && !preg_match('/^[\d\s\-\(\)]+$/', $form_data['phone'])) {
        $errors[] = "Please enter a valid phone number";
    }

    // If validation passes, process the form
    if (empty($errors)) {
        // Here you would typically send an email or save to database
        echo "<div style='color: green; padding: 10px; border: 1px solid green;'>";
        echo "<h3>Message sent successfully!</h3>";
        echo "<p>Thank you, " . htmlspecialchars($form_data['name']) . ". We'll get back to you soon.</p>";
        echo "</div>";

        // Clear form data after successful submission
        $form_data = array();
    }
}

// Function to preserve form values after submission
function getValue($field, $form_data) {
    return isset($form_data[$field]) ? htmlspecialchars($form_data[$field]) : '';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact Form</title>
    <style>
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        .required { color: red; }
        input[type="text"], input[type="email"], textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        textarea { height: 100px; resize: vertical; }
        .error-list { color: red; background: #ffe6e6; padding: 10px; border-radius: 4px; }
        .submit-btn { background: #007cba; color: white; padding: 12px 30px; border: none; border-radius: 4px; cursor: pointer; }
        .submit-btn:hover { background: #005a87; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Contact Us</h2>

        <?php if (!empty($errors)): ?>
            <div class="error-list">
                <h3>Please correct the following errors:</h3>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="contact_form.php">
            <div class="form-group">
                <label for="name">Full Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" value="<?php echo getValue('name', $form_data); ?>">
            </div>

            <div class="form-group">
                <label for="email">Email Address <span class="required">*</span></label>
                <input type="email" id="email" name="email" value="<?php echo getValue('email', $form_data); ?>">
            </div>

            <div class="form-group">
                <label for="phone">Phone Number (optional)</label>
                <input type="text" id="phone" name="phone" value="<?php echo getValue('phone', $form_data); ?>">
            </div>

            <div class="form-group">
                <label for="subject">Subject <span class="required">*</span></label>
                <input type="text" id="subject" name="subject" value="<?php echo getValue('subject', $form_data); ?>">
            </div>

            <div class="form-group">
                <label for="message">Message <span class="required">*</span></label>
                <textarea id="message" name="message" placeholder="Please describe your inquiry..."><?php echo getValue('message', $form_data); ?></textarea>
            </div>

            <div class="form-group">
                <input type="submit" value="Send Message" class="submit-btn">
            </div>
        </form>
    </div>
</body>
</html>
```

### Advanced Required Field Techniques

Sometimes you need conditional required fields, where certain fields become required based on other selections. Here's how to handle dynamic requirements:

```php
<?php
// order_form.php - Order form with conditional required fields
$errors = array();
$form_data = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect all form data
    $form_data['customer_name'] = trim($_POST['customer_name'] ?? '');
    $form_data['email'] = trim($_POST['email'] ?? '');
    $form_data['delivery_method'] = $_POST['delivery_method'] ?? '';
    $form_data['address'] = trim($_POST['address'] ?? '');
    $form_data['pickup_time'] = $_POST['pickup_time'] ?? '';
    $form_data['phone'] = trim($_POST['phone'] ?? '');

    // Always required fields
    $always_required = array('customer_name', 'email', 'delivery_method');

    foreach ($always_required as $field) {
        if (empty($form_data[$field])) {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required";
        }
    }

    // Conditional required fields based on delivery method
    if ($form_data['delivery_method'] == 'delivery') {
        if (empty($form_data['address'])) {
            $errors[] = "Delivery address is required for delivery orders";
        }
        if (empty($form_data['phone'])) {
            $errors[] = "Phone number is required for delivery orders";
        }
    } elseif ($form_data['delivery_method'] == 'pickup') {
        if (empty($form_data['pickup_time'])) {
            $errors[] = "Pickup time is required for pickup orders";
        }
    }

    // Validate email format
    if (!empty($form_data['email']) && !filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    }

    // Process if no errors
    if (empty($errors)) {
        echo "<div style='color: green; padding: 15px; border: 1px solid green; margin-bottom: 20px;'>";
        echo "<h3>Order received successfully!</h3>";
        echo "<p>Thank you, " . htmlspecialchars($form_data['customer_name']) . "</p>";
        echo "<p>Order method: " . htmlspecialchars($form_data['delivery_method']) . "</p>";
        echo "</div>";
    }
}
?>
```

---

## URL and Email Validation {#url-email-validation}

Validating URLs and email addresses requires understanding their expected formats and using appropriate validation techniques. Think of this as teaching your program to recognize valid addresses, just like how you can tell if a postal address looks correct.

### Email Validation

PHP provides built-in functions for email validation, but understanding the logic helps you create more robust validation systems.

```php
<?php
// email_validation.php - Comprehensive email validation
function validateEmail($email) {
    $errors = array();

    // Basic empty check
    if (empty($email)) {
        $errors[] = "Email address is required";
        return $errors;
    }

    // Remove whitespace
    $email = trim($email);

    // Check basic format using PHP's built-in filter
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
        return $errors;
    }

    // Additional custom checks
    if (strlen($email) > 254) {
        $errors[] = "Email address is too long";
    }

    // Check for common typos in domains
    $common_domains = array(
        'gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com',
        'aol.com', 'icloud.com', 'live.com'
    );

    $domain = substr(strrchr($email, "@"), 1);
    $suggestions = array();

    // Simple domain suggestion logic
    foreach ($common_domains as $common_domain) {
        if (levenshtein($domain, $common_domain) == 1) {
            $suggestions[] = $common_domain;
        }
    }

    if (!empty($suggestions)) {
        $errors[] = "Did you mean: " . str_replace($domain, $suggestions[0], $email) . "?";
    }

    return $errors;
}

// URL validation function
function validateURL($url) {
    $errors = array();

    if (empty($url)) {
        return $errors; // URL is optional in this example
    }

    $url = trim($url);

    // Add http:// if no protocol specified
    if (!preg_match('/^https?:\/\//', $url)) {
        $url = 'http://' . $url;
    }

    // Validate URL format
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        $errors[] = "Please enter a valid URL";
    }

    return $errors;
}

// Process form submission
$errors = array();
$form_data = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form_data['name'] = trim($_POST['name'] ?? '');
    $form_data['email'] = trim($_POST['email'] ?? '');
    $form_data['website'] = trim($_POST['website'] ?? '');
    $form_data['company'] = trim($_POST['company'] ?? '');

    // Validate name
    if (empty($form_data['name'])) {
        $errors[] = "Name is required";
    }

    // Validate email
    $email_errors = validateEmail($form_data['email']);
    $errors = array_merge($errors, $email_errors);

    // Validate URL
    $url_errors = validateURL($form_data['website']);
    $errors = array_merge($errors, $url_errors);

    // Process if no errors
    if (empty($errors)) {
        echo "<div style='color: green; padding: 15px; border: 1px solid green; margin-bottom: 20px;'>";
        echo "<h3>Profile saved successfully!</h3>";
        echo "<p>Name: " . htmlspecialchars($form_data['name']) . "</p>";
        echo "<p>Email: " . htmlspecialchars($form_data['email']) . "</p>";
        if (!empty($form_data['website'])) {
            echo "<p>Website: " . htmlspecialchars($form_data['website']) . "</p>";
        }
        echo "</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile Form</title>
    <style>
        .container { max-width: 500px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="url"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .error-list { color: red; background: #ffe6e6; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
        .help-text { font-size: 12px; color: #666; margin-top: 5px; }
        .submit-btn { background: #28a745; color: white; padding: 12px 30px; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Profile</h2>

        <?php if (!empty($errors)): ?>
            <div class="error-list">
                <h3>Please correct the following errors:</h3>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="email_validation.php">
            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($form_data['name'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>">
                <div class="help-text">We'll use this to send you important updates</div>
            </div>

            <div class="form-group">
                <label for="website">Website (optional)</label>
                <input type="url" id="website" name="website" value="<?php echo htmlspecialchars($form_data['website'] ?? ''); ?>" placeholder="https://example.com">
                <div class="help-text">Include http:// or https://</div>
            </div>

            <div class="form-group">
                <label for="company">Company (optional)</label>
                <input type="text" id="company" name="company" value="<?php echo htmlspecialchars($form_data['company'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <input type="submit" value="Save Profile" class="submit-btn">
            </div>
        </form>
    </div>
</body>
</html>
```

### Advanced URL Validation

For more complex URL validation, you might want to check if the URL is actually accessible or belongs to a specific domain:

```php
<?php
// Advanced URL validation with accessibility check
function validateURLAdvanced($url) {
    $errors = array();

    if (empty($url)) {
        return $errors;
    }

    $url = trim($url);

    // Add protocol if missing
    if (!preg_match('/^https?:\/\//', $url)) {
        $url = 'https://' . $url;
    }

    // Basic format validation
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        $errors[] = "Please enter a valid URL format";
        return $errors;
    }

    // Check if URL is accessible (optional - can be slow)
    $headers = @get_headers($url, 1);
    if (!$headers) {
        $errors[] = "Unable to verify URL accessibility";
    }

    // Check for allowed domains (if needed)
    $allowed_domains = array('github.com', 'linkedin.com', 'twitter.com');
    $parsed_url = parse_url($url);
    $domain = $parsed_url['host'];

    if (!empty($allowed_domains) && !in_array($domain, $allowed_domains)) {
        $errors[] = "Only URLs from " . implode(', ', $allowed_domains) . " are allowed";
    }

    return $errors;
}
?>
```

---

## Complete Form Example {#complete-form}

Now let's bring everything together in a comprehensive form that demonstrates all the concepts we've covered. This example shows a job application form with multiple validation types, file uploads, and proper error handling.

```php
<?php
// job_application.php - Complete job application form
session_start();

$errors = array();
$form_data = array();
$success = false;

// Define validation rules
$required_fields = array('first_name', 'last_name', 'email', 'phone', 'position', 'experience');
$file_upload_dir = 'uploads/';

// Create uploads directory if it doesn't exist
if (!file_exists($file_upload_dir)) {
    mkdir($file_upload_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $form_data = array(
        'first_name' => trim($_POST['first_name'] ?? ''),
        'last_name' => trim($_POST['last_name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'position' => $_POST['position'] ?? '',
        'experience' => $_POST['experience'] ?? '',
        'salary_expectation' => trim($_POST['salary_expectation'] ?? ''),
        'start_date' => $_POST['start_date'] ?? '',
        'portfolio_url' => trim($_POST['portfolio_url'] ?? ''),
        'cover_letter' => trim($_POST['cover_letter'] ?? ''),
        'newsletter' => isset($_POST['newsletter']) ? 'yes' : 'no'
    );

    // Validate required fields
    foreach ($required_fields as $field) {
        if (empty($form_data[$field])) {
            $field_name = ucfirst(str_replace('_', ' ', $field));
            $errors[] = $field_name . " is required";
        }
    }

    // Validate email
    if (!empty($form_data['email']) && !filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    }

    // Validate phone number
    if (!empty($form_data['phone'])) {
        $phone_pattern = '/^[\+]?[1-9][\d]{0,15}$/';
        if (!preg_match($phone_pattern, preg_replace('/[\s\-\(\)]/', '', $form_data['phone']))) {
            $errors[] = "Please enter a valid phone number";
        }
    }

    // Validate portfolio URL
    if (!empty($form_data['portfolio_url'])) {
        if (!filter_var($form_data['portfolio_url'], FILTER_VALIDATE_URL)) {
            $errors[] = "Please enter a valid portfolio URL";
        }
    }

    // Validate salary expectation (if provided)
    if (!empty($form_data['salary_expectation'])) {
        if (!is_numeric(str_replace(',', '', $form_data['salary_expectation']))) {
            $errors[] = "Salary expectation must be a valid number";
        }
    }

    // Validate start date
    if (!empty($form_data['start_date'])) {
        $start_date = DateTime::createFromFormat('Y-m-d', $form_data['start_date']);
        if (!$start_date || $start_date < new DateTime()) {
            $errors[] = "Start date must be today or in the future";
        }
    }

// Handle file upload (resume)
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        $allowed_types = array('application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $max_file_size = 2 * 1024 * 1024; // 2MB

        $file_type = $_FILES['resume']['type'];
        $file_size = $_FILES['resume']['size'];
        $file_tmp = $_FILES['resume']['tmp_name'];
        $file_name = $_FILES['resume']['name'];

        // Validate file type
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "Resume must be a PDF or Word document";
        }

        // Validate file size
        if ($file_size > $max_file_size) {
            $errors[] = "Resume file size must be less than 2MB";
        }

        // If file validation passes, prepare for upload
        if (empty($errors)) {
            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $new_filename = 'resume_' . time() . '.' . $file_extension;
            $upload_path = $file_upload_dir . $new_filename;

            if (!move_uploaded_file($file_tmp, $upload_path)) {
                $errors[] = "Failed to upload resume file";
            } else {
                $form_data['resume_filename'] = $new_filename;
            }
        }
    }

    // If all validations pass, process the application
    if (empty($errors)) {
        $success = true;

        // Here you would typically:
        // 1. Save to database
        // 2. Send confirmation email
        // 3. Notify HR department

        // For demonstration, we'll just show success message
        $_SESSION['application_data'] = $form_data;
    }
}

// Helper function to preserve form values
function getFormValue($field, $form_data) {
    return isset($form_data[$field]) ? htmlspecialchars($form_data[$field]) : '';
}

// Helper function to check if option is selected
function isSelected($field, $value, $form_data) {
    return (isset($form_data[$field]) && $form_data[$field] == $value) ? 'selected' : '';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Job Application Form</title>
    <style>
        .container { max-width: 800px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; }
        .form-section { margin-bottom: 30px; padding: 20px; background: #f9f9f9; border-radius: 8px; }
        .form-section h3 { margin-top: 0; color: #333; }
        .form-group { margin-bottom: 15px; }
        .form-row { display: flex; gap: 15px; }
        .form-row .form-group { flex: 1; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        .required { color: red; }
        input[type="text"], input[type="email"], input[type="tel"], input[type="date"],
        input[type="url"], input[type="file"], select, textarea {
            width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;
        }
        textarea { height: 100px; resize: vertical; }
        .checkbox-group { display: flex; align-items: center; gap: 10px; }
        .checkbox-group input[type="checkbox"] { width: auto; }
        .error-list { color: red; background: #ffe6e6; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .success { color: green; background: #e6ffe6; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .submit-btn { background: #007cba; color: white; padding: 15px 40px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .submit-btn:hover { background: #005a87; }
        .help-text { font-size: 12px; color: #666; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Job Application Form</h1>

        <?php if (!empty($errors)): ?>
            <div class="error-list">
                <h3>Please correct the following errors:</h3>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success">
                <h3>Application submitted successfully!</h3>
                <p>Thank you, <?php echo htmlspecialchars($form_data['first_name'] . ' ' . $form_data['last_name']); ?>!</p>
                <p>We have received your application for the <?php echo htmlspecialchars($form_data['position']); ?> position.</p>
                <p>We will review your application and get back to you within 1-2 business days.</p>
            </div>
        <?php else: ?>
            <form method="POST" action="job_application.php" enctype="multipart/form-data">
                <!-- Personal Information Section -->
                <div class="form-section">
                    <h3>Personal Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name <span class="required">*</span></label>
                            <input type="text" id="first_name" name="first_name" value="<?php echo getFormValue('first_name', $form_data); ?>">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name <span class="required">*</span></label>
                            <input type="text" id="last_name" name="last_name" value="<?php echo getFormValue('last_name', $form_data); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address <span class="required">*</span></label>
                            <input type="email" id="email" name="email" value="<?php echo getFormValue('email', $form_data); ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number <span class="required">*</span></label>
                            <input type="tel" id="phone" name="phone" value="<?php echo getFormValue('phone', $form_data); ?>">
                        </div>
                    </div>
                </div>

                <!-- Position Information Section -->
                <div class="form-section">
                    <h3>Position Information</h3>
                    <div class="form-group">
                        <label for="position">Position Applying For <span class="required">*</span></label>
                        <select id="position" name="position">
                            <option value="">-- Select Position --</option>
                            <option value="junior_developer" <?php echo isSelected('position', 'junior_developer', $form_data); ?>>Junior Developer</option>
                            <option value="senior_developer" <?php echo isSelected('position', 'senior_developer', $form_data); ?>>Senior Developer</option>
                            <option value="project_manager" <?php echo isSelected('position', 'project_manager', $form_data); ?>>Project Manager</option>
                            <option value="designer" <?php echo isSelected('position', 'designer', $form_data); ?>>UI/UX Designer</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="experience">Years of Experience <span class="required">*</span></label>
                            <select id="experience" name="experience">
                                <option value="">-- Select Experience --</option>
                                <option value="0-1" <?php echo isSelected('experience', '0-1', $form_data); ?>>0-1 years</option>
                                <option value="2-3" <?php echo isSelected('experience', '2-3', $form_data); ?>>2-3 years</option>
                                <option value="4-5" <?php echo isSelected('experience', '4-5', $form_data); ?>>4-5 years</option>
                                <option value="5+" <?php echo isSelected('experience', '5+', $form_data); ?>>5+ years</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="salary_expectation">Salary Expectation (USD)</label>
                            <input type="text" id="salary_expectation" name="salary_expectation"
                                   value="<?php echo getFormValue('salary_expectation', $form_data); ?>"
                                   placeholder="e.g., 60000">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="start_date">Available Start Date</label>
                        <input type="date" id="start_date" name="start_date" value="<?php echo getFormValue('start_date', $form_data); ?>">
                    </div>
                </div>

                <!-- Additional Information Section -->
                <div class="form-section">
                    <h3>Additional Information</h3>
                    <div class="form-group">
                        <label for="portfolio_url">Portfolio URL</label>
                        <input type="url" id="portfolio_url" name="portfolio_url"
                               value="<?php echo getFormValue('portfolio_url', $form_data); ?>"
                               placeholder="https://your-portfolio.com">
                        <div class="help-text">Link to your online portfolio or GitHub profile</div>
                    </div>

                    <div class="form-group">
                        <label for="resume">Resume <span class="required">*</span></label>
                        <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx">
                        <div class="help-text">Upload your resume (PDF or Word format, max 2MB)</div>
                    </div>

                    <div class="form-group">
                        <label for="cover_letter">Cover Letter</label>
                        <textarea id="cover_letter" name="cover_letter"
                                  placeholder="Tell us why you're interested in this position..."><?php echo getFormValue('cover_letter', $form_data); ?></textarea>
                    </div>

                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="newsletter" name="newsletter"
                                   <?php echo (isset($form_data['newsletter']) && $form_data['newsletter'] == 'yes') ? 'checked' : ''; ?>>
                            <label for="newsletter">Subscribe to our newsletter for job updates</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <input type="submit" value="Submit Application" class="submit-btn">
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
```

---

## Understanding File Uploads

File uploads add another layer of complexity to form handling. When users upload files, PHP stores them temporarily on the server and provides information about them through the `$_FILES` superglobal array. Think of this process like receiving a package - you need to inspect it, verify it's what you expected, and then decide where to store it permanently.

The `$_FILES` array contains information about each uploaded file, including its original name, size, type, and temporary location. The key insight is that uploaded files don't automatically end up where you want them - you must explicitly move them from their temporary location to a permanent directory using the `move_uploaded_file()` function.

### File Upload Security Considerations

File uploads present significant security risks if not handled properly. Never trust the file type information provided by the browser, as this can be easily manipulated. Instead, use PHP's built-in functions to verify file types and implement strict validation rules.

The `enctype="multipart/form-data"` attribute in the form tag is crucial for file uploads - without it, files won't be transmitted properly. This attribute tells the browser to encode the form data in a way that can handle binary file data alongside regular text fields.

---

## Security Best Practices {#security}

Security in PHP forms is like protecting your house - you need multiple layers of defense. The most important principle is to never trust user input. Every piece of data coming from a form should be treated as potentially malicious until proven otherwise.

### Cross-Site Scripting (XSS) Prevention

XSS attacks occur when malicious users inject JavaScript or HTML code into your forms, which then gets executed when displayed to other users. The primary defense is to escape output using `htmlspecialchars()` whenever displaying user-submitted data.

```php
<?php
// xss_prevention.php - Demonstrating XSS prevention
$message = '';
$user_comment = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input
    $user_comment = $_POST['comment'] ?? '';

    // WRONG: Never output user data directly
    // echo $user_comment; // This would be vulnerable to XSS

    // CORRECT: Always escape output
    $message = "Thank you for your comment: " . htmlspecialchars($user_comment, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>XSS Prevention Example</title>
    <style>
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .comment-box { width: 100%; height: 100px; padding: 10px; border: 1px solid #ddd; }
        .message { background: #e6ffe6; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
        .warning { background: #ffe6e6; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Comment Form - XSS Prevention Demo</h2>

        <div class="warning">
            <strong>Try entering malicious code like:</strong><br>
            <code>&lt;script&gt;alert('XSS Attack!')&lt;/script&gt;</code><br>
            <code>&lt;img src="x" onerror="alert('XSS')"&gt;</code>
        </div>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="xss_prevention.php">
            <label for="comment">Your Comment:</label><br>
            <textarea class="comment-box" id="comment" name="comment"
                      placeholder="Enter your comment here..."><?php echo htmlspecialchars($user_comment, ENT_QUOTES, 'UTF-8'); ?></textarea><br><br>
            <input type="submit" value="Submit Comment">
        </form>
    </div>
</body>
</html>
```

### SQL Injection Prevention

While not directly related to form display, understanding SQL injection is crucial when processing form data. When form data is used in database queries, it must be properly sanitized to prevent malicious SQL code injection.

```php
<?php
// sql_injection_prevention.php - Safe database operations
class DatabaseHandler {
    private $pdo;

    public function __construct($dsn, $username, $password) {
        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // CORRECT: Using prepared statements
    public function saveUser($name, $email) {
        $sql = "INSERT INTO users (name, email) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$name, $email]);
    }

    // WRONG: This would be vulnerable to SQL injection
    /*
    public function saveUserUnsafe($name, $email) {
        $sql = "INSERT INTO users (name, email) VALUES ('$name', '$email')";
        return $this->pdo->exec($sql);
    }
    */
}

// Example usage in form processing
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Validate data first
    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Use prepared statements for database operations
        $db = new DatabaseHandler('mysql:host=localhost;dbname=testdb', 'username', 'password');

        if ($db->saveUser($name, $email)) {
            echo "User saved successfully!";
        } else {
            echo "Error saving user.";
        }
    }
}
?>
```

### Cross-Site Request Forgery (CSRF) Protection

CSRF attacks trick users into performing unwanted actions on websites where they're authenticated. The solution is to include a unique token in each form that verifies the request came from your website.

```php
<?php
// csrf_protection.php - Implementing CSRF protection
session_start();

// Generate CSRF token
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

$errors = array();
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check CSRF token first
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = "Invalid security token. Please try again.";
    } else {
        // Process form normally
        $email = trim($_POST['email'] ?? '');

        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        } else {
            // Process subscription
            $success = true;
        }
    }
}

$csrf_token = generateCSRFToken();
?>

<!DOCTYPE html>
<html>
<head>
    <title>CSRF Protection Example</title>
    <style>
        .container { max-width: 500px; margin: 0 auto; padding: 20px; }
        .error { color: red; background: #ffe6e6; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
        .success { color: green; background: #e6ffe6; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="email"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .submit-btn { background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Newsletter Subscription</h2>

        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success">
                <p>Thank you for subscribing to our newsletter!</p>
            </div>
        <?php else: ?>
            <form method="POST" action="csrf_protection.php">
                <!-- Hidden CSRF token field -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <input type="submit" value="Subscribe" class="submit-btn">
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
```

---

## Form Processing Best Practices

Understanding best practices helps you build forms that are both secure and user-friendly. Think of these practices as time-tested recipes that help you avoid common pitfalls.

### Input Sanitization vs Validation

There's an important distinction between sanitizing and validating input. Validation checks if data meets your requirements, while sanitization cleans the data to make it safe for use. You should validate first to ensure data quality, then sanitize for security.

```php
<?php
// input_processing.php - Demonstrating sanitization vs validation
function processFormData($data) {
    $processed = array();
    $errors = array();

    // Name field: validate length, then sanitize
    $name = trim($data['name'] ?? '');
    if (strlen($name) < 2) {
        $errors[] = "Name must be at least 2 characters long";
    } else {
        // Sanitize: remove any HTML tags and convert special characters
        $processed['name'] = htmlspecialchars(strip_tags($name), ENT_QUOTES, 'UTF-8');
    }

    // Email field: validate format, then sanitize
    $email = trim($data['email'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    } else {
        // Sanitize email
        $processed['email'] = filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    // Phone number: validate format, then sanitize
    $phone = trim($data['phone'] ?? '');
    if (!empty($phone)) {
        // Remove all non-digit characters for validation
        $digits_only = preg_replace('/\D/', '', $phone);
        if (strlen($digits_only) < 10) {
            $errors[] = "Phone number must contain at least 10 digits";
        } else {
            // Store in a clean format
            $processed['phone'] = $digits_only;
        }
    }

    return array('data' => $processed, 'errors' => $errors);
}

// Example usage
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = processFormData($_POST);

    if (empty($result['errors'])) {
        // Safe to use processed data
        $clean_data = $result['data'];
        echo "Processing complete for: " . $clean_data['name'];
    } else {
        // Display validation errors
        foreach ($result['errors'] as $error) {
            echo "<p style='color: red;'>" . htmlspecialchars($error) . "</p>";
        }
    }
}
?>
```

### Preserving User Input After Validation Errors

When validation fails, users shouldn't have to re-enter all their information. The principle is to be helpful - remember what they entered correctly and only ask them to fix the errors.

```php
<?php
// The key is to store form data in variables before validation
// and then use these variables to populate form fields
$form_data = array(
    'name' => '',
    'email' => '',
    'message' => ''
);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Preserve submitted data
    $form_data['name'] = $_POST['name'] ?? '';
    $form_data['email'] = $_POST['email'] ?? '';
    $form_data['message'] = $_POST['message'] ?? '';

    // Validate and process...
    // If errors occur, $form_data still contains user input
}

// In your HTML form, always populate with preserved data:
// <input type="text" name="name" value="<?php echo htmlspecialchars($form_data['name']); ?>">
?>
```

---

## Conclusion

PHP forms are the foundation of interactive web applications. By understanding the concepts we've covered - HTTP methods, form processing, validation, security, and best practices - you're equipped to build robust, secure forms that provide excellent user experiences.

Remember that form handling is an iterative process. Start with basic functionality, then gradually add validation, security measures, and user experience enhancements. Each form you build will teach you something new about handling user input effectively.

The key principles to remember are: always validate on the server side, never trust user input, sanitize data for output, and make your forms user-friendly by preserving valid input when errors occur. These practices will serve you well as you continue developing with PHP.

### Next Steps

As you advance in PHP development, consider exploring topics like database integration with forms, advanced file upload handling, AJAX form submissions, and form libraries that can streamline your development process. The foundation you've built here will make these advanced topics much easier to understand and implement.
