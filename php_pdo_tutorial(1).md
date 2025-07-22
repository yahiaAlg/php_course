# PHP PDO Complete Beginner's Tutorial

## Table of Contents
1. [What is PDO?](#what-is-pdo)
2. [Setting Up PDO Connection](#setting-up-pdo-connection)
3. [Basic CRUD Operations](#basic-crud-operations)
4. [Prepared Statements](#prepared-statements)
5. [Error Handling](#error-handling)
6. [Transactions](#transactions)
7. [Working with Different Data Types](#working-with-different-data-types)
8. [Advanced PDO Features](#advanced-pdo-features)
9. [Best Practices](#best-practices)

---

## What is PDO?

**PDO (PHP Data Objects)** is a database abstraction layer built into PHP that provides a consistent interface for accessing databases. Think of it as a translator that allows your PHP code to communicate with different database systems using the same commands.

### Why Use PDO?

**Security**: PDO provides excellent protection against SQL injection attacks through prepared statements.

**Flexibility**: You can switch between different database systems (MySQL, PostgreSQL, SQLite) with minimal code changes.

**Object-Oriented**: PDO uses modern object-oriented programming principles, making code more organized and maintainable.

**Performance**: Built-in prepared statements improve performance for repeated queries.

### PDO vs Other Database Extensions

Before PDO, PHP developers used extensions like `mysql_*` functions or `mysqli`. PDO is superior because:
- The old `mysql_*` functions are deprecated and removed
- PDO works with multiple database types
- Better error handling and security features

---

## Setting Up PDO Connection

### Basic Connection Syntax

The PDO connection follows this pattern:
```php
$pdo = new PDO($dsn, $username, $password, $options);
```

**DSN (Data Source Name)**: A string that contains the information required to connect to the database.

### MySQL Connection Example

```php
<?php
$host = 'localhost';
$dbname = 'my_database';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    echo "Connected successfully!";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
```

### Understanding the Connection Components

**Host**: The server where your database is located (usually 'localhost' for local development).

**Database Name**: The specific database you want to connect to.

**Username/Password**: Your database credentials.

### Connection Options

PDO allows you to set various options to control behavior:

```php
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password, $options);
```

**PDO::ATTR_ERRMODE**: Controls how PDO reports errors.
- `PDO::ERRMODE_EXCEPTION`: Throws exceptions (recommended)
- `PDO::ERRMODE_WARNING`: Issues PHP warnings
- `PDO::ERRMODE_SILENT`: Sets error codes silently

**PDO::ATTR_DEFAULT_FETCH_MODE**: Sets the default way to fetch results.

**PDO::ATTR_EMULATE_PREPARES**: When false, uses real prepared statements.

---

## Basic CRUD Operations

CRUD stands for Create, Read, Update, Delete - the four basic operations you'll perform on database data.

### CREATE - Inserting Data

**Basic Insert**:
```php
$sql = "INSERT INTO users (name, email) VALUES ('John Doe', 'john@example.com')";
$pdo->exec($sql);
echo "New record created successfully";
```

The `exec()` method is used for SQL statements that don't return results (like INSERT, UPDATE, DELETE).

**Getting the Last Inserted ID**:
```php
$sql = "INSERT INTO users (name, email) VALUES ('Jane Smith', 'jane@example.com')";
$pdo->exec($sql);
$lastId = $pdo->lastInsertId();
echo "New record created with ID: " . $lastId;
```

### READ - Selecting Data

**Basic Select**:
```php
$sql = "SELECT * FROM users";
$stmt = $pdo->query($sql);

while ($row = $stmt->fetch()) {
    echo "ID: " . $row['id'] . " - Name: " . $row['name'] . "<br>";
}
```

The `query()` method returns a PDOStatement object that you can iterate through.

**Fetch Methods**:
- `fetch()`: Returns one row at a time
- `fetchAll()`: Returns all rows as an array
- `fetchColumn()`: Returns a single column value

```php
// Fetch all rows
$users = $stmt->fetchAll();
foreach ($users as $user) {
    echo $user['name'] . "<br>";
}

// Fetch single column
$sql = "SELECT name FROM users WHERE id = 1";
$stmt = $pdo->query($sql);
$name = $stmt->fetchColumn();
echo "User name: " . $name;
```

### UPDATE - Modifying Data

```php
$sql = "UPDATE users SET email = 'newemail@example.com' WHERE id = 1";
$stmt = $pdo->exec($sql);
echo "Number of records updated: " . $stmt;
```

The `exec()` method returns the number of affected rows.

### DELETE - Removing Data

```php
$sql = "DELETE FROM users WHERE id = 1";
$stmt = $pdo->exec($sql);
echo "Number of records deleted: " . $stmt;
```

---

## Prepared Statements

Prepared statements are one of PDO's most important features. They separate SQL logic from data, preventing SQL injection attacks and improving performance.

### Why Use Prepared Statements?

**Security**: User input is treated as data, not executable code.

**Performance**: The database can optimize and cache the query plan.

**Convenience**: Easier to work with dynamic data.

### Basic Prepared Statement

```php
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute(['john@example.com']);
$user = $stmt->fetch();
```

### Understanding the Process

1. **Prepare**: The database parses and compiles the SQL statement
2. **Bind**: Parameters are bound to placeholders
3. **Execute**: The statement runs with the provided data

### Placeholder Types

**Positional Placeholders (?)**:
```php
$sql = "INSERT INTO users (name, email, age) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute(['John Doe', 'john@example.com', 30]);
```

**Named Placeholders (:name)**:
```php
$sql = "INSERT INTO users (name, email, age) VALUES (:name, :email, :age)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':name' => 'John Doe',
    ':email' => 'john@example.com',
    ':age' => 30
]);
```

### Parameter Binding

You can also bind parameters individually:

```php
$sql = "SELECT * FROM users WHERE age > :age AND city = :city";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':age', $age, PDO::PARAM_INT);
$stmt->bindParam(':city', $city, PDO::PARAM_STR);

$age = 25;
$city = 'New York';
$stmt->execute();
```

**Parameter Types**:
- `PDO::PARAM_INT`: Integer values
- `PDO::PARAM_STR`: String values
- `PDO::PARAM_BOOL`: Boolean values
- `PDO::PARAM_NULL`: NULL values

---

## Error Handling

Proper error handling is crucial for debugging and maintaining secure applications.

### Exception-Based Error Handling

```php
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "SELECT * FROM non_existent_table";
    $stmt = $pdo->query($sql);
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
```

### Checking for Errors

```php
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([1])) {
    $user = $stmt->fetch();
    if ($user) {
        echo "User found: " . $user['name'];
    } else {
        echo "No user found";
    }
} else {
    echo "Query failed";
}
```

### Error Information

```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([1]);

// Check for errors
if ($stmt->errorCode() != '00000') {
    $error = $stmt->errorInfo();
    echo "Error: " . $error[2];
}
```

---

## Transactions

Transactions ensure data integrity by grouping multiple database operations together. Either all operations succeed, or none of them do.

### Basic Transaction Example

```php
try {
    $pdo->beginTransaction();
    
    // Transfer money between accounts
    $pdo->exec("UPDATE accounts SET balance = balance - 100 WHERE id = 1");
    $pdo->exec("UPDATE accounts SET balance = balance + 100 WHERE id = 2");
    
    $pdo->commit();
    echo "Transaction completed successfully";
    
} catch(Exception $e) {
    $pdo->rollback();
    echo "Transaction failed: " . $e->getMessage();
}
```

### Understanding Transaction Methods

**beginTransaction()**: Starts a new transaction.

**commit()**: Saves all changes made during the transaction.

**rollback()**: Cancels all changes made during the transaction.

### Practical Transaction Example

```php
function transferMoney($pdo, $fromAccount, $toAccount, $amount) {
    try {
        $pdo->beginTransaction();
        
        // Check if sender has sufficient balance
        $stmt = $pdo->prepare("SELECT balance FROM accounts WHERE id = ?");
        $stmt->execute([$fromAccount]);
        $balance = $stmt->fetchColumn();
        
        if ($balance < $amount) {
            throw new Exception("Insufficient funds");
        }
        
        // Deduct from sender
        $stmt = $pdo->prepare("UPDATE accounts SET balance = balance - ? WHERE id = ?");
        $stmt->execute([$amount, $fromAccount]);
        
        // Add to receiver
        $stmt = $pdo->prepare("UPDATE accounts SET balance = balance + ? WHERE id = ?");
        $stmt->execute([$amount, $toAccount]);
        
        $pdo->commit();
        return true;
        
    } catch(Exception $e) {
        $pdo->rollback();
        throw $e;
    }
}
```

---

## Working with Different Data Types

PDO handles various data types automatically, but understanding how to work with them explicitly is important.

### Inserting Different Data Types

```php
$sql = "INSERT INTO products (name, price, in_stock, created_at) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    'Laptop',           // String
    999.99,            // Float
    true,              // Boolean
    date('Y-m-d H:i:s') // DateTime
]);
```

### Handling NULL Values

```php
$sql = "INSERT INTO users (name, email, phone) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    'John Doe',
    'john@example.com',
    null  // NULL value
]);
```

### Working with BLOB Data

```php
// Storing file data
$sql = "INSERT INTO files (name, content) VALUES (?, ?)";
$stmt = $pdo->prepare($sql);

$fileContent = file_get_contents('image.jpg');
$stmt->execute(['image.jpg', $fileContent]);

// Retrieving file data
$sql = "SELECT content FROM files WHERE name = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute(['image.jpg']);
$content = $stmt->fetchColumn();

file_put_contents('downloaded_image.jpg', $content);
```

---

## Advanced PDO Features

### Fetch Modes

PDO provides different ways to fetch data:

```php
$sql = "SELECT * FROM users";
$stmt = $pdo->query($sql);

// Associative array (default)
$user = $stmt->fetch(PDO::FETCH_ASSOC);
echo $user['name'];

// Numeric array
$user = $stmt->fetch(PDO::FETCH_NUM);
echo $user[1]; // Second column

// Object
$user = $stmt->fetch(PDO::FETCH_OBJ);
echo $user->name;

// Into custom class
class User {
    public $id;
    public $name;
    public $email;
}

$stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
$user = $stmt->fetch();
echo $user->name;
```

### Column Information

```php
$sql = "SELECT * FROM users LIMIT 1";
$stmt = $pdo->query($sql);

$columnCount = $stmt->columnCount();
echo "Number of columns: " . $columnCount;

for ($i = 0; $i < $columnCount; $i++) {
    $column = $stmt->getColumnMeta($i);
    echo "Column " . $i . ": " . $column['name'] . " (" . $column['native_type'] . ")";
}
```

### Buffered vs Unbuffered Queries

```php
// Buffered (default) - all results loaded into memory
$stmt = $pdo->query("SELECT * FROM users");

// Unbuffered - results fetched one by one
$pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
$stmt = $pdo->query("SELECT * FROM users");
```

---

## Best Practices

### 1. Always Use Prepared Statements for User Input

```php
// WRONG - Vulnerable to SQL injection
$sql = "SELECT * FROM users WHERE email = '" . $_POST['email'] . "'";

// CORRECT - Safe from SQL injection
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_POST['email']]);
```

### 2. Use Try-Catch for Error Handling

```php
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    // Show user-friendly message
    echo "Sorry, something went wrong. Please try again.";
}
```

### 3. Create a Database Connection Class

```php
class Database {
    private $host = 'localhost';
    private $dbname = 'mydb';
    private $username = 'root';
    private $password = '';
    private $pdo;
    
    public function connect() {
        if ($this->pdo == null) {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            try {
                $this->pdo = new PDO(
                    "mysql:host={$this->host};dbname={$this->dbname}",
                    $this->username,
                    $this->password,
                    $options
                );
            } catch(PDOException $e) {
                throw new Exception("Connection failed: " . $e->getMessage());
            }
        }
        
        return $this->pdo;
    }
}
```

### 4. Use Configuration Files

Store database credentials in a separate configuration file:

```php
// config.php
return [
    'database' => [
        'host' => 'localhost',
        'dbname' => 'mydb',
        'username' => 'root',
        'password' => ''
    ]
];

// Usage
$config = require 'config.php';
$pdo = new PDO(
    "mysql:host={$config['database']['host']};dbname={$config['database']['dbname']}",
    $config['database']['username'],
    $config['database']['password']
);
```

### 5. Validate Data Before Database Operations

```php
function createUser($pdo, $name, $email) {
    // Validate input
    if (empty($name) || empty($email)) {
        throw new Exception("Name and email are required");
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }
    
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        throw new Exception("Email already exists");
    }
    
    // Insert new user
    $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
    return $stmt->execute([$name, $email]);
}
```

### 6. Use Consistent Error Handling

```php
function handleDatabaseError($e) {
    // Log the actual error
    error_log("Database Error: " . $e->getMessage());
    
    // Return user-friendly message
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        return "This record already exists.";
    }
    
    return "A database error occurred. Please try again.";
}
```

---

## Common Pitfalls to Avoid

### 1. Not Using Prepared Statements
Always use prepared statements for dynamic queries to prevent SQL injection.

### 2. Ignoring Error Handling
Always wrap database operations in try-catch blocks.

### 3. Fetching All Results When You Need One
Use `fetch()` instead of `fetchAll()` when you only need one row.

### 4. Not Closing Connections
While PHP automatically closes connections at script end, it's good practice to explicitly close them in long-running scripts.

### 5. Using Wrong Fetch Mode
Choose the appropriate fetch mode for your needs to avoid unnecessary overhead.

---

## Conclusion

PDO is a powerful and secure way to interact with databases in PHP. By following the principles and examples in this tutorial, you'll be able to build robust, secure database-driven applications. Remember to always use prepared statements, handle errors properly, and follow best practices for maintainable code.

The key to mastering PDO is practice - start with simple queries and gradually work your way up to more complex operations. Focus on security, error handling, and code organization from the beginning, and you'll develop good habits that will serve you well in your PHP development journey.