# Complete Beginner's Guide to Semi-Structured Data in PHP

## Table of Contents
1. [What is Semi-Structured Data?](#what-is-semi-structured-data)
2. [JSON - JavaScript Object Notation](#json---javascript-object-notation)
3. [XML - eXtensible Markup Language](#xml---extensible-markup-language)
4. [CSV - Comma-Separated Values](#csv---comma-separated-values)
5. [PHP Serialization](#php-serialization)
6. [When to Use Which Format](#when-to-use-which-format)
7. [Common Mistakes to Avoid](#common-mistakes-to-avoid)
8. [Practice Exercises](#practice-exercises)

---

## What is Semi-Structured Data?

Think of data storage like organizing your closet:

- **Structured data** = Everything in labeled boxes, same size, same place (like a database table)
- **Unstructured data** = Everything thrown in randomly (like a plain text file)
- **Semi-structured data** = Organized with flexible containers and labels (like JSON, XML)

Semi-structured data is perfect when you need to store information that doesn't fit neatly into rows and columns, but still needs some organization.

**Examples of semi-structured data:**
- Configuration files for your website
- User profiles with different amounts of information
- Product catalogs with varying attributes
- Form data from websites

---

## JSON - JavaScript Object Notation

JSON is like a digital filing system that both humans and computers can easily understand.

### What Does JSON Look Like?

```json
{
  "name": "John Smith",
  "age": 25,
  "is_student": true,
  "courses": ["PHP", "HTML", "CSS"],
  "address": {
    "street": "123 Main St",
    "city": "New York"
  }
}
```

### JSON Rules (Simple!)

1. Use double quotes `"` around text (strings)
2. Numbers don't need quotes: `42` or `3.14`
3. True/false values: `true` or `false`
4. Empty values: `null`
5. Lists use square brackets: `[1, 2, 3]`
6. Objects use curly braces: `{"key": "value"}`

### Converting PHP to JSON

```php
<?php
// Start with PHP array
$student = [
    'name' => 'Alice Johnson',
    'age' => 22,
    'grades' => [85, 92, 78, 96],
    'is_honors' => true,
    'advisor' => null
];

// Convert to JSON
$json = json_encode($student);
echo $json;

// Output: {"name":"Alice Johnson","age":22,"grades":[85,92,78,96],"is_honors":true,"advisor":null}

// Make it pretty (easier to read)
$prettyJson = json_encode($student, JSON_PRETTY_PRINT);
echo $prettyJson;
/*
{
    "name": "Alice Johnson",
    "age": 22,
    "grades": [
        85,
        92,
        78,
        96
    ],
    "is_honors": true,
    "advisor": null
}
*/
?>
```

### Converting JSON to PHP

```php
<?php
$jsonString = '{
    "book_title": "Learn PHP",
    "author": "Jane Doe",
    "pages": 350,
    "chapters": ["Introduction", "Variables", "Functions"],
    "published": true
}';

// Convert JSON to PHP array
$book = json_decode($jsonString, true);

// Now you can use it like a normal PHP array
echo $book['book_title']; // Learn PHP
echo $book['pages']; // 350
echo $book['chapters'][0]; // Introduction

// Loop through chapters
foreach ($book['chapters'] as $chapter) {
    echo "Chapter: " . $chapter . "\n";
}
?>
```

### Practical Example: Storing User Preferences

```php
<?php
// User fills out a preferences form
$userPreferences = [
    'theme' => 'dark',
    'language' => 'english',
    'notifications' => [
        'email' => true,
        'sms' => false,
        'push' => true
    ],
    'privacy' => [
        'profile_public' => false,
        'show_email' => false
    ]
];

// Save to file
$json = json_encode($userPreferences, JSON_PRETTY_PRINT);
file_put_contents('user_preferences.json', $json);

// Later, load preferences
$loadedJson = file_get_contents('user_preferences.json');
$preferences = json_decode($loadedJson, true);

// Use the preferences
if ($preferences['theme'] === 'dark') {
    echo "Loading dark theme...";
}

if ($preferences['notifications']['email']) {
    echo "Email notifications enabled";
}
?>
```

### Handling JSON Errors

```php
<?php
// Bad JSON (missing quotes around string)
$badJson = '{name: "John", age: 30}';

$result = json_decode($badJson, true);

// Check if something went wrong
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON Error: " . json_last_error_msg();
    // Output: JSON Error: Syntax error
} else {
    print_r($result);
}

// Better way (PHP 7.3+)
try {
    $result = json_decode($badJson, true, 512, JSON_THROW_ON_ERROR);
    print_r($result);
} catch (JsonException $e) {
    echo "JSON Error: " . $e->getMessage();
}
?>
```

---

## XML - eXtensible Markup Language

XML is like HTML but for storing data instead of displaying web pages.

### What Does XML Look Like?

```xml
<?xml version="1.0" encoding="UTF-8"?>
<library>
    <book id="1">
        <title>PHP for Beginners</title>
        <author>John Smith</author>
        <year>2024</year>
        <available>true</available>
        <genres>
            <genre>Programming</genre>
            <genre>Web Development</genre>
        </genres>
    </book>
    <book id="2">
        <title>Advanced PHP</title>
        <author>Jane Doe</author>
        <year>2024</year>
        <available>false</available>
        <genres>
            <genre>Programming</genre>
            <genre>Advanced</genre>
        </genres>
    </book>
</library>
```

### Reading XML in PHP

```php
<?php
$xmlString = '<?xml version="1.0"?>
<recipes>
    <recipe id="1">
        <name>Chocolate Cake</name>
        <prep_time>30</prep_time>
        <difficulty>Medium</difficulty>
        <ingredients>
            <ingredient>Flour</ingredient>
            <ingredient>Sugar</ingredient>
            <ingredient>Cocoa</ingredient>
        </ingredients>
    </recipe>
    <recipe id="2">
        <name>Pasta</name>
        <prep_time>15</prep_time>
        <difficulty>Easy</difficulty>
        <ingredients>
            <ingredient>Pasta</ingredient>
            <ingredient>Tomato Sauce</ingredient>
            <ingredient>Cheese</ingredient>
        </ingredients>
    </recipe>
</recipes>';

// Load XML
$xml = simplexml_load_string($xmlString);

// Read data
echo "First recipe: " . $xml->recipe[0]->name . "\n"; // Chocolate Cake
echo "Recipe ID: " . $xml->recipe[0]['id'] . "\n"; // 1 (this is an attribute)

// Loop through all recipes
foreach ($xml->recipe as $recipe) {
    echo "Recipe: " . $recipe->name . "\n";
    echo "Time: " . $recipe->prep_time . " minutes\n";
    echo "Difficulty: " . $recipe->difficulty . "\n";
    
    echo "Ingredients: ";
    foreach ($recipe->ingredients->ingredient as $ingredient) {
        echo $ingredient . ", ";
    }
    echo "\n\n";
}
?>
```

### Creating XML in PHP

```php
<?php
// Simple way to create XML
$products = [
    ['id' => 1, 'name' => 'Laptop', 'price' => 999.99],
    ['id' => 2, 'name' => 'Mouse', 'price' => 25.50],
    ['id' => 3, 'name' => 'Keyboard', 'price' => 75.00]
];

// Start XML
$xml = new SimpleXMLElement('<products></products>');

// Add each product
foreach ($products as $productData) {
    $product = $xml->addChild('product');
    $product->addAttribute('id', $productData['id']);
    $product->addChild('name', $productData['name']);
    $product->addChild('price', $productData['price']);
}

// Output XML
echo $xml->asXML();

// Save to file
$xml->asXML('products.xml');
?>
```

### XML vs JSON Comparison

```php
<?php
$data = [
    'person' => [
        'name' => 'Alice',
        'age' => 30,
        'hobbies' => ['reading', 'swimming']
    ]
];

// Same data in JSON
$json = json_encode($data, JSON_PRETTY_PRINT);
echo "JSON:\n" . $json . "\n\n";

// Same data in XML (manually created for clarity)
$xmlString = '<?xml version="1.0"?>
<person>
    <name>Alice</name>
    <age>30</age>
    <hobbies>
        <hobby>reading</hobby>
        <hobby>swimming</hobby>
    </hobbies>
</person>';

echo "XML:\n" . $xmlString . "\n";

// JSON is more compact, XML is more descriptive
?>
```

---

## CSV - Comma-Separated Values

CSV is like a simple spreadsheet saved as text. Each line is a row, commas separate columns.

### What Does CSV Look Like?

```
name,age,city,salary
John Smith,25,New York,50000
Jane Doe,30,Los Angeles,60000
Bob Johnson,35,Chicago,55000
```

### Reading CSV Files

```php
<?php
// Create a sample CSV file first
$csvData = "name,age,city,job
Alice,28,Boston,Developer
Bob,32,Seattle,Designer
Charlie,29,Austin,Manager
Diana,31,Denver,Analyst";

file_put_contents('employees.csv', $csvData);

// Now read it
function readCSVFile($filename) {
    $employees = [];
    
    if (($file = fopen($filename, 'r')) !== false) {
        // Read the first line (headers)
        $headers = fgetcsv($file);
        
        // Read each data line
        while (($row = fgetcsv($file)) !== false) {
            // Combine headers with data
            $employee = array_combine($headers, $row);
            $employees[] = $employee;
        }
        
        fclose($file);
    }
    
    return $employees;
}

// Use the function
$employees = readCSVFile('employees.csv');

// Display results
foreach ($employees as $employee) {
    echo $employee['name'] . " is " . $employee['age'] . " years old\n";
    echo "Works as: " . $employee['job'] . " in " . $employee['city'] . "\n\n";
}
?>
```

### Writing CSV Files

```php
<?php
// Data to save
$products = [
    ['Product A', 29.99, 15, 'Electronics'],
    ['Product B', 45.50, 8, 'Home'],
    ['Product C', 12.25, 25, 'Books'],
    ['Product D', 89.99, 5, 'Electronics']
];

// Open file for writing
$file = fopen('products.csv', 'w');

// Write headers
$headers = ['Name', 'Price', 'Stock', 'Category'];
fputcsv($file, $headers);

// Write data
foreach ($products as $product) {
    fputcsv($file, $product);
}

fclose($file);

echo "CSV file created successfully!\n";

// Read it back to verify
echo "Contents of products.csv:\n";
echo file_get_contents('products.csv');
?>
```

### Processing CSV Data

```php
<?php
// Let's say we have sales data
$salesData = "date,product,quantity,price
2024-01-01,Laptop,2,999.99
2024-01-01,Mouse,5,25.50
2024-01-02,Laptop,1,999.99
2024-01-02,Keyboard,3,75.00
2024-01-03,Mouse,2,25.50";

file_put_contents('sales.csv', $salesData);

// Function to analyze sales
function analyzeSales($filename) {
    $sales = [];
    $totalRevenue = 0;
    $productTotals = [];
    
    if (($file = fopen($filename, 'r')) !== false) {
        $headers = fgetcsv($file);
        
        while (($row = fgetcsv($file)) !== false) {
            $sale = array_combine($headers, $row);
            
            // Calculate revenue for this sale
            $revenue = $sale['quantity'] * $sale['price'];
            $totalRevenue += $revenue;
            
            // Track sales by product
            if (!isset($productTotals[$sale['product']])) {
                $productTotals[$sale['product']] = 0;
            }
            $productTotals[$sale['product']] += $sale['quantity'];
            
            $sales[] = $sale;
        }
        
        fclose($file);
    }
    
    return [
        'sales' => $sales,
        'total_revenue' => $totalRevenue,
        'product_totals' => $productTotals
    ];
}

// Analyze the data
$analysis = analyzeSales('sales.csv');

echo "Total Revenue: $" . number_format($analysis['total_revenue'], 2) . "\n\n";
echo "Sales by Product:\n";
foreach ($analysis['product_totals'] as $product => $quantity) {
    echo "$product: $quantity units sold\n";
}
?>
```

---

## PHP Serialization

PHP serialization is like packaging PHP data so it can be stored and unpacked later.

### Basic Serialization

```php
<?php
// PHP data
$userData = [
    'username' => 'john_doe',
    'email' => 'john@example.com',
    'preferences' => [
        'theme' => 'dark',
        'language' => 'en'
    ],
    'last_login' => new DateTime('2024-01-15 10:30:00'),
    'login_count' => 42
];

// Serialize (pack it up)
$serialized = serialize($userData);
echo "Serialized data:\n" . $serialized . "\n\n";

// Store in file (like saving to session or cache)
file_put_contents('user_data.ser', $serialized);

// Later, load and unserialize (unpack it)
$loadedData = file_get_contents('user_data.ser');
$restored = unserialize($loadedData);

echo "Restored data:\n";
print_r($restored);

// Use the restored data
echo "Username: " . $restored['username'] . "\n";
echo "Last login: " . $restored['last_login']->format('Y-m-d H:i:s') . "\n";
?>
```

### When to Use PHP Serialization

```php
<?php
// Good for: Session data, caching, temporary storage
session_start();

// Store complex data in session
$_SESSION['cart'] = [
    'items' => [
        ['id' => 1, 'name' => 'Laptop', 'price' => 999.99, 'qty' => 1],
        ['id' => 2, 'name' => 'Mouse', 'price' => 25.50, 'qty' => 2]
    ],
    'total' => 1050.99,
    'created' => new DateTime()
];

// PHP automatically serializes/unserializes session data
echo "Cart total: $" . $_SESSION['cart']['total'];

// For file caching
function cacheExpensiveOperation($key, $callback, $ttl = 3600) {
    $cacheFile = "cache_{$key}.ser";
    
    // Check if cache exists and is not expired
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $ttl) {
        return unserialize(file_get_contents($cacheFile));
    }
    
    // Generate new data
    $data = $callback();
    
    // Cache it
    file_put_contents($cacheFile, serialize($data));
    
    return $data;
}

// Usage
$expensiveData = cacheExpensiveOperation('user_stats', function() {
    // Simulate expensive operation
    sleep(2); // This would be a database query or complex calculation
    return [
        'total_users' => 10000,
        'active_users' => 2500,
        'generated_at' => time()
    ];
});

print_r($expensiveData);
?>
```

---

## When to Use Which Format

### Quick Decision Guide

```php
<?php
// Scenario examples with recommendations

// 1. Website configuration
$config = [
    'database_host' => 'localhost',
    'debug_mode' => false,
    'max_users' => 1000
];
// Recommendation: JSON (easy to read and edit)
file_put_contents('config.json', json_encode($config, JSON_PRETTY_PRINT));

// 2. User data export for Excel
$users = [
    ['John', 'john@email.com', '2024-01-01'],
    ['Jane', 'jane@email.com', '2024-01-02']
];
// Recommendation: CSV (Excel can open it directly)
$file = fopen('users.csv', 'w');
fputcsv($file, ['Name', 'Email', 'Registered']);
foreach ($users as $user) {
    fputcsv($file, $user);
}
fclose($file);

// 3. Complex document structure
$document = [
    'title' => 'My Document',
    'sections' => [
        ['heading' => 'Introduction', 'content' => 'Welcome...'],
        ['heading' => 'Chapter 1', 'content' => 'In this chapter...']
    ]
];
// Recommendation: XML (better for documents with metadata)
$xml = new SimpleXMLElement('<document></document>');
$xml->addChild('title', $document['title']);
$sections = $xml->addChild('sections');
foreach ($document['sections'] as $section) {
    $sec = $sections->addChild('section');
    $sec->addChild('heading', $section['heading']);
    $sec->addChild('content', $section['content']);
}
$xml->asXML('document.xml');

// 4. Temporary PHP object storage
class User {
    public $name;
    public $loginTime;
    
    public function __construct($name) {
        $this->name = $name;
        $this->loginTime = new DateTime();
    }
}

$user = new User('Alice');
// Recommendation: PHP serialize (preserves object types)
file_put_contents('temp_user.ser', serialize($user));
?>
```

### Format Comparison Table

| Format | Best For | Pros | Cons |
|--------|----------|------|------|
| **JSON** | Configuration, web data | Easy to read, widely supported | Limited data types |
| **XML** | Documents, complex structures | Very flexible, metadata support | Verbose, complex |
| **CSV** | Tabular data, spreadsheets | Simple, Excel compatible | Only flat data |
| **PHP Serialize** | Caching, sessions | Preserves PHP types | Only works with PHP |

---

## Common Mistakes to Avoid

### 1. Not Handling Errors

```php
<?php
// BAD: No error checking
$data = json_decode($jsonString, true);
echo $data['name']; // Might crash if JSON is invalid

// GOOD: Always check for errors
$data = json_decode($jsonString, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON Error: " . json_last_error_msg();
    exit;
}
echo $data['name'];
?>
```

### 2. Wrong Data Types

```php
<?php
// BAD: Assuming data types
$userAge = json_decode('{"age": "25"}', true);
if ($userAge['age'] > 18) { // String comparison!
    echo "Adult";
}

// GOOD: Validate and convert
$userAge = json_decode('{"age": "25"}', true);
$age = (int)$userAge['age']; // Convert to integer
if ($age > 18) {
    echo "Adult";
}
?>
```

### 3. Security Issues

```php
<?php
// BAD: Trusting user data
$userInput = $_POST['data']; // Could be malicious
$data = unserialize($userInput); // DANGEROUS!

// GOOD: Validate input format
if (isset($_POST['json_data'])) {
    try {
        $data = json_decode($_POST['json_data'], true, 512, JSON_THROW_ON_ERROR);
        // Process safe JSON data
    } catch (JsonException $e) {
        echo "Invalid data format";
    }
}
?>
```

### 4. Memory Issues with Large Files

```php
<?php
// BAD: Loading huge files into memory
$hugeCsv = file_get_contents('10gb_file.csv'); // Will crash!

// GOOD: Process line by line
function processLargeCsv($filename) {
    if (($file = fopen($filename, 'r')) !== false) {
        $headers = fgetcsv($file);
        
        while (($row = fgetcsv($file)) !== false) {
            // Process one row at a time
            $data = array_combine($headers, $row);
            processRow($data);
        }
        
        fclose($file);
    }
}

function processRow($data) {
    // Do something with each row
    echo "Processing: " . $data['name'] . "\n";
}
?>
```

---

## Practice Exercises

### Exercise 1: Student Grade Manager

Create a program that manages student grades using JSON:

```php
<?php
// Exercise 1: Create a student grade manager

// 1. Create student data
$students = [
    [
        'name' => 'Alice Johnson',
        'student_id' => 'S001',
        'grades' => [
            'Math' => 85,
            'Science' => 92,
            'English' => 78
        ]
    ],
    [
        'name' => 'Bob Smith',
        'student_id' => 'S002',
        'grades' => [
            'Math' => 76,
            'Science' => 84,
            'English' => 88
        ]
    ]
];

// 2. Save to JSON file
// YOUR CODE HERE: Save $students to 'students.json'

// 3. Load from JSON file
// YOUR CODE HERE: Load the data back from 'students.json'

// 4. Calculate averages
// YOUR CODE HERE: Add an 'average' field to each student

// 5. Find the best student
// YOUR CODE HERE: Find which student has the highest average

// Solution provided below...
?>
```

### Exercise 2: Product Catalog with CSV

```php
<?php
// Exercise 2: Create a product catalog system

// 1. Create CSV with product data
$products = [
    ['ID', 'Name', 'Price', 'Category', 'Stock'],
    [1, 'Laptop', 999.99, 'Electronics', 5],
    [2, 'Chair', 89.99, 'Furniture', 12],
    [3, 'Book', 24.99, 'Education', 30]
];

// YOUR TASKS:
// 1. Write this data to 'products.csv'
// 2. Read it back and display all products
// 3. Find products under $50
// 4. Calculate total inventory value
// 5. Find the most expensive product

// Start coding here...
?>
```

### Exercise 3: Configuration Manager

```php
<?php
// Exercise 3: Build a simple configuration manager

class SimpleConfig {
    private $config = [];
    
    // YOUR TASKS:
    // 1. Add method to load JSON config file
    // 2. Add method to get config value (with default)
    // 3. Add method to set config value
    // 4. Add method to save changes back to file
    
    public function load($filename) {
        // Load JSON config file
        // YOUR CODE HERE
    }
    
    public function get($key, $default = null) {
        // Get config value, return default if not found
        // YOUR CODE HERE
    }
    
    public function set($key, $value) {
        // Set config value
        // YOUR CODE HERE
    }
    
    public function save($filename) {
        // Save config back to JSON file
        // YOUR CODE HERE
    }
}

// Test your configuration manager:
$config = new SimpleConfig();
// Test loading, getting, setting, and saving
?>
```

### Solutions

#### Exercise 1 Solution:

```php
<?php
// Exercise 1 Solution: Student Grade Manager

$students = [
    [
        'name' => 'Alice Johnson',
        'student_id' => 'S001',
        'grades' => [
            'Math' => 85,
            'Science' => 92,
            'English' => 78
        ]
    ],
    [
        'name' => 'Bob Smith',
        'student_id' => 'S002',
        'grades' => [
            'Math' => 76,
            'Science' => 84,
            'English' => 88
        ]
    ]
];

// 2. Save to JSON
file_put_contents('students.json', json_encode($students, JSON_PRETTY_PRINT));

// 3. Load from JSON
$loadedStudents = json_decode(file_get_contents('students.json'), true);

// 4. Calculate averages
foreach ($loadedStudents as &$student) {
    $total = array_sum($student['grades']);
    $count = count($student['grades']);
    $student['average'] = round($total / $count, 2);
}

// 5. Find best student
$bestStudent = $loadedStudents[0];
foreach ($loadedStudents as $student) {
    if ($student['average'] > $bestStudent['average']) {
        $bestStudent = $student;
    }
}

echo "Best student: " . $bestStudent['name'] . " with average: " . $bestStudent['average'];
?>
```

These exercises will help you practice working with semi-structured data formats in real-world scenarios. Start with Exercise 1 and work your way through them to build your confidence!

---

## Summary

Semi-structured data formats are essential tools for storing and exchanging information in PHP:

- **JSON**: Your go-to format for most data storage needs
- **XML**: When you need complex document structures
- **CSV**: Perfect for simple tabular data and spreadsheet compatibility
- **PHP Serialize**: Best for temporary storage of PHP-specific data

**Key Points to Remember:**
1. Always handle errors when parsing data
2. Validate data after loading it
3. Choose the right format for your specific needs
4. Be careful with large files and memory usage
5. Never trust user input without validation

Start with JSON for most projects, and expand to other formats as your needs grow!