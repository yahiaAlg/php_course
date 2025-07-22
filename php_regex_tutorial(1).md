# PHP Regular Expressions (REGEX) - Complete Beginner's Tutorial

## Introduction: What Are Regular Expressions?

Imagine you're looking through a massive phone book trying to find all phone numbers that start with "555". You could flip through every page manually, or you could use a pattern-matching tool that automatically finds what you're looking for. Regular expressions (regex) are exactly that tool for text processing.

Regular expressions are powerful patterns that help you search, match, and manipulate text. Think of them as a sophisticated "find and replace" system that can understand complex patterns rather than just exact text matches. In PHP, regex becomes an invaluable tool for validating email addresses, extracting data from strings, cleaning user input, and much more.

## Understanding Pattern Matching Basics

Before we dive into PHP's regex functions, let's understand how pattern matching works conceptually. When you use regex, you're essentially telling PHP: "Look for text that follows this specific pattern." The pattern is written in a special syntax that can describe everything from simple text matches to complex rules.

Consider this analogy: if you were teaching someone to identify valid email addresses, you might say "look for some characters, then an @ symbol, then more characters, then a dot, then a few more characters." Regular expressions let you express these rules in a precise, computer-readable format.

## PHP's Core Regex Functions

PHP provides several functions for working with regular expressions, but we'll focus on the most essential ones that form the foundation of regex work.

### The preg_match() Function

The `preg_match()` function is your first step into regex. It searches for a pattern in a string and returns 1 if found, 0 if not found, or false if an error occurs.

```php
<?php
$text = "Hello, my email is john@example.com";
$pattern = "/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/";

if (preg_match($pattern, $text)) {
    echo "Email found!";
} else {
    echo "No email found.";
}
?>
```

Notice how the pattern is wrapped in forward slashes (`/`). This is called a delimiter in PHP regex. The pattern itself describes what an email roughly looks like: characters, then @, then more characters, then a dot, then letters.

Let's break down what's happening step by step. The function examines each character in the text, trying to match the pattern. When it encounters "john@example.com", it recognizes this matches our email pattern and returns 1 (true).

### Capturing Matched Content

The real power of `preg_match()` emerges when you want to extract the matched content, not just know if it exists:

```php
<?php
$text = "Call me at 555-123-4567 tomorrow";
$pattern = "/(\d{3})-(\d{3})-(\d{4})/";

if (preg_match($pattern, $text, $matches)) {
    echo "Full match: " . $matches[0] . "\n";     // 555-123-4567
    echo "Area code: " . $matches[1] . "\n";     // 555
    echo "Exchange: " . $matches[2] . "\n";      // 123
    echo "Number: " . $matches[3] . "\n";        // 4567
}
?>
```

The parentheses in the pattern create "capture groups." Think of them as containers that hold specific parts of the match. The `$matches` array stores these captured pieces, with `$matches[0]` containing the entire match and subsequent indices containing each captured group.

### Finding All Matches with preg_match_all()

Sometimes you need to find every occurrence of a pattern, not just the first one. This is where `preg_match_all()` becomes invaluable:

```php
<?php
$text = "Prices: $19.99, $45.50, $123.45";
$pattern = "/\$(\d+\.\d{2})/";

preg_match_all($pattern, $text, $matches);

echo "All prices found:\n";
foreach ($matches[1] as $price) {
    echo "- $" . $price . "\n";
}
// Output:
// - $19.99
// - $45.50
// - $123.45
?>
```

The function finds every price in the text and stores them in the `$matches` array. Notice how `$matches[1]` contains just the captured numbers without the dollar sign, because our parentheses captured only the numeric part.

### Text Replacement with preg_replace()

Beyond finding text, you often need to modify it. The `preg_replace()` function finds patterns and replaces them with new content:

```php
<?php
$text = "Please call 555-123-4567 or 555-987-6543";
$pattern = "/(\d{3})-(\d{3})-(\d{4})/";
$replacement = "($1) $2-$3";

$result = preg_replace($pattern, $replacement, $text);
echo $result;
// Output: Please call (555) 123-4567 or (555) 987-6543
?>
```

The replacement string uses `$1`, `$2`, and `$3` to refer to the captured groups from the pattern. This lets you rearrange or reformat the matched content rather than simply replacing it with static text.

## Essential Pattern Components

Understanding regex patterns is like learning a new language. Let's explore the fundamental building blocks that make patterns work.

### Character Classes

Character classes let you specify which characters are acceptable at a particular position. Square brackets create a character class:

```php
<?php
$text = "The code is A1B2C3";
$pattern = "/[A-Z]/";  // Matches any uppercase letter

if (preg_match($pattern, $text)) {
    echo "Found uppercase letter";
}

// More specific example
$pattern = "/[0-9]/";  // Matches any digit
$pattern = "/[a-z]/";  // Matches any lowercase letter
$pattern = "/[aeiou]/"; // Matches any vowel
?>
```

Character classes are incredibly flexible. You can combine ranges and specific characters: `[a-zA-Z0-9]` matches any letter or digit. You can also negate a character class with `^`: `[^0-9]` matches anything except digits.

### Quantifiers: Controlling Repetition

Quantifiers specify how many times a pattern element should appear:

```php
<?php
$text = "Colors: red, blue, green";
$pattern = "/[a-z]+/";  // + means "one or more"

preg_match_all($pattern, $text, $matches);
print_r($matches[0]);
// Output: Array([0] => red, [1] => blue, [2] => green)

// Different quantifiers
$pattern = "/[a-z]*/";  // * means "zero or more"
$pattern = "/[a-z]?/";  // ? means "zero or one"
$pattern = "/[a-z]{3}/"; // {3} means "exactly 3"
$pattern = "/[a-z]{2,5}/"; // {2,5} means "between 2 and 5"
?>
```

Think of quantifiers as instructions about repetition. The `+` quantifier tells PHP "match the previous element one or more times," while `*` says "match zero or more times." This difference is crucial: `+` requires at least one match, while `*` can match nothing.

### Anchors: Positioning Your Matches

Anchors don't match characters; they match positions within the string:

```php
<?php
$text = "The quick brown fox";

// ^ matches the start of the string
$pattern = "/^The/";
if (preg_match($pattern, $text)) {
    echo "Text starts with 'The'";
}

// $ matches the end of the string
$pattern = "/fox$/";
if (preg_match($pattern, $text)) {
    echo "Text ends with 'fox'";
}

// Word boundaries
$pattern = "/\bquick\b/";  // Matches "quick" as a whole word
?>
```

Anchors are essential for precise matching. Without them, a pattern like `/cat/` would match "cat" anywhere in the string, including inside words like "category" or "scattered." The word boundary anchor `\b` ensures you match complete words only.

## Practical Applications and Examples

Let's explore how these concepts work together in real-world scenarios.

### Email Validation

Building a robust email validator helps demonstrate how regex components work together:

```php
<?php
function validateEmail($email) {
    // Break down the pattern for clarity
    $pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    
    return preg_match($pattern, $email);
}

// Test the function
$emails = ["user@example.com", "invalid.email", "test@domain.co.uk"];

foreach ($emails as $email) {
    if (validateEmail($email)) {
        echo "$email is valid\n";
    } else {
        echo "$email is invalid\n";
    }
}
?>
```

This pattern works by defining three main sections: the username (before @), the domain name (after @ but before the final dot), and the top-level domain (after the final dot). Each section has specific rules about which characters are allowed.

### Data Extraction from Text

Regular expressions excel at extracting structured data from unstructured text:

```php
<?php
$logEntry = "2024-01-15 10:30:45 ERROR User login failed for user123";
$pattern = "/(\d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2}) (\w+) (.+)/";

if (preg_match($pattern, $logEntry, $matches)) {
    echo "Date: " . $matches[1] . "\n";
    echo "Time: " . $matches[2] . "\n";
    echo "Level: " . $matches[3] . "\n";
    echo "Message: " . $matches[4] . "\n";
}
?>
```

This example demonstrates how regex can parse structured text formats. Each capture group extracts a specific piece of information: date, time, log level, and message. This approach is invaluable for processing log files, parsing configuration files, or extracting data from formatted text.

### Phone Number Formatting

Here's a practical example of cleaning and formatting phone numbers:

```php
<?php
function formatPhoneNumber($phone) {
    // Remove all non-digit characters
    $cleaned = preg_replace("/[^0-9]/", "", $phone);
    
    // Check if it's a valid US phone number (10 digits)
    if (preg_match("/^(\d{3})(\d{3})(\d{4})$/", $cleaned, $matches)) {
        return "(" . $matches[1] . ") " . $matches[2] . "-" . $matches[3];
    }
    
    return "Invalid phone number";
}

// Test with various formats
$phones = ["5551234567", "555-123-4567", "(555) 123-4567", "555.123.4567"];

foreach ($phones as $phone) {
    echo "$phone -> " . formatPhoneNumber($phone) . "\n";
}
?>
```

This function demonstrates a two-step process: first, it strips away all formatting to get just the digits, then it applies a standard format. This approach handles various input formats while producing consistent output.

## Common Pitfalls and How to Avoid Them

Regular expressions can be tricky. Understanding common mistakes helps you write more reliable code.

### Greedy vs. Non-Greedy Matching

By default, quantifiers are "greedy" – they match as much as possible:

```php
<?php
$html = "<p>First paragraph</p><p>Second paragraph</p>";
$pattern = "/<p>.*<\/p>/";  // Greedy matching

preg_match($pattern, $html, $matches);
echo $matches[0];
// Output: <p>First paragraph</p><p>Second paragraph</p>

// Non-greedy matching
$pattern = "/<p>.*?<\/p>/";  // Adding ? makes it non-greedy
preg_match($pattern, $html, $matches);
echo $matches[0];
// Output: <p>First paragraph</p>
?>
```

The greedy quantifier matches from the first `<p>` to the last `</p>`, while the non-greedy version stops at the first `</p>` it encounters. Understanding this difference is crucial for parsing HTML or any nested structures.

### Escaping Special Characters

Regular expressions use many characters with special meanings. When you want to match these characters literally, you must escape them:

```php
<?php
$text = "Price: $19.99 (plus tax)";
$pattern = "/\$\d+\.\d{2}/";  // Escaping $ and .

if (preg_match($pattern, $text, $matches)) {
    echo "Found price: " . $matches[0];
}
// Output: Found price: $19.99
?>
```

The backslash tells PHP to treat the following character literally rather than as a regex special character. This is essential when matching currency symbols, periods, parentheses, or any other character that has special meaning in regex.

## Advanced Techniques

As you become more comfortable with basic regex, these advanced techniques will expand your capabilities.

### Using Flags for Enhanced Matching

PHP regex supports flags that modify how patterns work:

```php
<?php
$text = "Hello\nWorld\nPHP";
$pattern = "/^php$/i";  // i flag for case-insensitive matching

if (preg_match($pattern, $text)) {
    echo "Found PHP (case-insensitive)";
}

// Multiline flag
$pattern = "/^php$/im";  // m flag for multiline mode
if (preg_match($pattern, $text)) {
    echo "Found PHP on its own line";
}
?>
```

The `i` flag makes matching case-insensitive, while the `m` flag changes how `^` and `$` work, making them match the start and end of lines rather than just the start and end of the entire string.

### Conditional Replacement

Sometimes you need to make replacements based on complex conditions:

```php
<?php
function formatCurrency($text) {
    $pattern = "/(\d+)\.(\d{1})(?!\d)/";  // Matches numbers with one decimal place
    $replacement = "$1.$2" . "0";        // Adds trailing zero
    
    return preg_replace($pattern, $replacement, $text);
}

$text = "Prices: $19.5, $45.25, $123.9";
echo formatCurrency($text);
// Output: Prices: $19.50, $45.25, $123.90
?>
```

This example uses a negative lookahead `(?!\d)` to ensure we only match numbers that don't already have two decimal places. This prevents incorrectly modifying properly formatted numbers.

## Performance Considerations and Best Practices

Regular expressions can be computationally expensive. Here are strategies for writing efficient regex code:

### Optimize Your Patterns

```php
<?php
// Less efficient: backtracking-heavy pattern
$pattern = "/.*@.*\..*/";

// More efficient: specific character classes
$pattern = "/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/";
?>
```

The first pattern uses `.*` which can cause excessive backtracking. The second pattern is more specific about what characters are allowed, making it faster and more accurate.

### Use Appropriate Functions

Choose the right function for your needs:

```php
<?php
// For simple substring searches, sometimes strpos() is faster
if (strpos($text, "@") !== false) {
    // Simple check before expensive regex
    if (preg_match($emailPattern, $text)) {
        // Process email
    }
}
?>
```

For simple checks, PHP's string functions can be faster than regex. Use regex when you need pattern matching, but consider simpler alternatives for basic operations.

## Conclusion

Regular expressions in PHP provide powerful tools for text processing, from simple pattern matching to complex data extraction and manipulation. The key to mastering regex is understanding that you're describing patterns rather than exact matches.

Start with simple patterns and gradually build complexity. Practice with real-world examples like email validation, phone number formatting, and data extraction. Remember that regex is a tool – use it when pattern matching is needed, but don't overlook simpler string functions for basic operations.

As you continue working with regex, you'll develop an intuition for when and how to use these patterns effectively. The investment in learning regex pays dividends in any text-processing task you encounter in PHP development.