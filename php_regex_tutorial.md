# PHP Regular Expressions Tutorial for Beginners

## What Are Regular Expressions?

Regular expressions (regex) are powerful patterns used to match and manipulate text. Think of them as a sophisticated "find and replace" tool that can search for complex patterns in strings. In PHP, regex is essential for validating user input, parsing data, and text processing.

Imagine you want to find all email addresses in a document, or check if a password meets specific criteria. Regular expressions make these tasks simple and efficient.

## Why Use Regex in PHP?

Regular expressions are particularly useful for:
- **Data validation**: Checking if user input matches expected formats
- **Text extraction**: Finding specific patterns in large texts
- **String manipulation**: Replacing or modifying text based on patterns
- **Parsing**: Breaking down structured data into components

## Basic Regex Syntax

Before diving into PHP functions, let's understand the basic building blocks:

### Literal Characters
The simplest regex pattern matches exact characters:
```php
$pattern = '/hello/';
$text = "hello world";
// This matches the word "hello" exactly
```

### Common Metacharacters
- `.` - Matches any single character (except newline)
- `*` - Matches zero or more of the preceding character
- `+` - Matches one or more of the preceding character
- `?` - Matches zero or one of the preceding character
- `^` - Matches the beginning of a string
- `$` - Matches the end of a string

### Character Classes
- `[abc]` - Matches any of the characters a, b, or c
- `[a-z]` - Matches any lowercase letter
- `[0-9]` - Matches any digit
- `\d` - Shorthand for `[0-9]`
- `\w` - Matches word characters (letters, digits, underscore)
- `\s` - Matches whitespace characters

## PHP Regex Functions

PHP provides several built-in functions for working with regular expressions. All modern PHP regex functions use PCRE (Perl Compatible Regular Expressions).

### 1. preg_match() - Find First Match

This function searches for the first occurrence of a pattern in a string. It returns 1 if found, 0 if not found.

```php
<?php
$pattern = '/\d+/';  // Matches one or more digits
$text = "I have 25 apples and 10 oranges";

if (preg_match($pattern, $text, $matches)) {
    echo "Found: " . $matches[0]; // Output: Found: 25
} else {
    echo "No match found";
}
?>
```

**How it works**: The function stops after finding the first match. The `$matches` array contains the matched text, with `$matches[0]` being the full match.

### 2. preg_match_all() - Find All Matches

When you need to find all occurrences of a pattern, use `preg_match_all()`:

```php
<?php
$pattern = '/\d+/';
$text = "I have 25 apples and 10 oranges";

$count = preg_match_all($pattern, $text, $matches);
echo "Found $count matches: ";
print_r($matches[0]); // Array: [25, 10]
?>
```

**The difference**: While `preg_match()` stops at the first match, `preg_match_all()` continues searching through the entire string.

### 3. preg_replace() - Replace Patterns

This function replaces text that matches a pattern with new text:

```php
<?php
$pattern = '/\d+/';
$replacement = 'X';
$text = "I have 25 apples and 10 oranges";

$result = preg_replace($pattern, $replacement, $text);
echo $result; // Output: I have X apples and X oranges
?>
```

**Practical use**: This is excellent for cleaning data, censoring content, or formatting text.

### 4. preg_split() - Split Strings by Pattern

Instead of splitting by a fixed delimiter, you can split by a pattern:

```php
<?php
$pattern = '/\s+/';  // Split by one or more whitespace characters
$text = "apple,  banana;   cherry";

$fruits = preg_split($pattern, $text);
print_r($fruits);
// Array: [apple,, banana;, cherry]
?>
```

**Why it's useful**: Regular `explode()` can only split by exact strings, but `preg_split()` can handle variable spacing, different delimiters, and complex patterns.

## Pattern Delimiters and Modifiers

In PHP, regex patterns must be enclosed in delimiters. The most common delimiter is the forward slash `/`:

```php
$pattern = '/hello/';  // Basic pattern
$pattern = '/hello/i'; // Case-insensitive (i modifier)
$pattern = '/hello/m'; // Multiline mode (m modifier)
```

### Common Modifiers:
- `i` - Case-insensitive matching
- `m` - Multiline mode (^ and $ match line beginnings/ends)
- `s` - Dot matches newlines too
- `x` - Ignore whitespace in pattern (for readability)

## Practical Examples

### Email Validation
```php
<?php
function validateEmail($email) {
    $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    return preg_match($pattern, $email);
}

$email = "user@example.com";
if (validateEmail($email)) {
    echo "Valid email";
} else {
    echo "Invalid email";
}
?>
```

**Pattern breakdown**:
- `^` - Start of string
- `[a-zA-Z0-9._%+-]+` - One or more valid email characters
- `@` - Literal @ symbol
- `[a-zA-Z0-9.-]+` - Domain name characters
- `\.` - Literal dot (escaped)
- `[a-zA-Z]{2,}` - At least 2 letters for TLD
- `$` - End of string

### Phone Number Formatting
```php
<?php
$phone = "1234567890";
$pattern = '/(\d{3})(\d{3})(\d{4})/';
$replacement = '($1) $2-$3';

$formatted = preg_replace($pattern, $replacement, $phone);
echo $formatted; // Output: (123) 456-7890
?>
```

**Capturing groups**: Parentheses `()` create groups that can be referenced in the replacement as `$1`, `$2`, etc.

### Extract URLs from Text
```php
<?php
$text = "Visit https://example.com or http://test.org for more info";
$pattern = '/https?:\/\/[^\s]+/';

preg_match_all($pattern, $text, $matches);
print_r($matches[0]);
// Array: [https://example.com, http://test.org]
?>
```

## Common Pitfalls and Best Practices

### 1. Escaping Special Characters
When matching literal special characters, escape them with backslashes:

```php
$pattern = '/\$\d+\.\d{2}/';  // Matches $25.99
$text = "The price is $25.99";
```

### 2. Greedy vs Non-Greedy Matching
By default, regex is greedy (matches as much as possible):

```php
$text = '<div>content</div><div>more</div>';
$greedy = '/<div>.*<\/div>/';     // Matches entire string
$nongreedy = '/<div>.*?<\/div>/'; // Matches first div only
```

### 3. Performance Considerations
Complex patterns can be slow. For simple string operations, consider alternatives:

```php
// For simple substring checks, use strpos()
if (strpos($text, 'hello') !== false) {
    // Faster than regex for simple cases
}
```

### 4. Testing and Debugging
Always test your patterns with various inputs:

```php
<?php
function testPattern($pattern, $testCases) {
    foreach ($testCases as $test) {
        $result = preg_match($pattern, $test) ? 'Match' : 'No match';
        echo "$test: $result\n";
    }
}

$emailPattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
$testEmails = ['user@example.com', 'invalid-email', 'test@domain.co.uk'];

testPattern($emailPattern, $testEmails);
?>
```

## Error Handling

Always check for regex errors, especially with user-provided patterns:

```php
<?php
$pattern = '/invalid[pattern/';  // Missing closing bracket
$text = "test string";

$result = preg_match($pattern, $text);

if ($result === false) {
    echo "Regex error: " . preg_last_error();
} else {
    echo "Pattern is valid";
}
?>
```

## Summary

Regular expressions in PHP are powerful tools for text processing. The key functions are:
- `preg_match()` for finding single matches
- `preg_match_all()` for finding all matches
- `preg_replace()` for replacing patterns
- `preg_split()` for splitting strings

Remember to start with simple patterns and gradually build complexity. Always test your patterns thoroughly and consider performance for complex operations. With practice, regex becomes an invaluable tool for data validation, text processing, and string manipulation in PHP applications.