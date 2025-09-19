<?php
// Sample products database
$products = [
    ['id' => 1, 'name' => 'iPhone 15 Pro', 'category' => 'Electronics', 'price' => 999],
    ['id' => 2, 'name' => 'Samsung Galaxy S24', 'category' => 'Electronics', 'price' => 899],
    ['id' => 3, 'name' => 'MacBook Air', 'category' => 'Computers', 'price' => 1299],
    ['id' => 4, 'name' => 'iPad Pro', 'category' => 'Electronics', 'price' => 799],
    ['id' => 5, 'name' => 'Dell XPS Laptop', 'category' => 'Computers', 'price' => 1199],
    ['id' => 6, 'name' => 'iPhone 14', 'category' => 'Electronics', 'price' => 699],
    ['id' => 7, 'name' => 'HP Gaming Laptop', 'category' => 'Computers', 'price' => 1099],
    ['id' => 8, 'name' => 'Apple Watch', 'category' => 'Electronics', 'price' => 399],
    ['id' => 9, 'name' => 'Gaming Mouse', 'category' => 'Accessories', 'price' => 59],
    ['id' => 10, 'name' => 'Wireless Headphones', 'category' => 'Accessories', 'price' => 149]
];

// Get unique categories for the dropdown
$categories = array_unique(array_column($products, 'category'));
sort($categories);

// Get form data
$min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : '';
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : '';
$search_term = isset($_GET['search_term']) ? trim($_GET['search_term']) : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

// Apply filters
$filtered_products = $products;

// Filter by price range
if ($min_price !== '' || $max_price !== '') {
    $filtered_products = array_filter($filtered_products, function ($product) use ($min_price, $max_price) {
        $price_check = true;
        if ($min_price !== '' && $product['price'] < $min_price) {
            $price_check = false;
        }
        if ($max_price !== '' && $product['price'] > $max_price) {
            $price_check = false;
        }
        return $price_check;
    });
}

// Filter by search term in name
if ($search_term !== '') {
    $filtered_products = array_filter($filtered_products, function ($product) use ($search_term) {
        return stripos($product['name'], $search_term) !== false;
    });
}

// Filter by category
if ($category_filter !== '' && $category_filter !== 'all') {
    $filtered_products = array_filter($filtered_products, function ($product) use ($category_filter) {
        return $product['category'] === $category_filter;
    });
}

// Show active filters
$active_filters = [];
if ($min_price !== '') $active_filters[] = "Min Price: $" . $min_price;
if ($max_price !== '') $active_filters[] = "Max Price: $" . $max_price;
if ($search_term !== '') $active_filters[] = "Search: '" . htmlspecialchars($search_term) . "'";
if ($category_filter !== '' && $category_filter !== 'all') $active_filters[] = "Category: " . $category_filter;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Search Tutorial</title>
    <link rel="stylesheet" href="search.css">
</head>

<body>
    <div class="container">
        <h1>🔍 Product Search & Filter Tutorial</h1>

        <div class="info-box">
            <strong>How it works:</strong> Use the form below to filter products by price range, search for specific terms in product names, and filter by category. The PHP code will process your filters and show the results.
        </div>



        <div class="form-section">
            <h2>🎛️ Search & Filter Controls</h2>
            <form method="GET" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="min_price">Min Price ($)</label>
                        <input type="number" id="min_price" name="min_price"
                            value="<?= htmlspecialchars($min_price) ?>"
                            placeholder="e.g. 100" min="0">
                    </div>
                    <div class="form-group">
                        <label for="max_price">Max Price ($)</label>
                        <input type="number" id="max_price" name="max_price"
                            value="<?= htmlspecialchars($max_price) ?>"
                            placeholder="e.g. 1000" min="0">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="search_term">Search in Product Name</label>
                        <input type="text" id="search_term" name="search_term"
                            value="<?= htmlspecialchars($search_term) ?>"
                            placeholder="e.g. iPhone, laptop, etc.">
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category">
                            <option value="all" <?= $category_filter === 'all' ? 'selected' : '' ?>>All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= htmlspecialchars($category) ?>"
                                    <?= $category_filter === $category ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn">🔍 Search & Filter</button>
                    <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-reset">🔄 Reset All</a>
                </div>
            </form>
        </div>

        <?php if (!empty($active_filters)): ?>
            <div class="filter-summary">
                <strong>Active Filters:</strong> <?= implode(' | ', $active_filters) ?>
                <br><strong>Results Found:</strong> <?= count($filtered_products) ?> out of <?= count($products) ?> products
            </div>
        <?php endif; ?>

        <div class="results-section">
            <h2>📦 Original Products (<?= count($products) ?> items)</h2>
            <pre><?php print_r($products); ?></pre>

            <h2>🎯 Filtered Results (<?= count($filtered_products) ?> items)</h2>
            <?php if (empty($filtered_products)): ?>
                <div style="padding: 20px; text-align: center; color: #666; background: #f8f9fa; border-radius: 5px;">
                    <strong>No products found matching your criteria.</strong><br>
                    Try adjusting your filters or search terms.
                </div>
            <?php else: ?>
                <pre><?php print_r(array_values($filtered_products)); ?></pre>
            <?php endif; ?>
        </div>

        <div style="margin-top: 40px; padding: 20px; background: #e9ecef; border-radius: 5px;">
            <h3>💡 PHP Code Explanation:</h3>
            <p><strong>Price Filtering:</strong> Uses array_filter() with a callback function to check if product price falls within the specified range.</p>
            <p><strong>Name Search:</strong> Uses stripos() function for case-insensitive search within product names.</p>
            <p><strong>Category Filter:</strong> Simple string comparison to match the exact category.</p>
            <p><strong>Multiple Filters:</strong> Filters are applied sequentially, each narrowing down the results further.</p>
        </div>
    </div>
</body>

</html>