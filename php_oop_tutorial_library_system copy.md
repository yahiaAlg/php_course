# Complete PHP OOP Tutorial: Library Management System

## Table of Contents
1. [Introduction to OOP](#introduction-to-oop)
2. [Classes and Objects](#classes-and-objects)
3. [Properties and Methods](#properties-and-methods)
4. [Constructors](#constructors)
5. [Inheritance](#inheritance)
6. [Abstract Classes](#abstract-classes)
7. [Interfaces](#interfaces)
8. [Static Properties and Methods](#static-properties-and-methods)
9. [Access Modifiers](#access-modifiers)
10. [Complete Library System Implementation](#complete-library-system-implementation)

---

## Introduction to OOP

**Object-Oriented Programming (OOP)** is a programming paradigm that organizes code around objects rather than functions. Think of it like building with LEGO blocks - each block (object) has specific properties and can do certain things (methods).

### Why Use OOP?
- **Organization**: Code is more organized and easier to maintain
- **Reusability**: You can reuse code without rewriting it
- **Scalability**: Easy to add new features
- **Real-world modeling**: Perfect for modeling real-world systems like our library

---

## Classes and Objects

### What is a Class?
A **class** is like a blueprint or template. Think of it as a cookie cutter - it defines the shape and structure.

### What is an Object?
An **object** is an instance of a class. It's like the actual cookie made from the cookie cutter.

```php
<?php
// Define a simple Book class (the blueprint)
class Book {
    // This is empty for now, we'll add stuff here
}

// Create objects (instances) of the Book class
$book1 = new Book();  // This creates a Book object
$book2 = new Book();  // This creates another Book object
?>
```

---

## Properties and Methods

### Properties
Properties are like characteristics or attributes of an object. A book has a title, author, ISBN, etc.

### Methods
Methods are like actions that an object can perform. A book can be borrowed, returned, displayed, etc.

```php
<?php
class Book {
    // Properties (characteristics)
    public $title;
    public $author;
    public $isbn;
    public $isAvailable;
    
    // Methods (actions)
    public function displayInfo() {
        return "Title: {$this->title}, Author: {$this->author}";
    }
    
    public function borrow() {
        if ($this->isAvailable) {
            $this->isAvailable = false;
            return "Book borrowed successfully!";
        }
        return "Book is not available!";
    }
    
    public function returnBook() {
        $this->isAvailable = true;
        return "Book returned successfully!";
    }
}

// Using the class
$book = new Book();
$book->title = "Harry Potter";
$book->author = "J.K. Rowling";
$book->isbn = "978-0439708180";
$book->isAvailable = true;

echo $book->displayInfo();  // Output: Title: Harry Potter, Author: J.K. Rowling
echo $book->borrow();       // Output: Book borrowed successfully!
?>
```

**Key Points:**
- `$this` refers to the current object
- `->` is used to access properties and methods of an object
- `public` means the property/method can be accessed from anywhere

---

## Constructors

A **constructor** is a special method that runs automatically when you create a new object. It's like setting up the initial state of your object.

```php
<?php
class Book {
    public $title;
    public $author;
    public $isbn;
    public $isAvailable;
    
    // Constructor - runs when new Book() is called
    public function __construct($title, $author, $isbn) {
        $this->title = $title;
        $this->author = $author;
        $this->isbn = $isbn;
        $this->isAvailable = true;  // New books are always available
    }
    
    public function displayInfo() {
        $status = $this->isAvailable ? "Available" : "Borrowed";
        return "Title: {$this->title}, Author: {$this->author}, Status: {$status}";
    }
}

// Now we can create books more easily
$book1 = new Book("1984", "George Orwell", "978-0451524935");
$book2 = new Book("To Kill a Mockingbird", "Harper Lee", "978-0061120084");

echo $book1->displayInfo();
?>
```

---

## Inheritance

**Inheritance** allows a class to inherit properties and methods from another class. It's like a child inheriting traits from a parent.

```php
<?php
// Base class (parent)
class LibraryItem {
    public $title;
    public $isAvailable;
    
    public function __construct($title) {
        $this->title = $title;
        $this->isAvailable = true;
    }
    
    public function borrow() {
        if ($this->isAvailable) {
            $this->isAvailable = false;
            return "{$this->title} borrowed successfully!";
        }
        return "{$this->title} is not available!";
    }
}

// Child class - inherits from LibraryItem
class Book extends LibraryItem {
    public $author;
    public $isbn;
    
    public function __construct($title, $author, $isbn) {
        parent::__construct($title);  // Call parent constructor
        $this->author = $author;
        $this->isbn = $isbn;
    }
    
    public function displayInfo() {
        return "Book: {$this->title} by {$this->author}";
    }
}

// Another child class
class Article extends LibraryItem {
    public $journal;
    public $volume;
    
    public function __construct($title, $journal, $volume) {
        parent::__construct($title);
        $this->journal = $journal;
        $this->volume = $volume;
    }
    
    public function displayInfo() {
        return "Article: {$this->title} from {$this->journal}, Vol. {$this->volume}";
    }
}

$book = new Book("Dune", "Frank Herbert", "978-0441172719");
$article = new Article("Climate Change Effects", "Science Journal", "45");

echo $book->displayInfo();     // Book: Dune by Frank Herbert
echo $article->displayInfo();  // Article: Climate Change Effects from Science Journal, Vol. 45
?>
```

**Key Points:**
- `extends` creates inheritance relationship
- `parent::` calls methods from the parent class
- Child classes inherit all public/protected properties and methods

---

## Abstract Classes

**Abstract classes** are like incomplete blueprints. They define some methods but leave others for child classes to implement. You cannot create objects directly from abstract classes.

```php
<?php
// Abstract class - cannot be instantiated directly
abstract class User {
    protected $name;
    protected $id;
    protected $borrowedItems;
    
    public function __construct($name, $id) {
        $this->name = $name;
        $this->id = $id;
        $this->borrowedItems = [];
    }
    
    // Regular method that all users will have
    public function getName() {
        return $this->name;
    }
    
    public function borrowItem($item) {
        $this->borrowedItems[] = $item;
    }
    
    // Abstract method - child classes MUST implement this
    abstract public function getPermissions();
    abstract public function getMaxBorrowLimit();
}

// Concrete class that extends the abstract class
class LibraryMember extends User {
    public function getPermissions() {
        return ["borrow_books", "reserve_books"];
    }
    
    public function getMaxBorrowLimit() {
        return 5;  // Regular members can borrow 5 items
    }
}

class Librarian extends User {
    public function getPermissions() {
        return ["borrow_books", "add_books", "remove_books", "manage_users"];
    }
    
    public function getMaxBorrowLimit() {
        return 50;  // Librarians can borrow more items
    }
    
    public function addBookToLibrary($book) {
        return "Book '{$book->title}' added to library by {$this->name}";
    }
}

// Usage
$member = new LibraryMember("John Doe", "M001");
$librarian = new Librarian("Jane Smith", "L001");

echo "Member permissions: " . implode(", ", $member->getPermissions());
echo "Librarian permissions: " . implode(", ", $librarian->getPermissions());
?>
```

**Key Points:**
- Abstract classes use the `abstract` keyword
- Cannot create objects from abstract classes directly
- Child classes MUST implement all abstract methods
- Can contain both abstract and regular methods

---

## Interfaces

**Interfaces** are like contracts. They define what methods a class must have, but not how they work. A class can implement multiple interfaces.

```php
<?php
// Interface defining what methods must be available for displaying information
interface Displayable {
    public function displayInfo();
    public function getShortDescription();
}

// Interface for items that can generate reports
interface Reportable {
    public function generateReport();
}

class Book implements Displayable, Reportable {
    private $title;
    private $author;
    private $isbn;
    private $borrowCount;
    
    public function __construct($title, $author, $isbn) {
        $this->title = $title;
        $this->author = $author;
        $this->isbn = $isbn;
        $this->borrowCount = 0;
    }
    
    // Must implement because of Displayable interface
    public function displayInfo() {
        return "Book: {$this->title} by {$this->author} (ISBN: {$this->isbn})";
    }
    
    public function getShortDescription() {
        return "{$this->title} - {$this->author}";
    }
    
    // Must implement because of Reportable interface
    public function generateReport() {
        return "Book Report: {$this->title} has been borrowed {$this->borrowCount} times";
    }
    
    public function incrementBorrowCount() {
        $this->borrowCount++;
    }
}

class Article implements Displayable {
    private $title;
    private $journal;
    private $authors;
    
    public function __construct($title, $journal, $authors) {
        $this->title = $title;
        $this->journal = $journal;
        $this->authors = $authors;
    }
    
    public function displayInfo() {
        return "Article: {$this->title} in {$this->journal} by " . implode(", ", $this->authors);
    }
    
    public function getShortDescription() {
        return "{$this->title} ({$this->journal})";
    }
}

$book = new Book("The Hobbit", "J.R.R. Tolkien", "978-0547928227");
$article = new Article("AI in Libraries", "Tech Today", ["Dr. Smith", "Prof. Johnson"]);

echo $book->displayInfo();
echo $article->displayInfo();
?>
```

**Key Points:**
- Interfaces use the `interface` keyword
- Classes `implement` interfaces
- All interface methods must be implemented
- A class can implement multiple interfaces
- Interfaces only define method signatures, not implementations

---

## Static Properties and Methods

**Static** properties and methods belong to the class itself, not to specific objects. They're like shared resources that all objects of the class can access.

```php
<?php
class LibraryStats {
    // Static properties - shared by all instances
    private static $totalBooks = 0;
    private static $totalUsers = 0;
    private static $totalBorrowedBooks = 0;
    
    // Static methods - can be called without creating an object
    public static function addBook() {
        self::$totalBooks++;
    }
    
    public static function addUser() {
        self::$totalUsers++;
    }
    
    public static function borrowBook() {
        self::$totalBorrowedBooks++;
    }
    
    public static function returnBook() {
        if (self::$totalBorrowedBooks > 0) {
            self::$totalBorrowedBooks--;
        }
    }
    
    public static function getTotalBooks() {
        return self::$totalBooks;
    }
    
    public static function getTotalUsers() {
        return self::$totalUsers;
    }
    
    public static function getBorrowedBooksCount() {
        return self::$totalBorrowedBooks;
    }
    
    public static function getAvailableBooksCount() {
        return self::$totalBooks - self::$totalBorrowedBooks;
    }
    
    public static function getLibraryReport() {
        return "Library Statistics:\n" .
               "Total Books: " . self::$totalBooks . "\n" .
               "Total Users: " . self::$totalUsers . "\n" .
               "Books Currently Borrowed: " . self::$totalBorrowedBooks . "\n" .
               "Books Available: " . self::getAvailableBooksCount();
    }
}

// Using static methods - no need to create objects
LibraryStats::addBook();
LibraryStats::addBook();
LibraryStats::addBook();
LibraryStats::addUser();
LibraryStats::addUser();
LibraryStats::borrowBook();

echo LibraryStats::getLibraryReport();
?>
```

**Key Points:**
- Static properties/methods use the `static` keyword
- Access with `ClassName::methodName()` or `ClassName::$propertyName`
- Use `self::` inside the class to reference static members
- Static members belong to the class, not to specific objects

---

## Access Modifiers

Access modifiers control who can access properties and methods:

- **public**: Accessible from anywhere
- **private**: Accessible only within the same class
- **protected**: Accessible within the class and its subclasses

```php
<?php
class BankAccount {
    public $accountNumber;     // Anyone can access
    protected $balance;        // Only this class and subclasses
    private $pin;             // Only this class
    
    public function __construct($accountNumber, $initialBalance, $pin) {
        $this->accountNumber = $accountNumber;
        $this->balance = $initialBalance;
        $this->pin = $pin;
    }
    
    public function getBalance() {
        return $this->balance;  // Public method to access protected property
    }
    
    private function validatePin($inputPin) {
        return $this->pin === $inputPin;  // Private method
    }
    
    public function withdraw($amount, $inputPin) {
        if (!$this->validatePin($inputPin)) {
            return "Invalid PIN";
        }
        
        if ($amount <= $this->balance) {
            $this->balance -= $amount;
            return "Withdrawn: $" . $amount;
        }
        return "Insufficient funds";
    }
}

$account = new BankAccount("123456", 1000, "1234");
echo $account->accountNumber;        // OK - public
echo $account->getBalance();         // OK - public method
// echo $account->balance;           // ERROR - protected
// echo $account->pin;               // ERROR - private
?>
```

---

## Complete Library System Implementation

Now let's put everything together into a complete library management system:

```php
<?php

// Interface for displayable items
interface Displayable {
    public function displayInfo();
    public function getShortDescription();
}

// Interface for borrowable items
interface Borrowable {
    public function borrow($user);
    public function returnItem();
    public function isAvailable();
}

// Library statistics class with static methods
class LibraryStats {
    private static $totalBooks = 0;
    private static $totalArticles = 0;
    private static $totalUsers = 0;
    private static $borrowedItems = [];
    
    public static function addBook() {
        self::$totalBooks++;
    }
    
    public static function addArticle() {
        self::$totalArticles++;
    }
    
    public static function addUser() {
        self::$totalUsers++;
    }
    
    public static function borrowItem($itemId, $userId, $dueDate) {
        self::$borrowedItems[$itemId] = [
            'userId' => $userId,
            'dueDate' => $dueDate,
            'borrowDate' => date('Y-m-d')
        ];
    }
    
    public static function returnItem($itemId) {
        unset(self::$borrowedItems[$itemId]);
    }
    
    public static function getOverdueUsersCount() {
        $today = date('Y-m-d');
        $overdueCount = 0;
        
        foreach (self::$borrowedItems as $borrowInfo) {
            if ($borrowInfo['dueDate'] < $today) {
                $overdueCount++;
            }
        }
        
        return $overdueCount;
    }
    
    public static function getTotalBooks() {
        return self::$totalBooks;
    }
    
    public static function getTotalUsers() {
        return self::$totalUsers;
    }
    
    public static function getBorrowedItemsCount() {
        return count(self::$borrowedItems);
    }
    
    public static function getLibraryReport() {
        return "=== LIBRARY STATISTICS ===\n" .
               "Total Books: " . self::$totalBooks . "\n" .
               "Total Articles: " . self::$totalArticles . "\n" .
               "Total Users: " . self::$totalUsers . "\n" .
               "Currently Borrowed Items: " . self::getBorrowedItemsCount() . "\n" .
               "Overdue Items: " . self::getOverdueUsersCount() . "\n";
    }
}

// Abstract base class for all users
abstract class User {
    protected $name;
    protected $id;
    protected $borrowedItems;
    protected $borrowDate;
    
    public function __construct($name, $id) {
        $this->name = $name;
        $this->id = $id;
        $this->borrowedItems = [];
        LibraryStats::addUser();
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getBorrowedItems() {
        return $this->borrowedItems;
    }
    
    public function borrowItem($item) {
        if (count($this->borrowedItems) < $this->getMaxBorrowLimit()) {
            $this->borrowedItems[] = $item;
            $dueDate = date('Y-m-d', strtotime('+14 days')); // 2 weeks from now
            LibraryStats::borrowItem($item->getId(), $this->id, $dueDate);
            return true;
        }
        return false;
    }
    
    public function returnItem($item) {
        $key = array_search($item, $this->borrowedItems);
        if ($key !== false) {
            unset($this->borrowedItems[$key]);
            LibraryStats::returnItem($item->getId());
            return true;
        }
        return false;
    }
    
    // Abstract methods that child classes must implement
    abstract public function getPermissions();
    abstract public function getMaxBorrowLimit();
}

// Concrete user classes
class LibraryMember extends User {
    public function getPermissions() {
        return ["borrow_books", "reserve_books", "view_catalog"];
    }
    
    public function getMaxBorrowLimit() {
        return 5;
    }
}

class Librarian extends User {
    private $department;
    
    public function __construct($name, $id, $department) {
        parent::__construct($name, $id);
        $this->department = $department;
    }
    
    public function getPermissions() {
        return ["borrow_books", "add_books", "remove_books", "manage_users", "view_reports"];
    }
    
    public function getMaxBorrowLimit() {
        return 20;
    }
    
    public function addBookToLibrary($book) {
        LibraryStats::addBook();
        return "Book '{$book->getTitle()}' added to library by {$this->name}";
    }
    
    public function getDepartment() {
        return $this->department;
    }
}

// Base class for library items
abstract class LibraryItem implements Displayable, Borrowable {
    protected $id;
    protected $title;
    protected $isAvailable;
    protected $borrowedBy;
    protected $borrowDate;
    
    public function __construct($id, $title) {
        $this->id = $id;
        $this->title = $title;
        $this->isAvailable = true;
        $this->borrowedBy = null;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getTitle() {
        return $this->title;
    }
    
    public function isAvailable() {
        return $this->isAvailable;
    }
    
    public function borrow($user) {
        if ($this->isAvailable && $user->borrowItem($this)) {
            $this->isAvailable = false;
            $this->borrowedBy = $user;
            $this->borrowDate = date('Y-m-d H:i:s');
            return "'{$this->title}' borrowed by {$user->getName()}";
        }
        return "Cannot borrow '{$this->title}'";
    }
    
    public function returnItem() {
        if (!$this->isAvailable && $this->borrowedBy) {
            $user = $this->borrowedBy;
            $user->returnItem($this);
            $this->isAvailable = true;
            $this->borrowedBy = null;
            $this->borrowDate = null;
            return "'{$this->title}' returned successfully";
        }
        return "'{$this->title}' was not borrowed";
    }
    
    public function getBorrowedBy() {
        return $this->borrowedBy;
    }
}

// Concrete item classes
class Book extends LibraryItem {
    private $author;
    private $isbn;
    private $genre;
    private $pageCount;
    
    public function __construct($id, $title, $author, $isbn, $genre = "General", $pageCount = 0) {
        parent::__construct($id, $title);
        $this->author = $author;
        $this->isbn = $isbn;
        $this->genre = $genre;
        $this->pageCount = $pageCount;
        LibraryStats::addBook();
    }
    
    public function displayInfo() {
        $status = $this->isAvailable ? "Available" : "Borrowed";
        $borrowInfo = !$this->isAvailable ? " (Borrowed by: {$this->borrowedBy->getName()})" : "";
        
        return "BOOK INFO:\n" .
               "ID: {$this->id}\n" .
               "Title: {$this->title}\n" .
               "Author: {$this->author}\n" .
               "ISBN: {$this->isbn}\n" .
               "Genre: {$this->genre}\n" .
               "Pages: {$this->pageCount}\n" .
               "Status: {$status}{$borrowInfo}\n";
    }
    
    public function getShortDescription() {
        return "{$this->title} by {$this->author}";
    }
    
    public function getAuthor() {
        return $this->author;
    }
    
    public function getIsbn() {
        return $this->isbn;
    }
    
    public function getGenre() {
        return $this->genre;
    }
}

class Article extends LibraryItem {
    private $journal;
    private $volume;
    private $issue;
    private $authors;
    private $publicationDate;
    
    public function __construct($id, $title, $journal, $volume, $issue, $authors, $publicationDate) {
        parent::__construct($id, $title);
        $this->journal = $journal;
        $this->volume = $volume;
        $this->issue = $issue;
        $this->authors = is_array($authors) ? $authors : [$authors];
        $this->publicationDate = $publicationDate;
        LibraryStats::addArticle();
    }
    
    public function displayInfo() {
        $status = $this->isAvailable ? "Available" : "Borrowed";
        $borrowInfo = !$this->isAvailable ? " (Borrowed by: {$this->borrowedBy->getName()})" : "";
        $authorsStr = implode(", ", $this->authors);
        
        return "ARTICLE INFO:\n" .
               "ID: {$this->id}\n" .
               "Title: {$this->title}\n" .
               "Journal: {$this->journal}\n" .
               "Volume: {$this->volume}, Issue: {$this->issue}\n" .
               "Authors: {$authorsStr}\n" .
               "Publication Date: {$this->publicationDate}\n" .
               "Status: {$status}{$borrowInfo}\n";
    }
    
    public function getShortDescription() {
        return "{$this->title} ({$this->journal} Vol.{$this->volume})";
    }
    
    public function getJournal() {
        return $this->journal;
    }
    
    public function getAuthors() {
        return $this->authors;
    }
}

// Demo usage of the complete system
echo "=== LIBRARY MANAGEMENT SYSTEM DEMO ===\n\n";

// Create users
$member1 = new LibraryMember("Alice Johnson", "M001");
$member2 = new LibraryMember("Bob Smith", "M002");
$librarian = new Librarian("Dr. Sarah Wilson", "L001", "Reference");

echo "Users created:\n";
echo "- {$member1->getName()} (Member, Max borrow: {$member1->getMaxBorrowLimit()})\n";
echo "- {$member2->getName()} (Member, Max borrow: {$member2->getMaxBorrowLimit()})\n";
echo "- {$librarian->getName()} (Librarian, Max borrow: {$librarian->getMaxBorrowLimit()})\n\n";

// Create books and articles
$book1 = new Book("B001", "To Kill a Mockingbird", "Harper Lee", "978-0061120084", "Fiction", 324);
$book2 = new Book("B002", "1984", "George Orwell", "978-0451524935", "Dystopian Fiction", 328);
$book3 = new Book("B003", "The Great Gatsby", "F. Scott Fitzgerald", "978-0743273565", "Classic", 180);

$article1 = new Article("A001", "Climate Change and Its Effects", "Environmental Science", "45", "3", 
                       ["Dr. Jane Doe", "Prof. John Smith"], "2024-01-15");
$article2 = new Article("A002", "Artificial Intelligence in Libraries", "Information Technology Review", "12", "2",
                       ["Dr. Alice Brown", "Dr. Bob Wilson", "Prof. Carol Davis"], "2024-02-20");

echo "Library items created:\n";
echo "- " . $book1->getShortDescription() . "\n";
echo "- " . $book2->getShortDescription() . "\n";
echo "- " . $book3->getShortDescription() . "\n";
echo "- " . $article1->getShortDescription() . "\n";
echo "- " . $article2->getShortDescription() . "\n\n";

// Display detailed information
echo $book1->displayInfo() . "\n";
echo $article1->displayInfo() . "\n";

// Demonstrate borrowing
echo "=== BORROWING DEMO ===\n";
echo $book1->borrow($member1) . "\n";
echo $book2->borrow($member2) . "\n";
echo $article1->borrow($librarian) . "\n";

// Try to borrow an already borrowed book
echo $book1->borrow($member2) . "\n\n";

// Show updated status
echo "=== UPDATED BOOK STATUS ===\n";
echo $book1->displayInfo() . "\n";

// Demonstrate returning
echo "=== RETURNING DEMO ===\n";
echo $book1->returnItem() . "\n";
echo $book1->displayInfo() . "\n";

// Show library statistics
echo LibraryStats::getLibraryReport();

echo "\n=== SYSTEM FEATURES DEMONSTRATED ===\n";
echo "✓ Abstract Classes (User)\n";
echo "✓ Inheritance (LibraryMember, Librarian extend User)\n";
echo "✓ Interfaces (Displayable, Borrowable)\n";
echo "✓ Static Methods and Properties (LibraryStats)\n";
echo "✓ Access Modifiers (public, private, protected)\n";
echo "✓ Polymorphism (Books and Articles implement same interfaces)\n";
echo "✓ Encapsulation (Private properties with public methods)\n";
echo "✓ Real-world modeling (Complete library system)\n";

?>
```

## Summary

Congratulations! You've learned the fundamental concepts of PHP OOP:

1. **Classes and Objects**: Blueprints and instances
2. **Properties and Methods**: Characteristics and behaviors
3. **Constructors**: Automatic setup when creating objects
4. **Inheritance**: Child classes extending parent classes
5. **Abstract Classes**: Incomplete blueprints that define structure
6. **Interfaces**: Contracts that define required methods
7. **Static Members**: Class-level properties and methods
8. **Access Modifiers**: Controlling visibility and access

The library management system demonstrates how these concepts work together to create a real-world application. Each concept builds upon the previous ones, creating a robust and maintainable system.

### Next Steps
- Practice creating your own classes and objects
- Experiment with different inheritance hierarchies
- Try implementing additional interfaces
- Add more features to the library system
- Explore advanced OOP concepts like traits and namespaces

Remember: OOP is about modeling real-world problems in code. Think about the relationships between objects, what they can do, and how they interact with each other!