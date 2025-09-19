Here are examples showing how search, update, and sorting operations work with PHP array functions:

## 1. Search Functionality with `array_filter()` and `stripos()`

```php
<?php
// Sample data - array of products
$products = [
    ['id' => 1, 'name' => 'iPhone 15 Pro', 'category' => 'Electronics', 'price' => 999],
    ['id' => 2, 'name' => 'Samsung Galaxy S24', 'category' => 'Electronics', 'price' => 899],
    ['id' => 3, 'name' => 'MacBook Air', 'category' => 'Computers', 'price' => 1299],
    ['id' => 4, 'name' => 'iPad Pro', 'category' => 'Electronics', 'price' => 799],
    ['id' => 5, 'name' => 'Dell XPS Laptop', 'category' => 'Computers', 'price' => 1199]
];

// Search function using array_filter and stripos
function searchProducts($products, $searchTerm) {
    return array_filter($products, function($product) use ($searchTerm) {
        // stripos returns false if not found, 0 or positive number if found
        return stripos($product['name'], $searchTerm) !== false ||
               stripos($product['category'], $searchTerm) !== false;
    });
}

// Example searches
$searchResults1 = searchProducts($products, 'pro');
echo "Search for 'pro':\n";
print_r($searchResults1);
/*
Output:
Array
(
    [0] => Array
        (
            [id] => 1
            [name] => iPhone 15 Pro
            [category] => Electronics
            [price] => 999
        )
    [3] => Array
        (
            [id] => 4
            [name] => iPad Pro
            [category] => Electronics
            [price] => 799
        )
)
*/

// Multiple criteria search
function advancedSearch($products, $name = '', $category = '', $maxPrice = null) {
    return array_filter($products, function($product) use ($name, $category, $maxPrice) {
        $nameMatch = empty($name) || stripos($product['name'], $name) !== false;
        $categoryMatch = empty($category) || stripos($product['category'], $category) !== false;
        $priceMatch = is_null($maxPrice) || $product['price'] <= $maxPrice;

        return $nameMatch && $categoryMatch && $priceMatch;
    });
}

$filteredProducts = advancedSearch($products, '', 'electronics', 900);
echo "\nElectronics under $900:\n";
print_r($filteredProducts);
?>
```

## 2. Updates with `array_map()`

```php
<?php
// Sample user data
$users = [
    ['id' => 1, 'name' => 'john doe', 'email' => 'JOHN@EXAMPLE.COM', 'salary' => 50000],
    ['id' => 2, 'name' => 'jane smith', 'email' => 'JANE@EXAMPLE.COM', 'salary' => 60000],
    ['id' => 3, 'name' => 'bob johnson', 'email' => 'BOB@EXAMPLE.COM', 'salary' => 55000]
];

// Update function 1: Normalize data formatting
function normalizeUserData($users) {
    return array_map(function($user) {
        return [
            'id' => $user['id'],
            'name' => ucwords(strtolower($user['name'])), // Proper case
            'email' => strtolower($user['email']),        // Lowercase email
            'salary' => $user['salary'],
            'formatted_salary' => '$' . number_format($user['salary']) // Add formatted version
        ];
    }, $users);
}

$normalizedUsers = normalizeUserData($users);
echo "Normalized users:\n";
print_r($normalizedUsers);

// Update function 2: Apply salary increase
function applySalaryIncrease($users, $increasePercentage) {
    return array_map(function($user) use ($increasePercentage) {
        $user['old_salary'] = $user['salary'];
        $user['salary'] = round($user['salary'] * (1 + $increasePercentage / 100));
        $user['increase_amount'] = $user['salary'] - $user['old_salary'];
        return $user;
    }, $users);
}

$usersWithRaise = applySalaryIncrease($users, 10); // 10% increase
echo "\nUsers with 10% salary increase:\n";
print_r($usersWithRaise);

// Update function 3: Add calculated fields
function addCalculatedFields($products) {
    return array_map(function($product) {
        $product['discounted_price'] = $product['price'] * 0.9; // 10% discount
        $product['tax'] = $product['price'] * 0.08; // 8% tax
        $product['total_with_tax'] = $product['price'] + $product['tax'];
        $product['slug'] = strtolower(str_replace(' ', '-', $product['name']));
        return $product;
    }, $products);
}
?>
```

## 3. Sorting with `usort()`

```php
<?php
// Sample data for sorting examples
$employees = [
    ['name' => 'Alice Johnson', 'department' => 'IT', 'salary' => 75000, 'hire_date' => '2020-03-15'],
    ['name' => 'Bob Smith', 'department' => 'HR', 'salary' => 65000, 'hire_date' => '2019-07-22'],
    ['name' => 'Charlie Brown', 'department' => 'IT', 'salary' => 80000, 'hire_date' => '2021-01-10'],
    ['name' => 'Diana Prince', 'department' => 'Marketing', 'salary' => 70000, 'hire_date' => '2018-09-05'],
    ['name' => 'Eve Wilson', 'department' => 'IT', 'salary' => 75000, 'hire_date' => '2020-11-30']
];

// Sort by salary (descending)
function sortBySalaryDesc($employees) {
    $sorted = $employees; // Copy to avoid modifying original
    usort($sorted, function($a, $b) {
        return $b['salary'] - $a['salary']; // Descending order
    });
    return $sorted;
}

$sortedBySalary = sortBySalaryDesc($employees);
echo "Sorted by salary (highest first):\n";
foreach($sortedBySalary as $emp) {
    echo "{$emp['name']}: \${$emp['salary']}\n";
}

// Sort by multiple criteria: department first, then salary
function sortByDepartmentAndSalary($employees) {
    $sorted = $employees;
    usort($sorted, function($a, $b) {
        // First compare by department
        $deptComparison = strcmp($a['department'], $b['department']);
        if ($deptComparison !== 0) {
            return $deptComparison;
        }
        // If departments are same, compare by salary (descending)
        return $b['salary'] - $a['salary'];
    });
    return $sorted;
}

$sortedByDeptSalary = sortByDepartmentAndSalary($employees);
echo "\nSorted by department, then salary:\n";
foreach($sortedByDeptSalary as $emp) {
    echo "{$emp['department']} - {$emp['name']}: \${$emp['salary']}\n";
}

// Sort by hire date (newest first)
function sortByHireDateDesc($employees) {
    $sorted = $employees;
    usort($sorted, function($a, $b) {
        return strtotime($b['hire_date']) - strtotime($a['hire_date']);
    });
    return $sorted;
}

$sortedByDate = sortByHireDateDesc($employees);
echo "\nSorted by hire date (newest first):\n";
foreach($sortedByDate as $emp) {
    echo "{$emp['name']} - Hired: {$emp['hire_date']}\n";
}

// Complex sorting: Custom scoring system
function sortByCustomScore($employees) {
    $sorted = array_map(function($emp) {
        // Calculate a custom score based on salary and tenure
        $yearsWorked = (time() - strtotime($emp['hire_date'])) / (365 * 24 * 3600);
        $emp['score'] = ($emp['salary'] / 1000) + ($yearsWorked * 5); // Custom scoring
        return $emp;
    }, $employees);

    usort($sorted, function($a, $b) {
        return $b['score'] <=> $a['score']; // PHP 7+ spaceship operator
    });

    return $sorted;
}

$sortedByScore = sortByCustomScore($employees);
echo "\nSorted by custom score:\n";
foreach($sortedByScore as $emp) {
    echo "{$emp['name']} - Score: " . round($emp['score'], 2) . "\n";
}
?>
```

## Combined Example: Complete CRUD-like Operations

```php
<?php
class ProductManager {
    private $products;

    public function __construct($products) {
        $this->products = $products;
    }

    // Search products
    public function search($term) {
        return array_filter($this->products, function($product) use ($term) {
            return stripos($product['name'], $term) !== false ||
                   stripos($product['category'], $term) !== false;
        });
    }

    // Update all products (apply discount)
    public function applyDiscount($percentage) {
        $this->products = array_map(function($product) use ($percentage) {
            $product['original_price'] = $product['price'];
            $product['price'] = round($product['price'] * (1 - $percentage / 100), 2);
            $product['discount'] = $percentage;
            return $product;
        }, $this->products);

        return $this->products;
    }

    // Sort products
    public function sortBy($field, $direction = 'asc') {
        $sorted = $this->products;
        usort($sorted, function($a, $b) use ($field, $direction) {
            if ($direction === 'desc') {
                return $b[$field] <=> $a[$field];
            }
            return $a[$field] <=> $b[$field];
        });

        return $sorted;
    }

    // Get filtered, updated, and sorted results
    public function process($searchTerm = '', $discount = 0, $sortBy = 'name', $sortDirection = 'asc') {
        $results = $this->products;

        // Filter
        if (!empty($searchTerm)) {
            $results = array_filter($results, function($product) use ($searchTerm) {
                return stripos($product['name'], $searchTerm) !== false;
            });
        }

        // Update
        if ($discount > 0) {
            $results = array_map(function($product) use ($discount) {
                $product['discounted_price'] = round($product['price'] * (1 - $discount / 100), 2);
                return $product;
            }, $results);
        }

        // Sort
        usort($results, function($a, $b) use ($sortBy, $sortDirection) {
            if ($sortDirection === 'desc') {
                return $b[$sortBy] <=> $a[$sortBy];
            }
            return $a[$sortBy] <=> $b[$sortBy];
        });

        return $results;
    }
}

// Usage example
$products = [
    ['id' => 1, 'name' => 'iPhone 15 Pro', 'category' => 'Electronics', 'price' => 999],
    ['id' => 2, 'name' => 'Samsung Galaxy S24', 'category' => 'Electronics', 'price' => 899],
    ['id' => 3, 'name' => 'MacBook Air', 'category' => 'Computers', 'price' => 1299]
];

$manager = new ProductManager($products);
$results = $manager->process('pro', 10, 'price', 'desc');
print_r($results);
?>
```

These examples show how `array_filter()` with `stripos()` enables flexible search functionality, `array_map()` transforms and updates array data, and `usort()` provides powerful custom sorting capabilities. Each function serves a specific purpose in data manipulation workflows.
