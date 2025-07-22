## Chapter 6: PHP Arrays - Your Data's Swiss Army Knife

### Understanding Arrays as Data Containers

Arrays are like containers that can hold multiple pieces of related information. Think of them as boxes with compartments - each compartment can hold a different item, but they're all organized in one container. Unlike variables that can only hold one value at a time, arrays can hold dozens, hundreds, or even thousands of values.

In everyday life, you already use array-like concepts. A shopping list is an array of items to buy. A phone book is an array of names and phone numbers. A restaurant menu is an array of dishes and prices. PHP arrays let you represent these real-world collections in your code.

### Indexed Arrays: The Numbered List

The simplest type of array is an indexed array, where each element has a number (index) starting from 0. Think of it as a numbered list where the first item is #0, the second is #1, and so on.

```php
<?php
// Creating indexed arrays
$fruits = ["apple", "banana", "orange", "grape"];
$numbers = [10, 20, 30, 40, 50];
$mixed = ["Hello", 42, true, 3.14];

// Accessing elements by index
echo $fruits[0];  // "apple" - arrays start at index 0
echo $fruits[1];  // "banana"
echo $fruits[2];  // "orange"
echo $fruits[3];  // "grape"

// Why do arrays start at 0?
// This is a programming convention that makes certain operations more efficient
// Think of the index as "how many steps from the beginning"
// The first element is 0 steps from the beginning

// Adding elements to the end
$fruits[] = "strawberry";  // Adds to the end
$fruits[5] = "blueberry";  // Adds at specific index

// You can also create arrays one element at a time
$colors = [];  // Empty array
$colors[0] = "red";
$colors[1] = "green";
$colors[2] = "blue";

// Or skip the index and let PHP assign it automatically
$colors[] = "yellow";  // PHP will use index 3
$colors[] = "purple";  // PHP will use index 4

echo "We have " . count($colors) . " colors\n";
?>
```

### Understanding Array Indices

Let's explore how array indices work with a practical example:

```php
<?php
// Creating a simple student grade system
$grades = [85, 92, 78, 96, 88];

// Accessing grades by position
echo "First student's grade: " . $grades[0] . "\n";
echo "Last student's grade: " . $grades[4] . "\n";

// Common mistake: trying to access an index that doesn't exist
// echo $grades[5];  // This would cause an error!

// Safe way to access array elements
if (isset($grades[5])) {
    echo "Fifth student's grade: " . $grades[5] . "\n";
} else {
    echo "There is no fifth student\n";
}

// Finding the highest and lowest grades
$highest = $grades[0];
$lowest = $grades[0];

for ($i = 1; $i < count($grades); $i++) {
    if ($grades[$i] > $highest) {
        $highest = $grades[$i];
    }
    if ($grades[$i] < $lowest) {
        $lowest = $grades[$i];
    }
}

echo "Highest grade: $highest\n";
echo "Lowest grade: $lowest\n";

// Calculating average
$total = 0;
for ($i = 0; $i < count($grades); $i++) {
    $total += $grades[$i];
}
$average = $total / count($grades);
echo "Average grade: " . number_format($average, 2) . "\n";
?>
```

### Associative Arrays: The Labeled Container

Associative arrays use meaningful names (keys) instead of numbers to identify each element. This makes your code much more readable and self-documenting.

```php
<?php
// Creating associative arrays
$person = [
    "first_name" => "John",
    "last_name" => "Doe",
    "age" => 30,
    "email" => "john@example.com",
    "city" => "New York"
];

// Accessing elements by key
echo "Name: " . $person["first_name"] . " " . $person["last_name"] . "\n";
echo "Age: " . $person["age"] . "\n";
echo "Email: " . $person["email"] . "\n";

// Adding new elements
$person["phone"] = "555-1234";
$person["married"] = true;

// Modifying existing elements
$person["age"] = 31;  // Birthday!

// The power of associative arrays becomes clear when you compare:
// Instead of remembering that $person[2] is the age
// You can use $person["age"] which is self-explanatory

// Creating a product catalog
$products = [
    "laptop" => [
        "name" => "Gaming Laptop",
        "price" => 1299.99,
        "stock" => 5,
        "category" => "Electronics"
    ],
    "mouse" => [
        "name" => "Wireless Mouse",
        "price" => 29.99,
        "stock" => 25,
        "category" => "Electronics"
    ],
    "book" => [
        "name" => "PHP Programming Guide",
        "price" => 39.99,
        "stock" => 12,
        "category" => "Books"
    ]
];

// Accessing nested associative arrays
echo "Product: " . $products["laptop"]["name"] . "\n";
echo "Price: $" . $products["laptop"]["price"] . "\n";
echo "Stock: " . $products["laptop"]["stock"] . " units\n";
?>
```

### Creating Arrays: Multiple Methods

PHP provides several ways to create arrays, each useful in different situations:

```php
<?php
// Method 1: Array literal (most common)
$colors = ["red", "green", "blue"];

// Method 2: Using array() function (older style)
$animals = array("cat", "dog", "bird");

// Method 3: Creating empty array and adding elements
$shopping_list = [];
$shopping_list[] = "milk";
$shopping_list[] = "bread";
$shopping_list[] = "eggs";

// Method 4: Using range() for sequences
$numbers = range(1, 10);  // [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
$letters = range('a', 'z');  // ['a', 'b', 'c', ..., 'z']

// Method 5: Using array_fill() for repeated values
$zeros = array_fill(0, 5, 0);  // [0, 0, 0, 0, 0]
$defaults = array_fill(0, 3, "default");  // ["default", "default", "default"]

// Method 6: Creating arrays from strings
$words = explode(" ", "Hello world from PHP");  // ["Hello", "world", "from", "PHP"]
$csv_data = explode(",", "apple,banana,orange");  // ["apple", "banana", "orange"]

// Method 7: Creating arrays with specific keys
$grades = array_combine(
    ["Alice", "Bob", "Charlie"],  // Keys
    [85, 92, 78]                  // Values
);
// Result: ["Alice" => 85, "Bob" => 92, "Charlie" => 78]

echo "Alice's grade: " . $grades["
```
