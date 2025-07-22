# PHP Object-Oriented Programming: Complete Beginner's Guide

## What is Object-Oriented Programming (OOP)?

Object-Oriented Programming is a programming paradigm that organizes code around objects rather than functions and logic. Think of it like organizing a library - instead of having all books scattered randomly, you group them by categories, authors, and subjects. In programming terms, we group related data and functions together into "objects."

Imagine you're building a car manufacturing system. Instead of having separate functions for engine operations, wheel management, and door controls scattered throughout your code, OOP lets you create a "Car" object that contains all these related components and their behaviors in one organized package.

The beauty of OOP lies in its ability to mirror real-world relationships. Just as a real car has properties (color, model, engine type) and behaviors (start, stop, accelerate), our code objects have properties (variables) and methods (functions) that work together.

## Classes and Objects: The Building Blocks

A class is like a blueprint or template - it defines what an object will look like and what it can do, but it's not the actual object itself. An object is a specific instance created from that blueprint.

```php
<?php
// This is our blueprint (class)
class Car {
    // Properties (characteristics of a car)
    public $color;
    public $brand;
    public $model;
    
    // Methods (what a car can do)
    public function start() {
        return "The car is starting... Engine on!";
    }
    
    public function stop() {
        return "The car has stopped. Engine off!";
    }
}

// Creating objects (actual cars) from our blueprint
$myCar = new Car();  // This creates a new car object
$friendsCar = new Car();  // This creates another car object

// Setting properties for each car
$myCar->color = "Red";
$myCar->brand = "Toyota";
$myCar->model = "Camry";

$friendsCar->color = "Blue";
$friendsCar->brand = "Honda";
$friendsCar->model = "Civic";

// Using methods (calling functions on objects)
echo $myCar->start();  // Output: The car is starting... Engine on!
echo $friendsCar->stop();  // Output: The car has stopped. Engine off!
?>
```

Notice how we use the `new` keyword to create objects and the arrow operator (`->`) to access properties and methods. Each object maintains its own set of property values - changing `$myCar->color` doesn't affect `$friendsCar->color`.

## Constructors: Setting Up Your Objects

A constructor is a special method that runs automatically when you create a new object. It's like the assembly line in a factory - it sets up the initial state of your object with the values you provide.

```php
<?php
class Car {
    public $color;
    public $brand;
    public $model;
    private $isRunning = false;  // Private property - only accessible within this class
    
    // Constructor method - runs when 'new Car()' is called
    public function __construct($color, $brand, $model) {
        $this->color = $color;    // $this refers to the current object
        $this->brand = $brand;
        $this->model = $model;
        echo "A new {$this->color} {$this->brand} {$this->model} has been created!\n";
    }
    
    public function start() {
        if (!$this->isRunning) {
            $this->isRunning = true;
            return "The {$this->brand} {$this->model} is starting...\n";
        }
        return "The car is already running!\n";
    }
    
    public function getCarInfo() {
        return "This is a {$this->color} {$this->brand} {$this->model}\n";
    }
}

// Now we can create cars with initial values
$myCar = new Car("Red", "Toyota", "Camry");
// Output: A new Red Toyota Camry has been created!

$friendsCar = new Car("Blue", "Honda", "Civic");
// Output: A new Blue Honda Civic has been created!

echo $myCar->getCarInfo();  // Output: This is a Red Toyota Camry
echo $myCar->start();       // Output: The Toyota Camry is starting...
?>
```

The `$this` keyword is crucial here - it refers to the current object instance. When you call `$myCar->start()`, inside the method `$this` refers to the `$myCar` object specifically.

## Destructors: Cleaning Up

A destructor is the opposite of a constructor - it runs automatically when an object is destroyed or when the script ends. It's useful for cleanup tasks like closing database connections or saving data.

```php
<?php
class DatabaseConnection {
    private $connection;
    private $host;
    
    public function __construct($host) {
        $this->host = $host;
        $this->connection = "Connected to {$host}";
        echo "Database connection established to {$host}\n";
    }
    
    public function query($sql) {
        return "Executing query: {$sql} on {$this->host}\n";
    }
    
    // Destructor - runs when object is destroyed
    public function __destruct() {
        echo "Closing database connection to {$this->host}\n";
        // In real code, you'd close the actual database connection here
    }
}

// Creating and using a database connection
$db = new DatabaseConnection("localhost");
// Output: Database connection established to localhost

echo $db->query("SELECT * FROM users");
// Output: Executing query: SELECT * FROM users on localhost

// When the script ends or $db goes out of scope, destructor runs
// Output: Closing database connection to localhost
?>
```

## Access Modifiers: Controlling Visibility

Access modifiers control who can access properties and methods. Think of them as security levels in a building - some areas are public (anyone can enter), some are private (only authorized personnel), and some are protected (authorized personnel and their associates).

```php
<?php
class BankAccount {
    public $accountHolder;      // Anyone can access this
    private $balance;           // Only this class can access this
    protected $accountNumber;   // This class and its subclasses can access this
    
    public function __construct($holder, $initialBalance) {
        $this->accountHolder = $holder;
        $this->balance = $initialBalance;
        $this->accountNumber = $this->generateAccountNumber();
    }
    
    // Public method - anyone can call this
    public function deposit($amount) {
        if ($amount > 0) {
            $this->balance += $amount;
            return "Deposited ${amount}. New balance: ${this->balance}\n";
        }
        return "Invalid deposit amount\n";
    }
    
    // Public method to safely get balance
    public function getBalance() {
        return $this->balance;
    }
    
    // Private method - only this class can call this
    private function generateAccountNumber() {
        return "ACC" . rand(100000, 999999);
    }
    
    // Protected method - this class and subclasses can call this
    protected function validateTransaction($amount) {
        return $amount > 0 && $amount <= $this->balance;
    }
}

$account = new BankAccount("John Doe", 1000);

// This works - public property
echo "Account holder: " . $account->accountHolder . "\n";

// This works - public method
echo $account->deposit(500);

// This works - public method accessing private property safely
echo "Current balance: $" . $account->getBalance() . "\n";

// These would cause errors if uncommented:
// echo $account->balance;  // Error: Cannot access private property
// $account->generateAccountNumber();  // Error: Cannot access private method
?>
```

Private properties and methods are like your personal diary - only you (the class) can access them. Protected items are like family secrets - you and your relatives (subclasses) can access them, but outsiders cannot.

## Inheritance: Building on Existing Code

Inheritance allows you to create new classes based on existing ones. The new class (child) inherits all properties and methods from the parent class and can add its own or modify inherited ones.

```php
<?php
// Parent class (base class)
class Vehicle {
    protected $brand;
    protected $model;
    protected $year;
    
    public function __construct($brand, $model, $year) {
        $this->brand = $brand;
        $this->model = $model;
        $this->year = $year;
    }
    
    public function start() {
        return "The {$this->brand} {$this->model} is starting...\n";
    }
    
    public function getInfo() {
        return "{$this->year} {$this->brand} {$this->model}\n";
    }
}

// Child class inheriting from Vehicle
class Car extends Vehicle {
    private $doors;
    
    public function __construct($brand, $model, $year, $doors) {
        // Call parent constructor first
        parent::__construct($brand, $model, $year);
        $this->doors = $doors;
    }
    
    // Override parent method
    public function start() {
        return "Car engine starting... The {$this->brand} {$this->model} is ready to drive!\n";
    }
    
    // Add new method specific to cars
    public function honk() {
        return "Beep beep! The {$this->brand} is honking!\n";
    }
    
    // Override parent method and extend it
    public function getInfo() {
        return parent::getInfo() . "Doors: {$this->doors}\n";
    }
}

// Another child class
class Motorcycle extends Vehicle {
    private $engineSize;
    
    public function __construct($brand, $model, $year, $engineSize) {
        parent::__construct($brand, $model, $year);
        $this->engineSize = $engineSize;
    }
    
    public function start() {
        return "Motorcycle engine roaring... The {$this->brand} {$this->model} is ready to ride!\n";
    }
    
    public function wheelie() {
        return "The {$this->brand} is doing a wheelie!\n";
    }
}

// Using inheritance
$car = new Car("Toyota", "Camry", 2023, 4);
$motorcycle = new Motorcycle("Harley-Davidson", "Street 750", 2023, "750cc");

echo $car->start();        // Uses Car's overridden start method
echo $car->honk();         // Uses Car's specific method
echo $car->getInfo();      // Uses Car's extended getInfo method

echo $motorcycle->start(); // Uses Motorcycle's overridden start method
echo $motorcycle->wheelie(); // Uses Motorcycle's specific method
?>
```

Think of inheritance like a family tree - children inherit traits from their parents but can also develop their own unique characteristics. The `parent::` keyword lets you access the parent class's methods, useful when you want to extend rather than completely replace functionality.

## Constants: Values That Never Change

Constants are like mathematical constants (π, e) - they have fixed values that never change throughout your program. In PHP classes, constants are defined using the `const` keyword.

```php
<?php
class MathHelper {
    // Class constants - values that never change
    const PI = 3.14159;
    const E = 2.71828;
    const GRAVITY = 9.81;
    
    // You can also have constants with more complex values
    const SUPPORTED_CURRENCIES = ['USD', 'EUR', 'GBP', 'JPY'];
    
    public static function calculateCircleArea($radius) {
        // Access constant using self:: (within the class)
        return self::PI * $radius * $radius;
    }
    
    public static function calculateCircumference($radius) {
        return 2 * self::PI * $radius;
    }
    
    public static function getSupportedCurrencies() {
        return self::SUPPORTED_CURRENCIES;
    }
}

// Access constants from outside the class using ::
echo "Pi value: " . MathHelper::PI . "\n";
echo "Gravity: " . MathHelper::GRAVITY . " m/s²\n";

// Using constants in calculations
$radius = 5;
echo "Circle area with radius {$radius}: " . MathHelper::calculateCircleArea($radius) . "\n";
echo "Circle circumference with radius {$radius}: " . MathHelper::calculateCircumference($radius) . "\n";

// Working with array constants
$currencies = MathHelper::getSupportedCurrencies();
echo "Supported currencies: " . implode(', ', $currencies) . "\n";

// Constants are case-sensitive and cannot be changed
// MathHelper::PI = 3.14;  // This would cause an error
?>
```

Constants are particularly useful for configuration values, mathematical constants, or any value that should remain the same throughout your application's lifetime.

## Abstract Classes: Defining Blueprints

Abstract classes are like architectural blueprints that define the structure but leave some details to be filled in by specific implementations. You cannot create objects directly from abstract classes - they must be extended by concrete classes.

```php
<?php
// Abstract class - cannot be instantiated directly
abstract class Shape {
    protected $color;
    
    public function __construct($color) {
        $this->color = $color;
    }
    
    // Concrete method - all shapes have this implementation
    public function getColor() {
        return $this->color;
    }
    
    // Abstract method - must be implemented by child classes
    abstract public function calculateArea();
    abstract public function calculatePerimeter();
    
    // Another concrete method that uses abstract methods
    public function getShapeInfo() {
        return "This is a {$this->color} shape with area: " . 
               $this->calculateArea() . " and perimeter: " . 
               $this->calculatePerimeter() . "\n";
    }
}

// Concrete class implementing the abstract class
class Circle extends Shape {
    private $radius;
    
    public function __construct($color, $radius) {
        parent::__construct($color);
        $this->radius = $radius;
    }
    
    // Must implement abstract methods
    public function calculateArea() {
        return 3.14159 * $this->radius * $this->radius;
    }
    
    public function calculatePerimeter() {
        return 2 * 3.14159 * $this->radius;
    }
}

class Rectangle extends Shape {
    private $width;
    private $height;
    
    public function __construct($color, $width, $height) {
        parent::__construct($color);
        $this->width = $width;
        $this->height = $height;
    }
    
    // Must implement abstract methods
    public function calculateArea() {
        return $this->width * $this->height;
    }
    
    public function calculatePerimeter() {
        return 2 * ($this->width + $this->height);
    }
}

// Using concrete classes
$circle = new Circle("red", 5);
$rectangle = new Rectangle("blue", 4, 6);

echo $circle->getShapeInfo();
echo $rectangle->getShapeInfo();

// This would cause an error:
// $shape = new Shape("green");  // Cannot instantiate abstract class
?>
```

Abstract classes are perfect when you want to provide a common interface and some shared functionality, but need different implementations for specific behaviors.

## Interfaces: Defining Contracts

Interfaces are like contracts that define what methods a class must implement, without specifying how they should be implemented. Think of them as job descriptions - they tell you what you need to do, but not how to do it.

```php
<?php
// Interface defining what a payment processor must do
interface PaymentProcessorInterface {
    public function processPayment($amount);
    public function refundPayment($transactionId);
    public function getTransactionStatus($transactionId);
}

// Interface for logging capabilities
interface LoggableInterface {
    public function log($message);
}

// Concrete class implementing the payment interface
class PayPalProcessor implements PaymentProcessorInterface, LoggableInterface {
    private $apiKey;
    
    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }
    
    public function processPayment($amount) {
        // PayPal-specific payment processing
        $transactionId = "PP" . rand(100000, 999999);
        $this->log("Processing PayPal payment of ${amount}");
        return "PayPal payment of ${amount} processed. Transaction ID: {$transactionId}\n";
    }
    
    public function refundPayment($transactionId) {
        $this->log("Refunding PayPal transaction: {$transactionId}");
        return "PayPal refund processed for transaction: {$transactionId}\n";
    }
    
    public function getTransactionStatus($transactionId) {
        return "PayPal transaction {$transactionId} status: Completed\n";
    }
    
    public function log($message) {
        echo "[PayPal Log] " . date('Y-m-d H:i:s') . " - {$message}\n";
    }
}

class StripeProcessor implements PaymentProcessorInterface, LoggableInterface {
    private $secretKey;
    
    public function __construct($secretKey) {
        $this->secretKey = $secretKey;
    }
    
    public function processPayment($amount) {
        // Stripe-specific payment processing
        $transactionId = "ST" . rand(100000, 999999);
        $this->log("Processing Stripe payment of ${amount}");
        return "Stripe payment of ${amount} processed. Transaction ID: {$transactionId}\n";
    }
    
    public function refundPayment($transactionId) {
        $this->log("Refunding Stripe transaction: {$transactionId}");
        return "Stripe refund processed for transaction: {$transactionId}\n";
    }
    
    public function getTransactionStatus($transactionId) {
        return "Stripe transaction {$transactionId} status: Successful\n";
    }
    
    public function log($message) {
        echo "[Stripe Log] " . date('Y-m-d H:i:s') . " - {$message}\n";
    }
}

// Function that works with any payment processor
function processOrder($processor, $amount) {
    // This function doesn't care which specific processor it receives
    // It just knows it implements PaymentProcessorInterface
    return $processor->processPayment($amount);
}

// Using different processors interchangeably
$paypal = new PayPalProcessor("paypal_api_key");
$stripe = new StripeProcessor("stripe_secret_key");

echo processOrder($paypal, 100.00);
echo processOrder($stripe, 150.00);

echo $paypal->getTransactionStatus("PP123456");
echo $stripe->refundPayment("ST789012");
?>
```

Interfaces enable polymorphism - you can treat different objects the same way as long as they implement the same interface. This makes your code more flexible and maintainable.

## Traits: Sharing Code Across Classes

Traits are like mix-ins that allow you to share code between classes without using inheritance. They're perfect for functionality that doesn't fit into a natural inheritance hierarchy.

```php
<?php
// Trait for logging functionality
trait LoggingTrait {
    private $logFile = 'app.log';
    
    public function log($message, $level = 'INFO') {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] [{$level}] {$message}\n";
        
        // In a real application, you'd write to a file or database
        echo $logEntry;
    }
    
    public function logError($message) {
        $this->log($message, 'ERROR');
    }
    
    public function logDebug($message) {
        $this->log($message, 'DEBUG');
    }
}

// Trait for timestamp functionality
trait TimestampTrait {
    private $createdAt;
    private $updatedAt;
    
    public function setCreatedAt() {
        $this->createdAt = date('Y-m-d H:i:s');
    }
    
    public function setUpdatedAt() {
        $this->updatedAt = date('Y-m-d H:i:s');
    }
    
    public function getCreatedAt() {
        return $this->createdAt;
    }
    
    public function getUpdatedAt() {
        return $this->updatedAt;
    }
}

// Class using multiple traits
class User {
    use LoggingTrait, TimestampTrait;
    
    private $name;
    private $email;
    
    public function __construct($name, $email) {
        $this->name = $name;
        $this->email = $email;
        $this->setCreatedAt();
        $this->log("User created: {$name} ({$email})");
    }
    
    public function updateEmail($newEmail) {
        $oldEmail = $this->email;
        $this->email = $newEmail;
        $this->setUpdatedAt();
        $this->log("Email updated from {$oldEmail} to {$newEmail}");
    }
    
    public function getName() {
        return $this->name;
    }
}

// Another class using the same traits
class Product {
    use LoggingTrait, TimestampTrait;
    
    private $name;
    private $price;
    
    public function __construct($name, $price) {
        $this->name = $name;
        $this->price = $price;
        $this->setCreatedAt();
        $this->log("Product created: {$name} - ${price}");
    }
    
    public function updatePrice($newPrice) {
        $oldPrice = $this->price;
        $this->price = $newPrice;
        $this->setUpdatedAt();
        $this->log("Price updated from ${oldPrice} to ${newPrice}");
    }
}

// Using classes with traits
$user = new User("John Doe", "john@example.com");
$user->updateEmail("john.doe@example.com");

echo "User created at: " . $user->getCreatedAt() . "\n";
echo "User updated at: " . $user->getUpdatedAt() . "\n";

$product = new Product("Laptop", 999.99);
$product->updatePrice(899.99);
?>
```

Traits solve the problem of code duplication without forcing you into complex inheritance hierarchies. They're particularly useful for cross-cutting concerns like logging, timestamps, or validation.

## Static Methods and Properties: Class-Level Functionality

Static methods and properties belong to the class itself rather than to specific instances. They're like shared utilities that don't need an object to work.

```php
<?php
class MathUtilities {
    // Static property - shared by all instances
    private static $calculationCount = 0;
    
    // Static method - can be called without creating an object
    public static function add($a, $b) {
        self::$calculationCount++;
        return $a + $b;
    }
    
    public static function multiply($a, $b) {
        self::$calculationCount++;
        return $a * $b;
    }
    
    public static function power($base, $exponent) {
        self::$calculationCount++;
        return pow($base, $exponent);
    }
    
    // Static method to get the count
    public static function getCalculationCount() {
        return self::$calculationCount;
    }
    
    // Static method to reset the count
    public static function resetCalculationCount() {
        self::$calculationCount = 0;
    }
}

class DatabaseConnection {
    private static $instance = null;
    private $connection;
    
    // Private constructor prevents direct instantiation
    private function __construct() {
        $this->connection = "Database connection established";
        echo $this->connection . "\n";
    }
    
    // Static method to get single instance (Singleton pattern)
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function query($sql) {
        return "Executing: {$sql}";
    }
}

// Using static methods - no need to create objects
echo "5 + 3 = " . MathUtilities::add(5, 3) . "\n";
echo "4 * 6 = " . MathUtilities::multiply(4, 6) . "\n";
echo "2^8 = " . MathUtilities::power(2, 8) . "\n";

echo "Total calculations performed: " . MathUtilities::getCalculationCount() . "\n";

// Using singleton pattern
$db1 = DatabaseConnection::getInstance();
$db2 = DatabaseConnection::getInstance();

// Both variables reference the same instance
var_dump($db1 === $db2);  // Output: bool(true)

echo $db1->query("SELECT * FROM users") . "\n";
?>
```

Static methods are perfect for utility functions that don't need object state, while static properties are useful for sharing data across all instances of a class.

## Namespaces: Organizing Your Code

Namespaces are like folders for your classes - they help organize code and prevent naming conflicts. They're especially important in larger applications where you might have multiple classes with similar names.

```php
<?php
// File: payment/PayPal.php
namespace Payment;

class PayPal {
    public function processPayment($amount) {
        return "Processing ${amount} via PayPal";
    }
}

// File: shipping/PayPal.php
namespace Shipping;

class PayPal {
    public function calculateShipping($weight) {
        return "Calculating shipping for {$weight}kg via PayPal shipping";
    }
}

// File: main.php
namespace Main;

// Using classes from different namespaces
use Payment\PayPal as PaymentPayPal;
use Shipping\PayPal as ShippingPayPal;

// Alternative way to use namespaced classes
// $paymentProcessor = new Payment\PayPal();
// $shippingCalculator = new Shipping\PayPal();

$paymentProcessor = new PaymentPayPal();
$shippingCalculator = new ShippingPayPal();

echo $paymentProcessor->processPayment(100) . "\n";
echo $shippingCalculator->calculateShipping(2.5) . "\n";

// Another example with deeper namespace structure
namespace App\Models\User;

class Profile {
    private $firstName;
    private $lastName;
    
    public function __construct($firstName, $lastName) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
    
    public function getFullName() {
        return $this->firstName . ' ' . $this->lastName;
    }
}

namespace App\Controllers;

// Using the Profile class from different namespace
use App\Models\User\Profile;

class UserController {
    public function createProfile($firstName, $lastName) {
        $profile = new Profile($firstName, $lastName);
        return "Profile created for: " . $profile->getFullName();
    }
}

// Using the controller
$controller = new UserController();
echo $controller->createProfile("John", "Doe") . "\n";
?>
```

Namespaces become increasingly important as your applications grow. They help you organize code logically and avoid conflicts when using third-party libraries.

## Iterables: Making Objects Loop-Friendly

The Iterator interface allows you to make your objects work with foreach loops, giving you control over how the iteration happens.

```php
<?php
// Custom collection class that implements Iterator
class BookCollection implements Iterator {
    private $books = [];
    private $position = 0;
    
    public function addBook($title, $author) {
        $this->books[] = ['title' => $title, 'author' => $author];
    }
    
    // Iterator interface methods
    public function current() {
        return $this->books[$this->position];
    }
    
    public function key() {
        return $this->position;
    }
    
    public function next() {
        $this->position++;
    }
    
    public function rewind() {
        $this->position = 0;
    }
    
    public function valid() {
        return isset($this->books[$this->position]);
    }
    
    // Additional useful methods
    public function count() {
        return count($this->books);
    }
    
    public function isEmpty() {
        return empty($this->books);
    }
}

// Using the iterable collection
$library = new BookCollection();
$library->addBook("The Great Gatsby", "F. Scott Fitzgerald");
$library->addBook("To Kill a Mockingbird", "Harper Lee");
$library->addBook("1984", "George Orwell");

// Now we can use foreach with our custom object
echo "Library contains " . $library->count() . " books:\n";

foreach ($library as $index => $book) {
    echo ($index + 1) . ". {$book['title']} by {$book['author']}\n";
}

// Another example with more complex iteration
class NumberRange implements Iterator {
    private $start;
    private $end;
    private $current;
    
    public function __construct($start, $end) {
        $this->start = $start;
        $this->end = $end;
        $this->current = $start;
    }
    
    public function current() {
        return $this->current;
    }
    
    public function key() {
        return $this->current;
    }
    
    public function next() {
        $this->current++;
    }
    
    public function rewind() {
        $this->current = $this->start;
    }
    
    public function valid() {
        return $this->current <= $this->end;
    }
}

// Using the number range iterator
$range = new NumberRange(1, 5);

echo "Numbers from 1 to 5:\n";
foreach ($range as $number) {
    echo $number . " ";
}
echo "\n";

// You can also use it multiple times
echo "Squares of numbers from 1 to 5:\n";
foreach ($range as $number) {
    echo $number . "² = " . ($number * $number) . "\n";
}
?>
```

Implementing the Iterator interface makes your objects feel like native PHP arrays when used in loops, providing a clean and intuitive way to work with collections of data.

## Putting It All Together: A Complete Example

Let's create a comprehensive example that demonstrates many of these concepts working together in a realistic scenario.

```php
<?php
namespace Library;

// Interface for items that can be borrowed
interface BorrowableInterface {
    public function borrow($memberId);
    public function returnItem();
    public function isAvailable();
}

// Abstract base class for all library items
abstract class LibraryItem implements BorrowableInterface {
    protected $id;
    protected $title;
    protected $isAvailable = true;
    protected $borrowedBy = null;
    protected $borrowDate = null;
    
    public function __construct($id, $title) {
        $this->id = $id;
        $this->title = $title;
    }
    
    public function borrow($memberId) {
        if (!$this->isAvailable) {
            return "Item '{$this->title}' is already borrowed";
        }
        
        $this->isAvailable = false;
        $this->borrowedBy = $memberId;
        $this->borrowDate = date('Y-m-d');
        
        return "Item '{$this->title}' borrowed by member {$memberId}";
    }
    
    public function returnItem() {
        if ($this->isAvailable) {
            return "Item '{$this->title}' was not borrowed";
        }
        
        $this->isAvailable = true;
        $previousBorrower = $this->borrowedBy;
        $this->borrowedBy = null;
        $this->borrowDate = null;
        
        return "Item '{$this->title}' returned by member {$previousBorrower}";
    }
    
    public function isAvailable() {
        return $this->isAvailable;
    }
    
    public function getTitle() {
        return $this->title;
    }
    
    public function getId() {
        return $this->id;
    }
    
    // Abstract method that concrete classes must implement
    abstract public function getItemType();
    abstract public function getDescription();
}

// Concrete class for books
class Book extends LibraryItem {
    private $author;
    private $isbn;
    private $pages;
    
    public function __construct($id, $title, $author, $isbn, $pages) {
        parent::__construct($id, $title);
        $this->author = $author;
        $this->isbn = $isbn;
        $this->pages = $pages;
    }
    
    public function getItemType() {
        return "Book";
    }
    
    public function getDescription() {
        return "'{$this->title}' by {$this->author} (ISBN: {$this->isbn}, {$this->pages} pages)";
    }
    
    public function getAuthor() {
        return $this->author;
    }
}

// Concrete class for DVDs
class DVD extends LibraryItem {
    private $director;
    private $duration;
    private $genre;
    
    public function __construct($id, $title, $director, $duration, $genre) {
        parent::__construct($id, $title);
        $this->director = $director;
        $this->duration = $duration;
        $this->genre = $genre;
    }
    
    public function getItemType() {
        return "DVD";
    }
    
    public function getDescription() {
        return "'{$this->title}' directed by {$this->director} ({$this->duration} min, {$this->genre})";
    }
}

// Trait for logging functionality
trait LoggingTrait {
    private static $logs = [];
    
    protected function log($message) {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] {$message}";
        self::$logs[] = $logEntry;
        echo $logEntry . "\n";
    }
    
    public static function getLogs() {
        return self::$logs;
    }
}

// Library management system
class Library implements Iterator {
    use LoggingTrait;
    
    private $items = [];
    private $members = [];
    private $position = 0;
    
    // Constants for library rules
    const MAX_BORROW_DAYS = 14;
    const LATE_FEE_PER_DAY = 0.50;
    
    public function addItem(LibraryItem $item) {
        $this->items[$item->getId()] = $item;
        $this->log("Added {$item->getItemType()}: {$item->getDescription()}");
    }
    
    public function addMember($memberId, $name) {
        $this->members[$memberId] = $name;
        $this->log("New member registered: {$name} (ID: {$memberId})");
    }
    
    public function borrowItem($itemId, $memberId) {
        if (!isset($this->items[$itemId])) {
            return "Item with ID {$itemId} not found";
        }
        
        if (!isset($this->members[$memberId])) {
            return "Member with ID {$memberId} not found";
        }
        
        $item = $this->items[$itemId];
        $result = $item->borrow($memberId);
        $this->log("Borrow attempt: {$result}");
        
        return $result;
    }
    
    public function returnItem($itemId) {
        if (!isset($this->items[$itemId])) {
            return "Item with ID {$itemId} not found";
        }
        
        $item = $this->items[$itemId];
        $result = $item->returnItem();
        $this->log("Return attempt: {$result}");
        
        return $result;
    }
    
    public function searchByTitle($title) {
        $results = [];
        foreach ($this->items as $item) {
            if (stripos($item->getTitle(), $title) !== false) {
                $results[] = $item;
            }
        }
        return $results;
    }
    
    public function getAvailableItems() {
        $available = [];
        foreach ($this->items as $item) {
            if ($item->isAvailable()) {
                $available[] = $item;
            }
        }
        return $available;
    }
    
    public static function calculateLateFee($daysLate) {
        return $daysLate * self::LATE_FEE_PER_DAY;
    }
    
    // Iterator interface implementation
    public function current() {
        return current($this->items);
    }
    
    public function key() {
        return key($this->items);
    }
    
    public function next() {
        return next($this->items);
    }
    
    public function rewind() {
        return reset($this->items);
    }
    
    public function valid() {
        return key($this->items) !== null;
    }
    
    public function count() {
        return count($this->items);
    }
}

// Using the complete library system
$library = new Library();

// Add members
$library->addMember(1001, "Alice Johnson");
$library->addMember(1002, "Bob Smith");
$library->addMember(1003, "Carol Davis");

// Add items to library
$book1 = new Book("B001", "The Great Gatsby", "F. Scott Fitzgerald", "978-0-7432-7356-5", 180);
$book2 = new Book("B002", "To Kill a Mockingbird", "Harper Lee", "978-0-06-112008-4", 324);
$dvd1 = new DVD("D001", "The Shawshank Redemption", "Frank Darabont", 142, "Drama");
$dvd2 = new DVD("D002", "Inception", "Christopher Nolan", 148, "Sci-Fi");

$library->addItem($book1);
$library->addItem($book2);
$library->addItem($dvd1);
$library->addItem($dvd2);

echo "\n=== Library Operations ===\n";

// Borrow items
echo $library->borrowItem("B001", 1001) . "\n";
echo $library->borrowItem("D001", 1002) . "\n";
echo $library->borrowItem("B001", 1003) . "\n"; // Should fail - already borrowed

// Show available items
echo "\nAvailable items:\n";
$availableItems = $library->getAvailableItems();
foreach ($availableItems as $item) {
    echo "- {$item->getItemType()}: {$item->getDescription()}\n";
}

// Search functionality
echo "\nSearching for 'kill':\n";
$searchResults = $library->searchByTitle("kill");
foreach ($searchResults as $item) {
    echo "- Found: {$item->getDescription()}\n";
}

// Return items
echo "\nReturning items:\n";
echo $library->returnItem("B001") . "\n";
echo $library->returnItem("D001") . "\n";

// Use library as iterator
echo "\nAll items in library:\n";
foreach ($library as $id => $item) {
    $status = $item->isAvailable() ? "Available" : "Borrowed";
    echo "ID: {$id} - {$item->getDescription()} [{$status}]\n";
}

// Calculate late fee
echo "\nLate fee calculation:\n";
$daysLate = 5;
$lateFee = Library::calculateLateFee($daysLate);
echo "Late fee for {$daysLate} days: $" . number_format($lateFee, 2) . "\n";

// Show library constants
echo "\nLibrary policies:\n";
echo "Maximum borrow days: " . Library::MAX_BORROW_DAYS . "\n";
echo "Late fee per day: $" . Library::LATE_FEE_PER_DAY . "\n";

echo "\n=== System Logs ===\n";
$logs = Library::getLogs();
foreach ($logs as $log) {
    echo $log . "\n";
}
?>
```

## Key Takeaways and Best Practices

Understanding PHP's Object-Oriented Programming concepts is like learning to organize and structure your code in a way that mirrors real-world relationships. Here are the essential points to remember:

**Classes and Objects** form the foundation - think of classes as blueprints and objects as the actual items built from those blueprints. Every object maintains its own state while sharing the same structure and behaviors defined in the class.

**Constructors and Destructors** handle the lifecycle of objects. Constructors set up initial state when objects are created, while destructors clean up resources when objects are destroyed.

**Access Modifiers** (public, private, protected) control visibility and encapsulation. Use private for internal implementation details, protected for subclass access, and public for external interfaces.

**Inheritance** allows you to build upon existing classes, promoting code reuse and establishing hierarchical relationships. Remember that child classes inherit all properties and methods from their parents.

**Abstract Classes and Interfaces** define contracts and common structures. Abstract classes provide partial implementation, while interfaces define pure contracts that implementing classes must fulfill.

**Traits** enable horizontal code reuse without inheritance constraints. They're perfect for cross-cutting concerns like logging, timestamps, or validation that apply to multiple unrelated classes.

**Static Methods and Properties** belong to the class rather than instances. Use them for utility functions and shared data that doesn't require object state.

**Namespaces** organize your code and prevent naming conflicts. They become increasingly important as your applications grow in size and complexity.

**Constants** provide immutable values that remain consistent throughout your application. Use them for configuration values, mathematical constants, or any value that shouldn't change.

**Iterators** make your objects work seamlessly with PHP's foreach loops, providing intuitive ways to traverse collections of data.

The power of OOP lies not in using every feature, but in choosing the right tools for each situation. Start with simple classes and objects, then gradually incorporate more advanced features as your understanding deepens and your applications become more complex.

Remember that good object-oriented design is about creating code that's easy to understand, maintain, and extend. Focus on clear responsibilities, logical relationships, and consistent interfaces. With practice, these concepts will become second nature, and you'll find yourself naturally thinking in terms of objects and their interactions.