# Simple PHP Exceptions Guide - No OOP

## The Critical Problem: When Simple Errors Kill Your Entire Program

Imagine you're building a website that loads user profiles. Your program tries to read a profile picture file, but the file is missing. **Without exception handling, this tiny "file not found" error will crash your entire application!**

Here's what happens when a simple file error destroys everything:

```php
<?php
echo "Starting user profile system...\n";
echo "Loading user data...\n";
echo "User: John Doe, Email: john@example.com\n";

// This single line will KILL the entire program
$profilePicture = file_get_contents('profile_pictures/john.jpg'); // File doesn't exist!

// NONE of these lines will execute - the program is DEAD
echo "Displaying user dashboard...\n";
echo "Loading recent activities...\n";
echo "Showing friend list...\n";
echo "Profile system ready!\n";
?>
```

**Output:**
```
Starting user profile system...
Loading user data...
User: John Doe, Email: john@example.com

Warning: file_get_contents(profile_pictures/john.jpg): failed to open stream: No such file or directory
```

**The program STOPS here!** Users see a broken website because of one missing image file.

Now watch how exception handling saves the day:

```php
<?php
echo "Starting user profile system...\n";
echo "Loading user data...\n";
echo "User: John Doe, Email: john@example.com\n";

try {
    // Try to load profile picture
    if (!file_exists('profile_pictures/john.jpg')) {
        throw new Exception("Profile picture not found!");
    }
    $profilePicture = file_get_contents('profile_pictures/john.jpg');
    echo "Profile picture loaded successfully!\n";
    
} catch (Exception $e) {
    // Handle the error gracefully
    echo "Notice: " . $e->getMessage() . " Using default picture.\n";
    $profilePicture = file_get_contents('default_avatar.png');
}

// The program CONTINUES running normally!
echo "Displaying user dashboard...\n";
echo "Loading recent activities...\n";
echo "Showing friend list...\n";
echo "Profile system ready!\n";
?>
```

**Output:**
```
Starting user profile system...
Loading user data...
User: John Doe, Email: john@example.com
Notice: Profile picture not found! Using default picture.
Displaying user dashboard...
Loading recent activities...
Showing friend list...
Profile system ready!
```

**See the difference?** Exception handling turns a program-killing error into a minor inconvenience that gets handled automatically!

---

## Table of Contents
1. [What Are Exceptions?](#what-are-exceptions)
2. [The Problem Without Exceptions](#the-problem-without-exceptions)
3. [Understanding Try-Catch-Finally Blocks](#understanding-try-catch-finally-blocks)
4. [Built-in PHP Exceptions You Should Know](#built-in-php-exceptions-you-should-know)
5. [Creating Your Own Exception Messages](#creating-your-own-exception-messages)
6. [Simple Real-World Examples](#simple-real-world-examples)
7. [Making Your Program Error-Resistant](#making-your-program-error-resistant)

---

## What Are Exceptions?

Now that you've seen the critical problem, let's understand the solution.

Exceptions are PHP's emergency response system. Instead of letting your program die from a single error, exceptions allow your program to say: **"I hit a problem, but I know how to handle it and keep going."**

**Without exceptions:** One small error = entire program crashes
**With exceptions:** Errors are caught and handled gracefully while the program continues

### Simple Example

```php
<?php
// Function that might cause problems
function divide($a, $b) {
    if ($b == 0) {
        throw new Exception("Cannot divide by zero!");
    }
    return $a / $b;
}

// Safe way to use the function
try {
    $result = divide(10, 2);
    echo "10 ÷ 2 = $result\n";
    
    $result2 = divide(10, 0); // This will cause an exception
    echo "This line won't run\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "But the program keeps running!\n";
}

echo "Program continues here!\n";
?>
```

**Output:**
```
10 ÷ 2 = 5
Error: Cannot divide by zero!
But the program keeps running!
Program continues here!
```

---

## The Problem Without Exceptions

Let's see why we need exceptions:

### The Old Way (Confusing)

```php
<?php
function checkAge($age) {
    if ($age < 0) return -1;      // What does -1 mean?
    if ($age > 150) return -2;    // What does -2 mean?
    return $age;                  // Is this success?
}

$result = checkAge(-5);
if ($result < 0) {
    echo "Something is wrong, but I don't know what!";
}
?>
```

### The New Way (Clear)

```php
<?php
function checkAge($age) {
    if ($age < 0) {
        throw new Exception("Age cannot be negative! You entered: $age");
    }
    if ($age > 150) {
        throw new Exception("Age cannot be over 150! You entered: $age");
    }
    return $age;
}

try {
    $validAge = checkAge(-5);
    echo "Age is: $validAge";
} catch (Exception $e) {
    echo "Age Error: " . $e->getMessage();
}
?>
```

**Output:**
```
Age Error: Age cannot be negative! You entered: -5
```

---

## Understanding Try-Catch-Finally Blocks

Think of these blocks like a safety system:

- **try** = "Let me try this risky thing"
- **catch** = "If something goes wrong, I'll handle it"
- **finally** = "No matter what happens, I'll always do this"

### Basic Structure

```php
<?php
try {
    // Code that might fail goes here
    echo "Trying something risky...\n";
    
} catch (Exception $e) {
    // Handle the error here
    echo "Oops! " . $e->getMessage() . "\n";
    
} finally {
    // This ALWAYS runs, even if there's an error
    echo "Cleaning up...\n";
}
?>
```

### Step-by-Step Example

```php
<?php
function processNumber($number) {
    echo "Starting to process number: $number\n";
    
    if (!is_numeric($number)) {
        throw new Exception("'$number' is not a valid number!");
    }
    
    if ($number < 0) {
        throw new Exception("Number must be positive!");
    }
    
    return $number * 2;
}

// Test 1: Success
echo "=== Test 1: Valid Number ===\n";
try {
    $result = processNumber(5);
    echo "Result: $result\n";
    echo "Processing completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    
} finally {
    echo "Test 1 finished.\n\n";
}

// Test 2: Error
echo "=== Test 2: Invalid Number ===\n";
try {
    $result = processNumber("hello");
    echo "Result: $result\n";
    echo "This won't print!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    
} finally {
    echo "Test 2 finished.\n\n";
}

echo "Both tests are done. Program still running!\n";
?>
```

**Output:**
```
=== Test 1: Valid Number ===
Starting to process number: 5
Result: 10
Processing completed!
Test 1 finished.

=== Test 2: Invalid Number ===
Starting to process number: hello
Error: 'hello' is not a valid number!
Test 2 finished.

Both tests are done. Program still running!
```

---

## Built-in PHP Exceptions You Should Know

PHP has several built-in exceptions for common problems:

### 1. Exception (The Basic One)

Use for general errors:

```php
<?php
function checkPassword($password) {
    if (empty($password)) {
        throw new Exception("Password cannot be empty!");
    }
    
    if (strlen($password) < 6) {
        throw new Exception("Password must be at least 6 characters long!");
    }
    
    return "Password is good!";
}

try {
    echo checkPassword("123") . "\n";
} catch (Exception $e) {
    echo "Password Problem: " . $e->getMessage() . "\n";
}
?>
```

### 2. InvalidArgumentException

Use when someone gives your function the wrong type of data:

```php
<?php
function calculateSquare($number) {
    if (!is_numeric($number)) {
        throw new InvalidArgumentException("I need a number, but you gave me: " . gettype($number));
    }
    
    return $number * $number;
}

// Test different inputs
$testInputs = [5, "hello", true, 3.14, null];

foreach ($testInputs as $input) {
    try {
        $result = calculateSquare($input);
        echo "Square of $input = $result\n";
        
    } catch (InvalidArgumentException $e) {
        echo "Bad Input: " . $e->getMessage() . "\n";
    }
}
?>
```

**Output:**
```
Square of 5 = 25
Bad Input: I need a number, but you gave me: string
Bad Input: I need a number, but you gave me: boolean
Square of 3.14 = 9.8596
Bad Input: I need a number, but you gave me: NULL
```

### 3. OutOfBoundsException

Use when trying to access something that doesn't exist:

```php
<?php
function getMonth($monthNumber) {
    $months = [
        1 => "January", 2 => "February", 3 => "March",
        4 => "April", 5 => "May", 6 => "June",
        7 => "July", 8 => "August", 9 => "September",
        10 => "October", 11 => "November", 12 => "December"
    ];
    
    if ($monthNumber < 1 || $monthNumber > 12) {
        throw new OutOfBoundsException("Month number must be 1-12, but you gave me: $monthNumber");
    }
    
    return $months[$monthNumber];
}

// Test different month numbers
$testMonths = [1, 6, 13, 0, -5];

foreach ($testMonths as $month) {
    try {
        echo "Month $month is: " . getMonth($month) . "\n";
    } catch (OutOfBoundsException $e) {
        echo "Invalid Month: " . $e->getMessage() . "\n";
    }
}
?>
```

### 4. RuntimeException

Use for problems that happen while your program is running:

```php
<?php
function readFile($filename) {
    if (!file_exists($filename)) {
        throw new RuntimeException("File '$filename' does not exist!");
    }
    
    $content = file_get_contents($filename);
    if ($content === false) {
        throw new RuntimeException("Could not read file '$filename'! Check permissions.");
    }
    
    return $content;
}

try {
    $content = readFile("missing.txt");
    echo "File content: $content\n";
    
} catch (RuntimeException $e) {
    echo "File Problem: " . $e->getMessage() . "\n";
    echo "Using default content instead.\n";
    $content = "Default content";
}
?>
```

---

## Creating Your Own Exception Messages

You can make your own specific error messages by throwing exceptions with custom messages:

### Simple Custom Messages

```php
<?php
function validateEmail($email) {
    if (empty($email)) {
        throw new Exception("EMAIL_REQUIRED: Please enter an email address");
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("EMAIL_INVALID: '$email' is not a valid email format");
    }
    
    return $email;
}

function validateAge($age) {
    if (empty($age)) {
        throw new Exception("AGE_REQUIRED: Please enter your age");
    }
    
    if (!is_numeric($age)) {
        throw new Exception("AGE_INVALID: Age must be a number");
    }
    
    if ($age < 13) {
        throw new Exception("AGE_TOO_YOUNG: You must be at least 13 years old");
    }
    
    if ($age > 120) {
        throw new Exception("AGE_TOO_OLD: Please enter a realistic age");
    }
    
    return (int)$age;
}

// Test user registration
$users = [
    ['email' => '', 'age' => '25'],
    ['email' => 'john@example.com', 'age' => '10'],
    ['email' => 'invalid-email', 'age' => '25'],
    ['email' => 'jane@example.com', 'age' => '30'],
];

foreach ($users as $index => $user) {
    echo "\n--- Testing User " . ($index + 1) . " ---\n";
    
    try {
        $validEmail = validateEmail($user['email']);
        $validAge = validateAge($user['age']);
        
        echo "✓ User is valid!\n";
        echo "  Email: $validEmail\n";
        echo "  Age: $validAge\n";
        
    } catch (Exception $e) {
        $message = $e->getMessage();
        
        if (strpos($message, 'EMAIL_') === 0) {
            echo "❌ Email Error: " . substr($message, strpos($message, ':') + 2) . "\n";
        } elseif (strpos($message, 'AGE_') === 0) {
            echo "❌ Age Error: " . substr($message, strpos($message, ':') + 2) . "\n";
        } else {
            echo "❌ Unknown Error: $message\n";
        }
    }
}
?>
```

### Using Different Exception Types

```php
<?php
function processOrder($items, $payment) {
    // Validate items
    if (empty($items)) {
        throw new InvalidArgumentException("Order must have at least one item!");
    }
    
    if (!is_array($items)) {
        throw new InvalidArgumentException("Items must be a list!");
    }
    
    // Check payment method
    $validPayments = ['cash', 'card', 'paypal'];
    if (!in_array($payment, $validPayments)) {
        throw new OutOfBoundsException("Payment method '$payment' is not supported!");
    }
    
    // Calculate total
    $total = 0;
    foreach ($items as $item) {
        if (!is_numeric($item)) {
            throw new RuntimeException("All item prices must be numbers!");
        }
        $total += $item;
    }
    
    return "Order processed: " . count($items) . " items, total: $" . number_format($total, 2) . ", payment: $payment";
}

// Test different orders
$orders = [
    [[], 'cash'],                           // No items
    ['not an array', 'cash'],               // Wrong item type
    [[10, 20, 30], 'bitcoin'],             // Invalid payment
    [[10, 'invalid', 30], 'cash'],         // Invalid price
    [[15.50, 8.25, 12.00], 'card'],       // Valid order
];

foreach ($orders as $index => $order) {
    echo "\n--- Order " . ($index + 1) . " ---\n";
    
    try {
        $result = processOrder($order[0], $order[1]);
        echo "✓ $result\n";
        
    } catch (InvalidArgumentException $e) {
        echo "❌ Input Error: " . $e->getMessage() . "\n";
        
    } catch (OutOfBoundsException $e) {
        echo "❌ Option Error: " . $e->getMessage() . "\n";
        
    } catch (RuntimeException $e) {
        echo "❌ Processing Error: " . $e->getMessage() . "\n";
        
    } catch (Exception $e) {
        echo "❌ Unexpected Error: " . $e->getMessage() . "\n";
    }
}
?>
```

---

## Simple Real-World Examples

### Example 1: Calculator Function

```php
<?php
function simpleCalculator($num1, $num2, $operation) {
    // Check if numbers are valid
    if (!is_numeric($num1)) {
        throw new InvalidArgumentException("First number '$num1' is not valid!");
    }
    
    if (!is_numeric($num2)) {
        throw new InvalidArgumentException("Second number '$num2' is not valid!");
    }
    
    // Perform calculation
    switch ($operation) {
        case '+':
            return $num1 + $num2;
            
        case '-':
            return $num1 - $num2;
            
        case '*':
            return $num1 * $num2;
            
        case '/':
            if ($num2 == 0) {
                throw new RuntimeException("Cannot divide by zero!");
            }
            return $num1 / $num2;
            
        default:
            throw new OutOfBoundsException("Operation '$operation' is not supported! Use: +, -, *, /");
    }
}

// Test the calculator
$calculations = [
    [10, 5, '+'],
    [10, 0, '/'],
    ['hello', 5, '+'],
    [10, 5, '%'],
    [20, 4, '*'],
];

foreach ($calculations as $index => $calc) {
    echo "\nCalculation " . ($index + 1) . ": {$calc[0]} {$calc[2]} {$calc[1]}\n";
    
    try {
        $result = simpleCalculator($calc[0], $calc[1], $calc[2]);
        echo "Result: $result\n";
        
    } catch (InvalidArgumentException $e) {
        echo "Input Error: " . $e->getMessage() . "\n";
        
    } catch (RuntimeException $e) {
        echo "Math Error: " . $e->getMessage() . "\n";
        
    } catch (OutOfBoundsException $e) {
        echo "Operation Error: " . $e->getMessage() . "\n";
    }
}
?>
```

### Example 2: Simple File Reader

```php
<?php
function safeReadFile($filename) {
    try {
        // Check if file exists
        if (!file_exists($filename)) {
            throw new Exception("File '$filename' not found!");
        }
        
        // Check if we can read it
        if (!is_readable($filename)) {
            throw new Exception("File '$filename' cannot be read!");
        }
        
        // Check file size (max 1MB for this example)
        $size = filesize($filename);
        if ($size > 1024 * 1024) {
            throw new Exception("File '$filename' is too large (over 1MB)!");
        }
        
        // Read the file
        $content = file_get_contents($filename);
        if ($content === false) {
            throw new RuntimeException("Failed to read file '$filename'!");
        }
        
        echo "✓ Successfully read file '$filename' (" . strlen($content) . " characters)\n";
        return $content;
        
    } catch (Exception $e) {
        echo "❌ File Error: " . $e->getMessage() . "\n";
        echo "→ Using default content instead.\n";
        return "Default file content";
    }
}

// Test reading files
echo "=== File Reading Tests ===\n";

// Create a test file
file_put_contents('test.txt', 'Hello, this is a test file!');

$filesToTest = ['test.txt', 'missing.txt', 'test.txt'];

foreach ($filesToTest as $index => $file) {
    echo "\nTest " . ($index + 1) . ": Reading '$file'\n";
    $content = safeReadFile($file);
    echo "Content preview: " . substr($content, 0, 50) . "\n";
}

// Clean up
unlink('test.txt');
?>
```

---

## Making Your Program Error-Resistant

The key is to expect problems and handle them gracefully:

### Always Use Try-Catch for Risky Operations

```php
<?php
// Risky operations that should always be wrapped in try-catch:

// 1. File operations
function processConfigFile() {
    try {
        $config = file_get_contents('config.txt');
        echo "Config loaded!\n";
    } catch (Exception $e) {
        echo "Config error: " . $e->getMessage() . "\n";
        echo "Using defaults...\n";
        $config = "default_setting=true";
    }
    return $config;
}

// 2. User input processing
function processUserInput($input) {
    try {
        if (empty($input)) {
            throw new Exception("Input cannot be empty!");
        }
        
        if (strlen($input) > 100) {
            throw new Exception("Input too long!");
        }
        
        return "Processed: " . htmlspecialchars($input);
        
    } catch (Exception $e) {
        echo "Input error: " . $e->getMessage() . "\n";
        return "Processed: [invalid input]";
    }
}

// 3. Mathematical operations
function safeDivision($a, $b) {
    try {
        if ($b == 0) {
            throw new RuntimeException("Cannot divide by zero!");
        }
        return $a / $b;
        
    } catch (RuntimeException $e) {
        echo "Math error: " . $e->getMessage() . "\n";
        return 0; // Return safe default
    }
}

// Test all functions
echo "=== Error-Resistant Program Demo ===\n";

echo "\n1. Config file processing:\n";
processConfigFile();

echo "\n2. User input processing:\n";
echo processUserInput("Hello World") . "\n";
echo processUserInput("") . "\n";

echo "\n3. Safe division:\n";
echo "10 / 2 = " . safeDivision(10, 2) . "\n";
echo "10 / 0 = " . safeDivision(10, 0) . "\n";

echo "\nProgram completed successfully!\n";
?>
```

### The Finally Block for Cleanup

```php
<?php
function processDataWithCleanup($data) {
    $tempFile = 'temp_processing.txt';
    
    try {
        echo "Starting data processing...\n";
        
        // Create temporary file
        file_put_contents($tempFile, $data);
        echo "Temporary file created.\n";
        
        // Simulate processing that might fail
        if (empty($data)) {
            throw new Exception("Cannot process empty data!");
        }
        
        if (strlen($data) < 5) {
            throw new Exception("Data too short to process!");
        }
        
        // Process the data
        $result = "PROCESSED: " . strtoupper($data);
        echo "Processing successful!\n";
        return $result;
        
    } catch (Exception $e) {
        echo "Processing failed: " . $e->getMessage() . "\n";
        return "PROCESSING FAILED";
        
    } finally {
        // This ALWAYS runs, even if there was an error
        if (file_exists($tempFile)) {
            unlink($tempFile);
            echo "Temporary file cleaned up.\n";
        }
        echo "Processing attempt completed.\n";
    }
}

// Test with different data
echo "=== Cleanup Demo ===\n";

echo "\nTest 1: Valid data\n";
$result1 = processDataWithCleanup("Hello World");
echo "Result: $result1\n";

echo "\nTest 2: Empty data\n";
$result2 = processDataWithCleanup("");
echo "Result: $result2\n";

echo "\nTest 3: Short data\n";
$result3 = processDataWithCleanup("Hi");
echo "Result: $result3\n";

echo "\nAll tests completed!\n";
?>
```

---

## Key Takeaways

**Remember these important points:**

1. **Exceptions prevent crashes** - Your program keeps running even when something goes wrong

2. **Use try-catch for risky code** - File operations, user input, calculations, etc.

3. **Different exception types for different problems:**
   - `Exception` - General problems
   - `InvalidArgumentException` - Wrong input type
   - `OutOfBoundsException` - Accessing something that doesn't exist
   - `RuntimeException` - Problems during execution

4. **Always provide clear error messages** - Tell users what went wrong and why

5. **Use finally for cleanup** - Code that must always run (like closing files)

6. **Don't ignore exceptions** - Always handle them or log them

**Simple Template to Remember:**
```php
<?php
try {
    // Risky code here
    
} catch (Exception $e) {
    // Handle the error
    echo "Error: " . $e->getMessage();
    
} finally {
    // Cleanup code (optional)
    echo "Always runs";
}
?>
```

With exceptions, your PHP programs become much more reliable and user-friendly!