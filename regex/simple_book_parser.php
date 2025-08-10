<?php

/**
 * Simple Book Parser - Beginner-Friendly Regex Example
 * 
 * This shows basic regex usage for extracting book information from XML
 * Focus: Understanding how regex works in practice, step by step
 */

// Sample XML with just one book (simple and clear)
$bookXML = '
<book id="123">
    <title>Harry Potter</title>
    <author>J.K. Rowling</author>
    <price>19.99</price>
    <pages>352</pages>
    <genre>Fantasy</genre>
</book>';

echo "=== Simple Book Parser Demo ===\n\n";
echo "XML to parse:\n" . trim($bookXML) . "\n\n";

// Step 1: Extract the book title
echo "Step 1: Extract Title\n";
$titlePattern = '/<title>(.*?)<\/title>/';
//               ^^^^^^^^^^^^^^^^^^^
// Explanation: <title> + capture content + </title>
// (.*?) = capture any character, non-greedy

if (preg_match($titlePattern, $bookXML, $matches)) {
    $title = $matches[1];  // $matches[0] = full match, $matches[1] = captured group
    echo "Pattern: $titlePattern\n";
    echo "Found title: '$title'\n\n";
} else {
    echo "No title found!\n\n";
}

// Step 2: Extract the author
echo "Step 2: Extract Author\n";
$authorPattern = '/<author>(.*?)<\/author>/';

if (preg_match($authorPattern, $bookXML, $matches)) {
    $author = $matches[1];
    echo "Pattern: $authorPattern\n";
    echo "Found author: '$author'\n\n";
}

// Step 3: Extract the price (just the number)
echo "Step 3: Extract Price\n";
$pricePattern = '/<price>(\d+\.?\d*)<\/price>/';
//               ^^^^^^^^^^^^^^^^^^^^^^^
// Explanation: <price> + capture digits with optional decimal + </price>
// \d+ = one or more digits
// \.? = optional decimal point (escaped because . is special)
// \d* = zero or more digits after decimal

if (preg_match($pricePattern, $bookXML, $matches)) {
    $price = $matches[1];
    echo "Pattern: $pricePattern\n";
    echo "Found price: $$price\n\n";
}

// Step 4: Extract the book ID from attributes
echo "Step 4: Extract Book ID from Attributes\n";
$idPattern = '/id="(\d+)"/';
//            ^^^^^^^^^^^
// Explanation: id=" + capture digits + "
// \d+ = one or more digits

if (preg_match($idPattern, $bookXML, $matches)) {
    $bookId = $matches[1];
    echo "Pattern: $idPattern\n";
    echo "Found book ID: $bookId\n\n";
}

// Step 5: Put it all together in a simple function
echo "Step 5: Complete Parser Function\n";

function parseBook($xml)
{
    $book = [];

    // Extract each field using regex
    if (preg_match('/<title>(.*?)<\/title>/', $xml, $matches)) {
        $book['title'] = $matches[1];
    }

    if (preg_match('/<author>(.*?)<\/author>/', $xml, $matches)) {
        $book['author'] = $matches[1];
    }

    if (preg_match('/<price>(\d+\.?\d*)<\/price>/', $xml, $matches)) {
        $book['price'] = (float)$matches[1];  // Convert to number
    }

    if (preg_match('/<pages>(\d+)<\/pages>/', $xml, $matches)) {
        $book['pages'] = (int)$matches[1];    // Convert to integer
    }

    if (preg_match('/<genre>(.*?)<\/genre>/', $xml, $matches)) {
        $book['genre'] = $matches[1];
    }

    if (preg_match('/id="(\d+)"/', $xml, $matches)) {
        $book['id'] = $matches[1];
    }

    return $book;
}

// Use the parser
$parsedBook = parseBook($bookXML);

echo "Complete parsed book:\n";
print_r($parsedBook);

// Step 6: Let's try with multiple books
echo "\n=== Parsing Multiple Books ===\n";

$multipleBooksXML = '
<books>
    <book id="1">
        <title>Harry Potter</title>
        <author>J.K. Rowling</author>
        <price>19.99</price>
    </book>
    <book id="2">
        <title>The Hobbit</title>
        <author>J.R.R. Tolkien</author>
        <price>15.50</price>
    </book>
</books>';

echo "XML with multiple books:\n" . trim($multipleBooksXML) . "\n\n";

// Extract all book blocks first
$bookBlockPattern = '/<book id="(\d+)">(.*?)<\/book>/s';
//                   ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
// Explanation: 
// <book id=" + capture ID + "> + capture book content + </book>
// The 's' modifier lets . match newlines too

echo "Step 6: Extract All Book Blocks\n";
echo "Pattern: $bookBlockPattern\n";

if (preg_match_all($bookBlockPattern, $multipleBooksXML, $matches, PREG_SET_ORDER)) {
    echo "Found " . count($matches) . " books:\n\n";

    foreach ($matches as $bookMatch) {
        $bookId = $bookMatch[1];      // First captured group (ID)
        $bookContent = $bookMatch[2]; // Second captured group (content)

        echo "Book ID: $bookId\n";
        echo "Content: " . trim($bookContent) . "\n";

        // Now parse this individual book content
        $book = parseBook($bookContent);
        $book['id'] = $bookId;  // Add the ID we captured

        echo "Parsed: " . $book['title'] . " by " . $book['author'] . " - $" . $book['price'] . "\n\n";
    }
}

// Step 7: Simple validation using regex
echo "=== Simple Validation ===\n";

function validateBookData($book)
{
    $errors = [];

    // Check if title exists and is not empty
    if (empty($book['title'])) {
        $errors[] = "Title is missing";
    }

    // Check if price is a valid number
    if (!isset($book['price']) || !is_numeric($book['price'])) {
        $errors[] = "Price must be a number";
    }

    // Check if ID is numeric
    if (!isset($book['id']) || !preg_match('/^\d+$/', $book['id'])) {
        $errors[] = "ID must be a number";
    }

    return $errors;
}

// Test validation
$testBook = ['title' => 'Test Book', 'author' => 'Test Author', 'price' => 'not-a-number', 'id' => 'abc'];
$errors = validateBookData($testBook);

if (empty($errors)) {
    echo "Book data is valid!\n";
} else {
    echo "Validation errors:\n";
    foreach ($errors as $error) {
        echo "- $error\n";
    }
}

echo "\n=== Key Takeaways ===\n";
echo "1. Use (.*?) to capture content between tags\n";
echo "2. Use \\d+ for numbers, \\d* for optional numbers\n";
echo "3. Escape special characters like . with \\\n";
echo "4. The 's' modifier lets . match newlines\n";
echo "5. preg_match() gets first match, preg_match_all() gets all matches\n";
echo "6. Always check if preg_match() found something before using \$matches\n";
echo "7. Convert captured strings to numbers when needed\n";
