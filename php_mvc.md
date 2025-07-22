# Complete PHP MVC Tutorial: From Zero to Hero

## Introduction to PHP MVC Architecture

Model-View-Controller (MVC) is a design pattern that separates application logic into three interconnected components. This separation helps organize code, makes it more maintainable, and allows multiple developers to work on different parts simultaneously.

**The Three Components:**

- **Model**: Handles data and business logic (database interactions, data validation)
- **View**: Manages the presentation layer (HTML templates, user interface)
- **Controller**: Acts as an intermediary between Model and View (handles user input, coordinates responses)

Think of MVC like a restaurant: the Model is the kitchen (where food is prepared), the View is the dining area (where customers see the final product), and the Controller is the waiter (who takes orders and coordinates between kitchen and customers).

## Setting Up the Project Structure

Before diving into code, we need to establish a solid foundation. A well-organized project structure is crucial for maintainability and scalability.

```
my-mvc-app/
├── app/
│   ├── Controllers/
│   ├── Models/
│   ├── Views/
│   └── Helpers/
├── config/
│   ├── database.php
│   └── app.php
├── core/
│   ├── Router.php
│   ├── Controller.php
│   ├── Model.php
│   └── Database.php
├── public/
│   ├── index.php
│   ├── css/
│   ├── js/
│   └── images/
├── routes/
│   └── web.php
└── .htaccess
```

This structure follows the principle of separation of concerns. The `app` folder contains your application-specific code, `config` holds configuration files, `core` contains the framework's base classes, `public` is the web-accessible directory, and `routes` defines URL patterns.

## Configuration Management

Configuration files centralize important settings, making your application flexible and environment-aware. Let's start with the database configuration.

**config/database.php:**

```php
<?php
return [
    'host' => 'localhost',
    'database' => 'mvc_app',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
```

The `return` statement makes this file act like a function that returns an array. This approach is cleaner than using global variables and allows for easy testing and modification.

**config/app.php:**

```php
<?php
return [
    'app_name' => 'My MVC Application',
    'base_url' => 'http://localhost/my-mvc-app/public',
    'default_controller' => 'HomeController',
    'default_action' => 'index',
    'debug' => true
];
```

These configurations will be loaded dynamically throughout our application, providing flexibility for different environments (development, staging, production).

## The Router System

The router is the traffic director of your application. It examines incoming URLs and determines which controller and method should handle the request.

**core/Router.php:**

```php
<?php
class Router
{
    private $routes = [];
    private $config;

    public function __construct()
    {
        $this->config = require_once '../config/app.php';
    }

    public function addRoute($method, $path, $controller, $action)
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch($requestUri, $requestMethod)
    {
        // Remove query parameters and clean the path
        $path = parse_url($requestUri, PHP_URL_PATH);
        $path = str_replace('/my-mvc-app/public', '', $path);
        $path = $path ?: '/';

        foreach ($this->routes as $route) {
            if ($this->matchRoute($route, $path, $requestMethod)) {
                return $this->callController($route['controller'], $route['action']);
            }
        }

        // If no route matches, use default controller
        $this->callController(
            $this->config['default_controller'],
            $this->config['default_action']
        );
    }

    private function matchRoute($route, $path, $method)
    {
        return $route['method'] === $method && $route['path'] === $path;
    }

    private function callController($controllerName, $action)
    {
        require_once "../app/Controllers/{$controllerName}.php";

        $controller = new $controllerName();

        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            throw new Exception("Method {$action} not found in {$controllerName}");
        }
    }
}
```

The router uses the `parse_url()` function to extract the path from the full URL, removing any query parameters. The `str_replace()` function removes the application's base path, ensuring our routes work regardless of where the application is installed.

## Route Definitions

Routes define the mapping between URLs and controller actions. This separation allows you to change URLs without modifying controller code.

**routes/web.php:**

```php
<?php
require_once '../core/Router.php';

$router = new Router();

// Define routes
$router->addRoute('GET', '/', 'HomeController', 'index');
$router->addRoute('GET', '/about', 'HomeController', 'about');
$router->addRoute('GET', '/users', 'UserController', 'index');
$router->addRoute('GET', '/users/create', 'UserController', 'create');
$router->addRoute('POST', '/users/store', 'UserController', 'store');
$router->addRoute('GET', '/users/show', 'UserController', 'show');

return $router;
```

This file returns a configured router instance. The route definitions follow RESTful conventions where possible, making the API intuitive for developers familiar with web standards.

## The Database Layer

The database layer provides a consistent interface for database operations. We'll use PDO (PHP Data Objects) for database connectivity because it's secure, flexible, and supports multiple database systems.

**core/Database.php:**

```php
<?php
class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $config = require '../config/database.php';

        try {
            $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
            $this->connection = new PDO($dsn, $config['username'], $config['password'], $config['options']);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Query execution failed: " . $e->getMessage());
        }
    }
}
```

This class implements the Singleton pattern, ensuring only one database connection exists throughout the application's lifecycle. The `getInstance()` method uses lazy loading, creating the connection only when first requested.

The `prepare()` and `execute()` methods provide protection against SQL injection attacks by separating SQL code from data. Parameters are automatically escaped and quoted.

## Base Controller Class

The base controller provides common functionality that all controllers can inherit. This follows the DRY (Don't Repeat Yourself) principle.

**core/Controller.php:**

```php
<?php
class Controller
{
    protected function view($viewName, $data = [])
    {
        // Extract array keys as variables
        extract($data);

        // Include the view file
        $viewPath = "../app/Views/{$viewName}.php";

        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            throw new Exception("View {$viewName} not found");
        }
    }

    protected function redirect($url)
    {
        $config = require '../config/app.php';
        $fullUrl = $config['base_url'] . $url;
        header("Location: {$fullUrl}");
        exit();
    }

    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}
```

The `extract()` function converts array keys into variables, making `$data['title']` accessible as `$title` in the view. The `file_exists()` check prevents inclusion of non-existent files, which would cause fatal errors.

## Base Model Class

The base model provides common database operations that specific models can inherit and extend.

**core/Model.php:**

```php
<?php
require_once 'Database.php';

class Model
{
    protected $db;
    protected $table;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->query($sql, [$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        $params = [];
        foreach ($data as $key => $value) {
            $params[":{$key}"] = $value;
        }

        $stmt = $this->db->query($sql, $params);
        return $this->db->getConnection()->lastInsertId();
    }

    public function update($id, $data)
    {
        $setParts = [];
        foreach (array_keys($data) as $key) {
            $setParts[] = "{$key} = :{$key}";
        }
        $setClause = implode(', ', $setParts);

        $sql = "UPDATE {$this->table} SET {$setClause} WHERE id = :id";

        $params = $data;
        $params[':id'] = $id;

        return $this->db->query($sql, $params);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
}
```

This base model demonstrates dynamic SQL generation. The `implode()` function joins array elements with a delimiter, creating comma-separated lists for columns and placeholders. The `array_keys()` function extracts just the keys from an associative array.

## Creating Controllers

Controllers handle user input and coordinate between models and views. Let's create a comprehensive example.

**app/Controllers/UserController.php:**

```php
<?php
require_once '../core/Controller.php';
require_once '../app/Models/User.php';

class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        $users = $this->userModel->findAll();
        $this->view('users/index', ['users' => $users]);
    }

    public function show()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('/users');
            return;
        }

        $user = $this->userModel->findById($id);

        if (!$user) {
            $this->view('errors/404');
            return;
        }

        $this->view('users/show', ['user' => $user]);
    }

    public function create()
    {
        $this->view('users/create');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/users');
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        // Basic validation
        $errors = [];
        if (empty($name)) {
            $errors[] = 'Name is required';
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required';
        }

        if (!empty($errors)) {
            $this->view('users/create', ['errors' => $errors, 'old' => $_POST]);
            return;
        }

        $userData = [
            'name' => $name,
            'email' => $email,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $userId = $this->userModel->create($userData);
        $this->redirect("/users/show?id={$userId}");
    }
}
```

The controller uses the null coalescing operator (`??`) to provide default values for potentially undefined variables. The `trim()` function removes whitespace, and `filter_var()` with `FILTER_VALIDATE_EMAIL` validates email addresses using PHP's built-in filter.

## Creating Models

Models encapsulate business logic and database operations for specific entities.

**app/Models/User.php:**

```php
<?php
require_once '../core/Model.php';

class User extends Model
{
    protected $table = 'users';

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        $stmt = $this->db->query($sql, [$email]);
        return $stmt->fetch();
    }

    public function getRecentUsers($limit = 10)
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT ?";
        $stmt = $this->db->query($sql, [$limit]);
        return $stmt->fetchAll();
    }

    public function validateUser($data)
    {
        $errors = [];

        if (empty($data['name']) || strlen($data['name']) < 2) {
            $errors['name'] = 'Name must be at least 2 characters long';
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please provide a valid email address';
        } else {
            // Check if email already exists
            $existingUser = $this->findByEmail($data['email']);
            if ($existingUser) {
                $errors['email'] = 'Email address is already registered';
            }
        }

        return $errors;
    }
}
```

The User model extends the base Model class, inheriting all common database operations while adding user-specific methods. The `validateUser()` method demonstrates business logic that belongs in the model layer rather than the controller.

## Creating Views

Views handle the presentation layer, displaying data to users in HTML format.

**app/Views/layout/header.php:**

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'My MVC App' ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .nav { background: #333; padding: 10px; margin-bottom: 20px; }
        .nav a { color: white; text-decoration: none; margin-right: 15px; }
        .error { color: red; margin: 5px 0; }
        .success { color: green; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="container">
        <nav class="nav">
            <a href="/">Home</a>
            <a href="/users">Users</a>
            <a href="/users/create">Add User</a>
            <a href="/about">About</a>
        </nav>
```

**app/Views/users/index.php:**

```php
<?php include '../app/Views/layout/header.php'; ?>

<h1>Users</h1>

<?php if (empty($users)): ?>
    <p>No users found. <a href="/users/create">Create the first user</a></p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
                    <td>
                        <a href="/users/show?id=<?= $user['id'] ?>">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include '../app/Views/layout/footer.php'; ?>
```

The view uses PHP's alternative syntax (`if:`, `foreach:`, `endif;`, `endforeach;`) which is more readable when mixing PHP and HTML. The `htmlspecialchars()` function prevents XSS attacks by escaping special HTML characters.

**app/Views/users/create.php:**

```php
<?php include '../app/Views/layout/header.php'; ?>

<h1>Create New User</h1>

<?php if (!empty($errors)): ?>
    <div class="errors">
        <?php foreach ($errors as $error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" action="/users/store">
    <div>
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name"
               value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
    </div>

    <div style="margin-top: 10px;">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"
               value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
    </div>

    <div style="margin-top: 15px;">
        <button type="submit">Create User</button>
        <a href="/users">Cancel</a>
    </div>
</form>

<?php include '../app/Views/layout/footer.php'; ?>
```

This form demonstrates proper form handling with error display and old input preservation. When validation fails, users don't lose their input, improving user experience.

## Helper Functions

Helpers provide utility functions that can be used throughout the application. They promote code reuse and keep your main classes clean.

**app/Helpers/FormHelper.php:**

```php
<?php
class FormHelper
{
    public static function input($type, $name, $value = '', $attributes = [])
    {
        $value = htmlspecialchars($value);
        $attrString = self::buildAttributes($attributes);

        return "<input type='{$type}' name='{$name}' value='{$value}' {$attrString}>";
    }

    public static function textarea($name, $value = '', $attributes = [])
    {
        $value = htmlspecialchars($value);
        $attrString = self::buildAttributes($attributes);

        return "<textarea name='{$name}' {$attrString}>{$value}</textarea>";
    }

    public static function select($name, $options = [], $selected = '', $attributes = [])
    {
        $attrString = self::buildAttributes($attributes);
        $html = "<select name='{$name}' {$attrString}>";

        foreach ($options as $value => $text) {
            $selectedAttr = ($value == $selected) ? 'selected' : '';
            $html .= "<option value='{$value}' {$selectedAttr}>{$text}</option>";
        }

        $html .= "</select>";
        return $html;
    }

    private static function buildAttributes($attributes)
    {
        $parts = [];
        foreach ($attributes as $key => $value) {
            $parts[] = "{$key}='{$value}'";
        }
        return implode(' ', $parts);
    }
}
```

**app/Helpers/StringHelper.php:**

```php
<?php
class StringHelper
{
    public static function slug($string)
    {
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
        $string = preg_replace('/[\s-]+/', '-', $string);
        return trim($string, '-');
    }

    public static function truncate($string, $length = 100, $suffix = '...')
    {
        if (strlen($string) <= $length) {
            return $string;
        }

        return substr($string, 0, $length) . $suffix;
    }

    public static function formatMoney($amount, $currency = '$')
    {
        return $currency . number_format($amount, 2);
    }

    public static function timeAgo($datetime)
    {
        $time = time() - strtotime($datetime);

        if ($time < 60) return 'just now';
        if ($time < 3600) return floor($time/60) . ' minutes ago';
        if ($time < 86400) return floor($time/3600) . ' hours ago';
        if ($time < 2592000) return floor($time/86400) . ' days ago';

        return date('M j, Y', strtotime($datetime));
    }
}
```

These helpers demonstrate common web development tasks. The `slug()` method uses regular expressions (`preg_replace()`) to create URL-friendly strings. The `timeAgo()` function uses `strtotime()` to convert datetime strings to timestamps for calculation.

## Application Entry Point

The entry point ties everything together and handles incoming requests.

**public/index.php:**

```php
<?php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session for flash messages and user data
session_start();

// Load configuration
$config = require_once '../config/app.php';

// Load router and routes
$router = require_once '../routes/web.php';

// Get request information
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

try {
    // Dispatch the request
    $router->dispatch($requestUri, $requestMethod);
} catch (Exception $e) {
    // Handle errors gracefully
    if ($config['debug']) {
        echo "<h1>Error:</h1><p>" . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    } else {
        echo "<h1>Something went wrong</h1><p>Please try again later.</p>";
    }
}
```

**public/.htaccess:**

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

The `.htaccess` file enables URL rewriting. The conditions check if the requested file or directory doesn't exist, then routes everything through `index.php`. The `QSA` flag preserves query strings, and `L` makes this the last rule processed.

## Advanced Database Operations

Let's enhance our database layer with more sophisticated operations that you'll commonly need in real applications.

**core/QueryBuilder.php:**

```php
<?php
class QueryBuilder
{
    private $db;
    private $table;
    private $conditions = [];
    private $orderBy = [];
    private $limit;
    private $offset;

    public function __construct($table)
    {
        $this->db = Database::getInstance();
        $this->table = $table;
    }

    public function where($column, $operator, $value)
    {
        $this->conditions[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
            'type' => 'AND'
        ];
        return $this;
    }

    public function orWhere($column, $operator, $value)
    {
        $this->conditions[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
            'type' => 'OR'
        ];
        return $this;
    }

    public function orderBy($column, $direction = 'ASC')
    {
        $this->orderBy[] = "{$column} {$direction}";
        return $this;
    }

    public function limit($limit, $offset = 0)
    {
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }

    public function get()
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];

        // Build WHERE clause
        if (!empty($this->conditions)) {
            $whereParts = [];
            foreach ($this->conditions as $i => $condition) {
                $placeholder = ":param{$i}";
                $connector = ($i === 0) ? '' : $condition['type'];
                $whereParts[] = "{$connector} {$condition['column']} {$condition['operator']} {$placeholder}";
                $params[$placeholder] = $condition['value'];
            }
            $sql .= " WHERE " . implode(' ', $whereParts);
        }

        // Build ORDER BY clause
        if (!empty($this->orderBy)) {
            $sql .= " ORDER BY " . implode(', ', $this->orderBy);
        }

        // Build LIMIT clause
        if ($this->limit) {
            $sql .= " LIMIT {$this->limit}";
            if ($this->offset) {
                $sql .= " OFFSET {$this->offset}";
            }
        }

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function first()
    {
        $this->limit(1);
        $results = $this->get();
        return $results ? $results[0] : null;
    }
}
```

This query builder demonstrates method chaining (fluent interface) and dynamic SQL generation. Each method returns `$this`, allowing you to chain operations like `$qb->where('status', '=', 'active')->orderBy('created_at', 'DESC')->limit(10)->get()`.

## Session Management and Flash Messages

Session management is crucial for user authentication and displaying temporary messages.

**app/Helpers/SessionHelper.php:**

```php
<?php
class SessionHelper
{
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public static function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public static function flash($key, $message)
    {
        $_SESSION["flash_{$key}"] = $message;
    }

    public static function getFlash($key)
    {
        $message = $_SESSION["flash_{$key}"] ?? null;
        unset($_SESSION["flash_{$key}"]);
        return $message;
    }

    public static function hasFlash($key)
    {
        return isset($_SESSION["flash_{$key}"]);
    }

    public static function destroy()
    {
        session_destroy();
    }

    public static function regenerate()
    {
        session_regenerate_id(true);
    }
}
```

Flash messages are temporary messages that persist for exactly one request. They're perfect for showing success or error messages after form submissions or redirects.

## Validation System

A robust validation system centralizes input validation logic and provides consistent error handling.

**app/Helpers/Validator.php:**

```php
<?php
class Validator
{
    private $data;
    private $errors = [];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function required($fields)
    {
        foreach ($fields as $field) {
            if (empty($this->data[$field])) {
                $this->errors[$field] = ucfirst($field) . ' is required';
            }
        }
        return $this;
    }

    public function email($field)
    {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = ucfirst($field) . ' must be a valid email address';
        }
        return $this;
    }

    public function min($field, $length)
    {
        if (!empty($this->data[$field]) && strlen($this->data[$field]) < $length) {
            $this->errors[$field] = ucfirst($field) . " must be at least {$length} characters";
        }
        return $this;
    }

    public function max($field, $length)
    {
        if (!empty($this->data[$field]) && strlen($this->data[$field]) > $length) {
            $this->errors[$field] = ucfirst($field) . " cannot exceed {$length} characters";
        }
        return $this;
    }

    public function unique($field, $table, $column = null)
    {
        $column = $column ?: $field;
        $value = $this->data[$field] ?? '';

        if (!empty($value)) {
            $db = Database::getInstance();
            $sql = "SELECT COUNT(*) FROM {$table} WHERE {$column} = ?";
            $stmt = $db->query($sql, [$value]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $this->errors[$field] = ucfirst($field) . ' already exists';
            }
        }
        return $this;
    }

    public function passes()
    {
        return empty($this->errors);
    }

    public function fails()
    {
        return !$this->passes();
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
```

Usage example in a controller:

```php
$validator = new Validator($_POST);
$validator->required(['name', 'email'])
          ->email('email')
          ->min('name', 2)
          ->unique('email', 'users');

if ($validator->fails()) {
    $this->view('users/create', ['errors' => $validator->getErrors()]);
    return;
}
```

## File Upload Handling

File uploads are common in web applications. Here's a secure and flexible file upload system.

**app/Helpers/FileUpload.php:**

```php
<?php
class FileUpload
{
    private $uploadDir;
    private $allowedTypes;
    private $maxSize;

    public function __construct($uploadDir = '../storage/uploads/', $allowedTypes = ['jpg', 'png', 'gif'], $maxSize = 2097152)
    {
        $this->uploadDir = $uploadDir;
        $this->allowedTypes = $allowedTypes;
        $this->maxSize = $maxSize; // 2MB default

        // Create upload directory if it doesn't exist
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function upload($fileInput)
    {
        if (!isset($_FILES[$fileInput])) {
            throw new Exception('No file uploaded');
        }

        $file = $_FILES[$fileInput];

        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('File upload failed: ' . $this->getUploadError($file['error']));
        }

        // Validate file size
        if ($file['size'] > $this->maxSize) {
            throw new Exception('File size exceeds maximum allowed size');
        }

        // Validate file type
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedTypes)) {
            throw new Exception('File type not allowed');
        }

        // Generate unique filename
        $filename = uniqid() . '.' . $extension;
        $filepath = $this->uploadDir . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $filename;
        } else {
            throw new Exception('Failed to save uploaded file');
        }
    }

    private function getUploadError($errorCode)
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return 'File size too large';
            case UPLOAD_ERR_PARTIAL:
                return 'File partially uploaded';
            case UPLOAD_ERR_NO_FILE:
                return 'No file uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing temporary folder';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file';
            default:
                return 'Unknown upload error';
        }
    }
}
```

The `pathinfo()` function extracts file information, `uniqid()` generates unique identifiers to prevent filename conflicts, and `move_uploaded_file()` securely moves files from the temporary upload location.

## Putting It All Together

Let's create a complete example that demonstrates all these concepts working together.

**app/Controllers/BlogController.php:**

```php
<?php
require_once '../core/Controller.php';
require_once '../app/Models/Post.php';
require_once '../app/Helpers/Validator.php';
require_once '../app/Helpers/SessionHelper.php';

class BlogController extends Controller
{
    private $postModel;

    public function __construct()
    {
        $this->postModel = new Post();
    }

    public function index()
    {
        $posts = $this->postModel->getPublishedPosts();
        $this->view('blog/index', [
            'title' => 'Blog Posts',
            'posts' => $posts,
            'success' => SessionHelper::getFlash('success')
        ]);
    }

    public function show()
    {
        $slug = $_GET['slug'] ?? null;

        if (!$slug) {
            $this->redirect('/blog');
            return;
        }

        $post = $this->postModel->findBySlug($slug);

        if (!$post) {
            $this->view('errors/404', ['title' => 'Post Not Found']);
            return;
        }

        $this->view('blog/show', [
            'title' => $post['title'],
            'post' => $post
        ]);
    }

    public function create()
    {
        $this->view('blog/create', [
            'title' => 'Create New Post',
            'errors' => SessionHelper::getFlash('errors'),
            'old' => SessionHelper::getFlash('old')
        ]);
    }

    public function store()
    {
        $validator = new Validator($_POST);
        $validator->required(['title', 'content'])
                  ->min('title', 5)
                  ->min('content', 50);

        if ($validator->fails()) {
            SessionHelper::flash('errors', $validator->getErrors());
            SessionHelper::flash('old', $_POST);
            $this->redirect('/blog/create');
            return;
        }

        $postData = [
            'title' => trim($_POST['title']),
            'slug' => StringHelper::slug($_POST['title']),
            'content' => trim($_POST['content']),
            'status' => 'published',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $postId = $this->postModel->create($postData);
        SessionHelper::flash('success', 'Post created successfully!');
        $this->redirect('/blog');
    }
}
```

This example shows how all components work together: validation, session management, string helpers, and database operations all coordinated through the controller.

## Security Considerations

Security should be built into your MVC framework from the ground up. Here are essential security practices:

**Input Sanitization:**
Always validate and sanitize user input. Use `htmlspecialchars()` for output, `filter_var()` for validation, and prepared statements for database queries.

**CSRF Protection:**
Implement Cross-Site Request Forgery protection for forms:

```php
// Generate CSRF token
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Validate CSRF token
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
```

**SQL Injection Prevention:**
Always use prepared statements. Never concatenate user input directly into SQL queries.

**XSS Prevention:**
Escape output using `htmlspecialchars()` or similar functions. Consider implementing Content Security Policy headers.

## Performance Optimization

As your application grows, performance becomes crucial:

**Database Connection Pooling:**
Use persistent connections when appropriate:

```php
$options[PDO::ATTR_PERSISTENT] = true;
```

**Query Optimization:**
Use database indexes, limit result sets, and avoid N+1 queries by implementing eager loading.

**Caching:**
Implement caching for expensive operations:

```php
class Cache {
    public static function get($key) {
        $file = "../cache/{$key}.cache";
        if (file_exists($file) && time() - filemtime($file) < 3600) {
            return unserialize(file_get_contents($file));
        }
        return null;
    }

    public static function set($key, $data) {
        $file = "../cache/{$key}.cache";
        file_put_contents($file, serialize($data));
    }
}
```

## Conclusion

You've now built a complete MVC framework from scratch! This foundation provides the structure needed for scalable web applications. The separation of concerns makes your code more maintainable, the routing system provides flexibility, and the database layer ensures security.

Key takeaways:

- MVC separates presentation, business logic, and data handling
- Routing provides clean URLs and centralized request handling
- Prepared statements prevent SQL injection attacks
- Helper classes promote code reuse and maintainability
- Proper validation ensures data integrity
- Session management enables user state persistence

As you continue developing, consider adding features like middleware, dependency injection, event systems, and automated testing. The foundation you've built here will support these advanced features as your applications grow in complexity.

Remember that building a framework is an iterative process. Start simple, add features as needed, and always prioritize security and maintainability over complexity.
