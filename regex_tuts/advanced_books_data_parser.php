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
    <author_email>jk.rowling@wizardmail.com</author_email>
    <author_phone>(555) 123-4567</author_phone>
    <publisher>Scholastic Books</publisher>
    <publisher_website>https://www.scholastic.com</publisher_website>
    <publisher_email>info@scholastic.com</publisher_email>
    <publisher_phone>555-987-6543</publisher_phone>
    <isbn>978-0-439-70818-8</isbn>
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

// Step 5: Extract email addresses
echo "Step 5: Extract Email Addresses\n";
$emailPattern = '/<(\w+_email)>([\w._%+-]+@[\w.-]+\.[a-zA-Z]{2,})<\/\1>/';
//               ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
// Explanation: 
// <(\w+_email)> = capture tag name ending with "_email"
// [\w._%+-]+ = username part (letters, numbers, dots, underscores, %, +, -)
// @ = literal @ symbol
// [\w.-]+ = domain name part
// \. = literal dot (escaped)
// [a-zA-Z]{2,} = domain extension (2 or more letters)
// <\/\1> = closing tag that matches the opening tag

if (preg_match_all($emailPattern, $bookXML, $matches, PREG_SET_ORDER)) {
    echo "Pattern: $emailPattern\n";
    foreach ($matches as $match) {
        $fieldName = $match[1];  // e.g., "author_email"
        $email = $match[2];      // e.g., "jk.rowling@wizardmail.com"
        echo "Found $fieldName: $email\n";
    }
    echo "\n";
}

// Step 6: Extract phone numbers
echo "Step 6: Extract Phone Numbers\n";
$phonePattern = '/<(\w+_phone)>(\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4})<\/\1>/';
//               ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
// Explanation:
// <(\w+_phone)> = capture tag name ending with "_phone"
// \(? = optional opening parenthesis (escaped)
// \d{3} = exactly 3 digits (area code)
// \)? = optional closing parenthesis (escaped)
// [-.\s]? = optional separator (dash, dot, or space)
// \d{3} = exactly 3 digits (exchange)
// [-.\s]? = optional separator
// \d{4} = exactly 4 digits (number)

if (preg_match_all($phonePattern, $bookXML, $matches, PREG_SET_ORDER)) {
    echo "Pattern: $phonePattern\n";
    foreach ($matches as $match) {
        $fieldName = $match[1];  // e.g., "author_phone"
        $phone = $match[2];      // e.g., "(555) 123-4567"
        echo "Found $fieldName: $phone\n";
    }
    echo "\n";
}

// Step 7: Extract URLs
echo "Step 7: Extract URLs\n";
$urlPattern = '/<(\w+_website)>(https?:\/\/[\w.-]+\.[a-zA-Z]{2,}[\/\w._-]*)<\/\1>/';
//             ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
// Explanation:
// <(\w+_website)> = capture tag name ending with "_website"
// https? = "http" optionally followed by "s"
// :\/\/ = literal "://" (slashes escaped)
// [\w.-]+ = domain characters (letters, numbers, dots, dashes)
// \. = literal dot
// [a-zA-Z]{2,} = domain extension (2+ letters)
// [\/\w._-]* = optional path (slashes, letters, numbers, dots, underscores, dashes)

if (preg_match($urlPattern, $bookXML, $matches)) {
    echo "Pattern: $urlPattern\n";
    $fieldName = $matches[1];
    $url = $matches[2];
    echo "Found $fieldName: $url\n\n";
}

// Step 8: Extract ISBN
echo "Step 8: Extract ISBN\n";
$isbnPattern = '/<isbn>(\d{3}-\d{1}-\d{3}-\d{5}-\d{1})<\/isbn>/';
//              ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
// Explanation:
// <isbn> = opening ISBN tag
// \d{3} = exactly 3 digits
// - = literal dash
// \d{1} = exactly 1 digit
// - = literal dash
// \d{3} = exactly 3 digits
// - = literal dash
// \d{5} = exactly 5 digits
// - = literal dash
// \d{1} = exactly 1 digit (check digit)

if (preg_match($isbnPattern, $bookXML, $matches)) {
    echo "Pattern: $isbnPattern\n";
    $isbn = $matches[1];
    echo "Found ISBN: $isbn\n\n";
}
// Step 9: Extract the book ID from attributes
echo "Step 9: Extract Book ID from Attributes\n";
$idPattern = '/id="(\d+)"/';
//            ^^^^^^^^^^^
// Explanation: id=" + capture digits + "
// \d+ = one or more digits

if (preg_match($idPattern, $bookXML, $matches)) {
    $bookId = $matches[1];
    echo "Pattern: $idPattern\n";
    echo "Found book ID: $bookId\n\n";
}

// Step 10: Put it all together in a simple function
echo "Step 10: Complete Parser Function\n";

function parseBook($xml)
{
    $book = [];

    // Extract basic fields
    if (preg_match('/<title>(.*?)<\/title>/', $xml, $matches)) {
        $book['title'] = $matches[1];
    }

    if (preg_match('/<author>(.*?)<\/author>/', $xml, $matches)) {
        $book['author'] = $matches[1];
    }

    if (preg_match('/<price>(\d+\.?\d*)<\/price>/', $xml, $matches)) {
        $book['price'] = (float)$matches[1];
    }

    if (preg_match('/<pages>(\d+)<\/pages>/', $xml, $matches)) {
        $book['pages'] = (int)$matches[1];
    }

    if (preg_match('/<genre>(.*?)<\/genre>/', $xml, $matches)) {
        $book['genre'] = $matches[1];
    }

    if (preg_match('/id="(\d+)"/', $xml, $matches)) {
        $book['id'] = $matches[1];
    }

    // Extract contact information
    if (preg_match('/<author_email>([\w._%+-]+@[\w.-]+\.[a-zA-Z]{2,})<\/author_email>/', $xml, $matches)) {
        $book['author_email'] = $matches[1];
    }

    if (preg_match('/<author_phone>(\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4})<\/author_phone>/', $xml, $matches)) {
        $book['author_phone'] = $matches[1];
    }

    if (preg_match('/<publisher>(.*?)<\/publisher>/', $xml, $matches)) {
        $book['publisher'] = $matches[1];
    }

    if (preg_match('/<publisher_website>(https?:\/\/[\w.-]+\.[a-zA-Z]{2,}[\/\w._-]*)<\/publisher_website>/', $xml, $matches)) {
        $book['publisher_website'] = $matches[1];
    }

    if (preg_match('/<publisher_email>([\w._%+-]+@[\w.-]+\.[a-zA-Z]{2,})<\/publisher_email>/', $xml, $matches)) {
        $book['publisher_email'] = $matches[1];
    }

    if (preg_match('/<publisher_phone>(\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4})<\/publisher_phone>/', $xml, $matches)) {
        $book['publisher_phone'] = $matches[1];
    }

    if (preg_match('/<isbn>(\d{3}-\d{1}-\d{3}-\d{5}-\d{1})<\/isbn>/', $xml, $matches)) {
        $book['isbn'] = $matches[1];
    }

    return $book;
}

// Use the parser
$parsedBook = parseBook($bookXML);

echo "Complete parsed book:\n";
print_r($parsedBook);

// Step 11: Let's try with multiple books
echo "\n=== Parsing Multiple Books ===\n";

$multipleBooksXML = '
<books>
    <book id="1">
        <title>Harry Potter</title>
        <author>J.K. Rowling</author>
        <author_email>jk.rowling@wizardmail.com</author_email>
        <publisher_website>https://www.scholastic.com</publisher_website>
        <isbn>978-0-439-70818-8</isbn>
        <price>19.99</price>
    </book>
    <book id="2">
        <title>The Hobbit</title>
        <author>J.R.R. Tolkien</author>
        <author_email>tolkien@middleearth.org</author_email>
        <publisher_phone>555-999-8888</publisher_phone>
        <isbn>978-0-547-92822-7</isbn>
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

echo "Step 12: Extract All Book Blocks\n";
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

        // Display all extracted information
        echo "Parsed: " . $book['title'] . " by " . $book['author'] . " - $" . $book['price'];
        if (isset($book['author_email'])) echo " | Author: " . $book['author_email'];
        if (isset($book['publisher_website'])) echo " | Website: " . $book['publisher_website'];
        if (isset($book['publisher_phone'])) echo " | Phone: " . $book['publisher_phone'];
        if (isset($book['isbn'])) echo " | ISBN: " . $book['isbn'];
        echo "\n\n";
    }
}

// Step 13: Advanced validation using regex
echo "=== Advanced Validation with Regex ===\n";

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

    // Validate email format if present
    if (isset($book['author_email']) && !preg_match('/^[\w._%+-]+@[\w.-]+\.[a-zA-Z]{2,}$/', $book['author_email'])) {
        $errors[] = "Author email format is invalid";
    }

    if (isset($book['publisher_email']) && !preg_match('/^[\w._%+-]+@[\w.-]+\.[a-zA-Z]{2,}$/', $book['publisher_email'])) {
        $errors[] = "Publisher email format is invalid";
    }

    // Validate phone format if present
    if (isset($book['author_phone']) && !preg_match('/^\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}$/', $book['author_phone'])) {
        $errors[] = "Author phone format is invalid";
    }

    if (isset($book['publisher_phone']) && !preg_match('/^\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}$/', $book['publisher_phone'])) {
        $errors[] = "Publisher phone format is invalid";
    }

    // Validate URL format if present
    if (isset($book['publisher_website']) && !preg_match('/^https?:\/\/[\w.-]+\.[a-zA-Z]{2,}[\/\w._-]*$/', $book['publisher_website'])) {
        $errors[] = "Publisher website URL format is invalid";
    }

    // Validate ISBN format if present (ISBN-13 format)
    if (isset($book['isbn']) && !preg_match('/^\d{3}-\d{1}-\d{3}-\d{5}-\d{1}$/', $book['isbn'])) {
        $errors[] = "ISBN format is invalid (should be XXX-X-XXX-XXXXX-X)";
    }

    return $errors;
}

// Test validation with good data
echo "Testing with VALID data:\n";
$validBook = [
    'title' => 'Test Book',
    'author' => 'Test Author',
    'price' => 19.99,
    'id' => '123',
    'author_email' => 'author@example.com',
    'publisher_phone' => '555-123-4567',
    'publisher_website' => 'https://www.publisher.com',
    'isbn' => '978-0-123-45678-9'
];

$errors = validateBookData($validBook);
if (empty($errors)) {
    echo "✓ All data is valid!\n\n";
} else {
    echo "✗ Validation errors found:\n";
    foreach ($errors as $error) {
        echo "- $error\n";
    }
    echo "\n";
}

// Test validation with bad data
echo "Testing with INVALID data:\n";
$invalidBook = [
    'title' => 'Test Book',
    'price' => 'not-a-number',
    'id' => 'abc',
    'author_email' => 'invalid-email',
    'publisher_phone' => '123',
    'publisher_website' => 'not-a-url',
    'isbn' => '123-wrong-format'
];

$errors = validateBookData($invalidBook);
if (empty($errors)) {
    echo "✓ All data is valid!\n\n";
} else {
    echo "✗ Validation errors found:\n";
    foreach ($errors as $error) {
        echo "- $error\n";
    }
    echo "\n";
}

// Step 14: Pattern extraction helper function
echo "=== Pattern Extraction Helper ===\n";

function extractPatternInfo($text, $patternName)
{
    $patterns = [
        'emails' => '/[\w._%+-]+@[\w.-]+\.[a-zA-Z]{2,}/',
        'phones' => '/\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}/',
        'urls' => '/https?:\/\/[\w.-]+\.[a-zA-Z]{2,}[\/\w._-]*/',
        'isbns' => '/\d{3}-\d{1}-\d{3}-\d{5}-\d{1}/',
        'prices' => '/\$?\d+\.?\d*/'
    ];

    if (!isset($patterns[$patternName])) {
        return "Unknown pattern type";
    }

    $pattern = $patterns[$patternName];

    if (preg_match_all($pattern, $text, $matches)) {
        return $matches[0];  // Return all matches
    }

    return [];
}

// Test the pattern extractor
$mixedText = "Contact us at info@publisher.com or call (555) 123-4567. 
Visit https://www.oursite.com for books like ISBN 978-0-123-45678-9 for $29.99 
or email support@help.org and check https://backup.site.net for deals at $15.50!";

echo "Text to analyze:\n$mixedText\n\n";

$patternTypes = ['emails', 'phones', 'urls', 'isbns', 'prices'];

foreach ($patternTypes as $type) {
    $found = extractPatternInfo($mixedText, $type);
    echo "Found " . count($found) . " $type:\n";
    foreach ($found as $item) {
        echo "  - $item\n";
    }
    echo "\n";
}

echo "\n=== Key Takeaways ===\n";
echo "1. Use (.*?) to capture content between tags\n";
echo "2. Use \\d+ for numbers, \\d* for optional numbers\n";
echo "3. Escape special characters like . with \\\n";
echo "4. Email pattern: [\\w._%+-]+@[\\w.-]+\\.[a-zA-Z]{2,}\n";
echo "5. Phone pattern: \\(?\\d{3}\\)?[-.\s]?\\d{3}[-.\s]?\\d{4}\n";
echo "6. URL pattern: https?:\\/\\/[\\w.-]+\\.[a-zA-Z]{2,}[\\/\\w._-]*\n";
echo "7. ISBN pattern: \\d{3}-\\d{1}-\\d{3}-\\d{5}-\\d{1}\n";
echo "8. The 's' modifier lets . match newlines\n";
echo "9. preg_match() gets first match, preg_match_all() gets all matches\n";
echo "10. Always validate extracted data with appropriate patterns\n";
echo "11. Use ^ and $ anchors for exact format validation\n";
echo "12. Build complex patterns step by step, test each part\n";
