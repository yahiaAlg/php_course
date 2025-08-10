# Complete Regex Tutorial: From Zero to Hero

## What is Regex?

Regular Expressions (regex) are patterns used to match character combinations in strings. Think of them as a powerful search language that can find, validate, and manipulate text based on patterns rather than exact matches.

**Real-world analogy**: Imagine you're looking for houses in a neighborhood. Instead of looking for "123 Main Street" exactly, you could describe a pattern: "any number, followed by 'Main Street'". Regex works similarly with text.

## Chapter 1: The Basics - Literal Characters

Let's start with the simplest regex patterns - literal characters.

```php
<?php
$text = "The quick brown fox jumps over the lazy dog";
$pattern = "/fox/";

if (preg_match($pattern, $text)) {
    echo "Found 'fox' in the text!";
}
?>
```

**Key concepts:**
- `/fox/` is a regex pattern that matches the exact word "fox"
- The forward slashes `/` are **delimiters** - they mark the beginning and end of the pattern
- `preg_match()` is PHP's function to test if a pattern matches

## Chapter 2: Delimiters - The Container for Your Pattern

Delimiters are characters that wrap your regex pattern. PHP requires them.

```php
// These are all valid delimiters:
$pattern1 = "/hello/";     // Forward slashes (most common)
$pattern2 = "#hello#";     // Hash symbols
$pattern3 = "~hello~";     // Tildes
$pattern4 = "|hello|";     // Pipes
```

**Why delimiters matter:**
- If your pattern contains the delimiter character, you need to escape it or use a different delimiter
- Example: To match `/home/user/`, use `#/home/user/#` instead of `//home/user//`

## Chapter 3: Case Sensitivity and Modifiers

By default, regex is case-sensitive. Modifiers change this behavior.

```php
$text = "Hello World";

// Case-sensitive (won't match)
$pattern1 = "/hello/";
preg_match($pattern1, $text); // Returns 0 (no match)

// Case-insensitive (will match)
$pattern2 = "/hello/i";
preg_match($pattern2, $text); // Returns 1 (match found)
```

**The 'i' modifier** makes the pattern case-insensitive. It goes after the closing delimiter.

## Chapter 4: Meta Characters - The Special Symbols

Meta characters have special meanings in regex. Here are the essential ones:

### The Dot (.) - Match Any Single Character

```php
$pattern = "/c.t/";
// Matches: "cat", "cot", "cut", "c9t", "c@t", etc.
// Does NOT match: "cart" (too many characters between c and t)
```

### The Caret (^) - Start of String

```php
$pattern = "/^Hello/";
// Matches: "Hello world" 
// Does NOT match: "Say Hello" (Hello is not at the start)
```

### The Dollar ($) - End of String

```php
$pattern = "/world$/";
// Matches: "Hello world"
// Does NOT match: "world peace" (world is not at the end)
```

### Combining Start and End

```php
$pattern = "/^Hello world$/";
// Matches ONLY: "Hello world" (exact match)
```

## Chapter 5: Quantifiers - How Many Times?

Quantifiers specify how many times a character or group should occur.

### The Asterisk (*) - Zero or More

```php
$pattern = "/colou*r/";
// Matches: "color" (0 u's), "colour" (1 u), "colouur" (2 u's), etc.
```

### The Plus (+) - One or More

```php
$pattern = "/colou+r/";
// Matches: "colour" (1 u), "colouur" (2 u's), etc.
// Does NOT match: "color" (needs at least 1 u)
```

### The Question Mark (?) - Zero or One

```php
$pattern = "/colou?r/";
// Matches: "color" (0 u's) OR "colour" (1 u)
// Does NOT match: "colouur" (more than 1 u)
```

### Curly Braces {} - Exact Quantities

```php
$pattern1 = "/\d{3}/";        // Exactly 3 digits
$pattern2 = "/\d{2,4}/";      // Between 2 and 4 digits
$pattern3 = "/\d{3,}/";       // 3 or more digits
```

## Chapter 6: Character Classes - Groups of Characters

Character classes let you match any character from a set.

### Square Brackets [] - Custom Character Classes

```php
$pattern = "/[aeiou]/";
// Matches any single vowel

$pattern2 = "/[0-9]/";
// Matches any single digit (same as \d)

$pattern3 = "/[a-zA-Z]/";
// Matches any single letter (upper or lowercase)
```

### Ranges and Negation

```php
$pattern1 = "/[a-z]/";        // Lowercase letters a through z
$pattern2 = "/[A-Z]/";        // Uppercase letters A through Z
$pattern3 = "/[0-9]/";        // Digits 0 through 9
$pattern4 = "/[^0-9]/";       // NOT digits (the ^ inside [] means "not")
```

### Predefined Character Classes

```php
$patterns = [
    "/\d/",     // Any digit (0-9)
    "/\D/",     // Any non-digit
    "/\w/",     // Any word character (letters, digits, underscore)
    "/\W/",     // Any non-word character
    "/\s/",     // Any whitespace (space, tab, newline)
    "/\S/"      // Any non-whitespace
];
```

## Chapter 7: Escaping - Making Special Characters Literal

To match a meta character literally, escape it with a backslash.

```php
// To match a literal dot
$pattern1 = "/\./";          // Matches only "."
$pattern2 = "/./";           // Matches any character

// To match a literal question mark
$pattern3 = "/\?/";          // Matches only "?"
$pattern4 = "/\$/";          // Matches only "$"

// Common example: matching file extensions
$pattern = "/\.txt$/";       // Files ending with .txt
```

## Chapter 8: Grouping and Capturing

Parentheses () group parts of your pattern and capture matched content.

### Basic Grouping

```php
$pattern = "/(cat|dog)/";
// Matches either "cat" or "dog"

$text = "I have a cat";
preg_match($pattern, $text, $matches);
// $matches[0] = "cat" (full match)
// $matches[1] = "cat" (first captured group)
```

### Multiple Groups

```php
$pattern = "/(\d{4})-(\d{2})-(\d{2})/";  // Date pattern
$text = "Today is 2024-03-15";

preg_match($pattern, $text, $matches);
// $matches[0] = "2024-03-15" (full match)
// $matches[1] = "2024" (year)
// $matches[2] = "03" (month)
// $matches[3] = "15" (day)
```

### Non-Capturing Groups

```php
$pattern = "/(?:cat|dog) owner/";
// Groups for alternation but doesn't capture the animal name
```

## Chapter 9: Greediness - How Much to Match?

By default, quantifiers are **greedy** - they match as much as possible.

### Greedy Matching (Default)

```php
$text = '<div>Hello</div><div>World</div>';
$pattern = "/<div>.*<\/div>/";

// Greedy match: "<div>Hello</div><div>World</div>" (entire string)
// It matches from first <div> to last </div>
```

### Non-Greedy (Lazy) Matching

Add `?` after a quantifier to make it non-greedy.

```php
$text = '<div>Hello</div><div>World</div>';
$pattern = "/<div>.*?<\/div>/";

// Non-greedy match: "<div>Hello</div>" (stops at first </div>)
```

### Understanding the Logic

- **Greedy**: "Match as much as possible while still allowing the overall pattern to match"
- **Non-greedy**: "Match as little as possible while still allowing the overall pattern to match"

```php
// More examples
$text = "aaaaab";
$greedy = "/a+b/";          // Matches "aaaaab" (all a's)
$lazy = "/a+?b/";           // Matches "ab" (minimum a's needed)
```

## Chapter 10: Complete Guide to PHP Regex Functions

PHP provides several powerful functions for working with regular expressions. Each serves different purposes and has specific use cases.

### preg_match() - Find First Match

**Purpose**: Tests if a pattern matches and optionally captures groups from the first match.

```php
int preg_match(string $pattern, string $subject, array &$matches = null, int $flags = 0, int $offset = 0)
```

**Parameters**:
- `$pattern`: The regex pattern
- `$subject`: The string to search in
- `$matches`: Array to store captured groups (optional)
- `$flags`: Optional flags (PREG_OFFSET_CAPTURE, etc.)
- `$offset`: Starting position in the string

**Return Values**:
- `1` if pattern matches
- `0` if no match
- `false` on error

```php
// Basic usage
$text = "The price is $45.99";
$pattern = "/\$(\d+)\.(\d+)/";

if (preg_match($pattern, $text, $matches)) {
    echo "Full match: " . $matches[0];     // "$45.99"
    echo "Dollars: " . $matches[1];       // "45"
    echo "Cents: " . $matches[2];         // "99"
}

// With offset - start searching from position 10
$result = preg_match("/\d+/", "abc123def456", $matches, 0, 10);
// Will find "456" instead of "123"

// With PREG_OFFSET_CAPTURE flag
preg_match("/\d+/", "abc123def", $matches, PREG_OFFSET_CAPTURE);
// $matches[0] = ["123", 3] (value and position)
```

### preg_match_all() - Find All Matches

**Purpose**: Finds all matches of a pattern and captures all groups.

```php
int preg_match_all(string $pattern, string $subject, array &$matches = null, int $flags = PREG_PATTERN_ORDER, int $offset = 0)
```

**Flags**:
- `PREG_PATTERN_ORDER` (default): Orders results by pattern
- `PREG_SET_ORDER`: Orders results by match sets
- `PREG_OFFSET_CAPTURE`: Include string offsets

```php
$text = "Contact: john@example.com or mary@test.org";
$pattern = "/(\w+)@([\w.]+)/";

// Default flag (PREG_PATTERN_ORDER)
preg_match_all($pattern, $text, $matches);
print_r($matches);
/*
Array (
    [0] => Array ( [0] => john@example.com [1] => mary@test.org )  // Full matches
    [1] => Array ( [0] => john [1] => mary )                      // First group (usernames)
    [2] => Array ( [0] => example.com [1] => test.org )          // Second group (domains)
)
*/

// With PREG_SET_ORDER flag
preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);
print_r($matches);
/*
Array (
    [0] => Array ( [0] => john@example.com [1] => john [2] => example.com )
    [1] => Array ( [0] => mary@test.org [1] => mary [2] => test.org )
)
*/

// Extract all numbers with their positions
$text = "Product 123 costs $45.99 and Product 456 costs $78.50";
preg_match_all("/\d+/", $text, $matches, PREG_OFFSET_CAPTURE);
foreach ($matches[0] as $match) {
    echo "Found '{$match[0]}' at position {$match[1]}\n";
}
```

### preg_replace() - Replace Matches

**Purpose**: Performs search and replace using regex patterns.

```php
mixed preg_replace(mixed $pattern, mixed $replacement, mixed $subject, int $limit = -1, int &$count = null)
```

```php
// Basic replacement
$text = "The quick brown fox";
$result = preg_replace("/brown/", "red", $text);
// Result: "The quick red fox"

// Using backreferences
$text = "John Smith, Mary Johnson";
$pattern = "/(\w+) (\w+)/";
$replacement = "$2, $1";  // $1 = first group, $2 = second group
$result = preg_replace($pattern, $replacement, $text);
// Result: "Smith, John, Johnson, Mary"

// Using named groups (PHP 7.2+)
$pattern = "/(?<first>\w+) (?<last>\w+)/";
$replacement = "${last}, ${first}";
$result = preg_replace($pattern, $replacement, $text);

// Multiple patterns and replacements
$text = "Hello World! How are you?";
$patterns = ["/Hello/", "/World/", "/!/"];
$replacements = ["Hi", "Universe", "?"];
$result = preg_replace($patterns, $replacements, $text);
// Result: "Hi Universe? How are you?"

// With limit and count
$text = "one two one three one";
$result = preg_replace("/one/", "ONE", $text, 2, $count);
// Result: "ONE two ONE three one" (only first 2 replaced)
// $count = 2 (number of replacements made)

// Case-insensitive replacement
$text = "PHP php Php pHp";
$result = preg_replace("/php/i", "JavaScript", $text);
// Result: "JavaScript JavaScript JavaScript JavaScript"
```

### preg_replace_callback() - Replace with Callback Function

**Purpose**: Uses a callback function to determine replacement values.

```php
mixed preg_replace_callback(mixed $pattern, callable $callback, mixed $subject, int $limit = -1, int &$count = null)
```

```php
// Convert numbers to words
function numberToWord($matches) {
    $numbers = ["zero", "one", "two", "three", "four", "five"];
    $number = (int)$matches[0];
    return $numbers[$number] ?? $matches[0];
}

$text = "I have 2 cats and 3 dogs";
$result = preg_replace_callback("/\d/", "numberToWord", $text);
// Result: "I have two cats and three dogs"

// Using anonymous function (closure)
$text = "hello world php";
$result = preg_replace_callback("/\b\w+\b/", function($matches) {
    return strtoupper($matches[0]);
}, $text);
// Result: "HELLO WORLD PHP"

// More complex example - format currency
$text = "The items cost 1234.56 and 789.12 dollars";
$result = preg_replace_callback("/(\d+)\.(\d{2})/", function($matches) {
    return "$" . number_format((float)$matches[0], 2);
}, $text);
// Result: "The items cost $1,234.56 and $789.12 dollars"
```

### preg_split() - Split String by Pattern

**Purpose**: Splits a string by regex pattern.

```php
array preg_split(string $pattern, string $subject, int $limit = -1, int $flags = 0)
```

**Flags**:
- `PREG_SPLIT_NO_EMPTY`: Don't return empty pieces
- `PREG_SPLIT_DELIM_CAPTURE`: Capture delimiter text too
- `PREG_SPLIT_OFFSET_CAPTURE`: Include string offsets

```php
// Basic splitting
$text = "apple,banana;orange:grape";
$parts = preg_split("/[,;:]/", $text);
// Result: ["apple", "banana", "orange", "grape"]

// Split by multiple whitespace
$text = "word1    word2\t\tword3\n\nword4";
$words = preg_split("/\s+/", $text);
// Result: ["word1", "word2", "word3", "word4"]

// With limit
$text = "one,two,three,four,five";
$parts = preg_split("/,/", $text, 3);
// Result: ["one", "two", "three,four,five"] (max 3 parts)

// With PREG_SPLIT_NO_EMPTY flag
$text = "a,,b,,,c";
$parts = preg_split("/,/", $text, -1, PREG_SPLIT_NO_EMPTY);
// Result: ["a", "b", "c"] (empty strings removed)

// With PREG_SPLIT_DELIM_CAPTURE flag
$text = "hello world php programming";
$parts = preg_split("/(\s+)/", $text, -1, PREG_SPLIT_DELIM_CAPTURE);
// Result: ["hello", " ", "world", " ", "php", " ", "programming"]

// Complex example - split by multiple delimiters but keep them
$text = "Hello! How are you? I'm fine.";
$parts = preg_split("/([!?.])/", $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
// Result: ["Hello", "!", " How are you", "?", " I'm fine", "."]
```

### preg_grep() - Filter Array by Pattern

**Purpose**: Returns array elements that match a pattern.

```php
array preg_grep(string $pattern, array $input, int $flags = 0)
```

```php
// Filter emails from array
$data = ["john@example.com", "invalid-email", "mary@test.org", "123456", "bob@site.net"];
$emails = preg_grep("/^[\w.]+@[\w.]+\.[a-z]{2,}$/i", $data);
// Result: ["john@example.com", "mary@test.org", "bob@site.net"]

// Filter numbers
$mixed = ["abc", "123", "def456", "789ghi", "999"];
$numbers = preg_grep("/^\d+$/", $mixed);
// Result: ["123", "999"]

// Invert match with PREG_GREP_INVERT
$words = ["apple", "123", "banana", "456", "cherry"];
$nonNumbers = preg_grep("/^\d+$/", $words, PREG_GREP_INVERT);
// Result: ["apple", "banana", "cherry"]
```

### preg_quote() - Escape Special Characters

**Purpose**: Escapes regex special characters in a string.

```php
string preg_quote(string $str, string $delimiter = null)
```

```php
// Escape user input for safe regex use
$userInput = "How much is $2.50?";
$escaped = preg_quote($userInput);
echo $escaped; // "How much is \$2\.50\?"

// Use in a pattern
$searchTerm = "user@example.com";
$pattern = "/" . preg_quote($searchTerm) . "/i";
// Creates: "/user@example\.com/i"

// With delimiter parameter
$searchTerm = "file/path/name.txt";
$escaped = preg_quote($searchTerm, "/");
// Result: "file\/path\/name\.txt" (forward slash is also escaped)
```

### preg_filter() - Filter and Replace

**Purpose**: Like preg_replace, but returns only elements where replacement occurred.

```php
mixed preg_filter(mixed $pattern, mixed $replacement, mixed $subject, int $limit = -1, int &$count = null)
```

```php
// Clean and filter valid emails
$emails = ["john@example.com", "invalid-email", "mary@test.org", "bad@", "@bad.com"];
$cleaned = preg_filter("/^([\w.]+@[\w.]+\.[a-z]{2,})$/i", "$1", $emails);
// Result: ["john@example.com", "mary@test.org"] (only valid emails returned)

// Extract and format phone numbers
$contacts = ["Call 555-123-4567", "Email me", "Phone: (555) 987-6543", "No contact"];
$phones = preg_filter("/.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*/", "($1) $2-$3", $contacts);
// Result: ["(555) 123-4567", "(555) 987-6543"]
```

### preg_last_error() - Get Last Error

**Purpose**: Returns error code from last regex operation.

```php
int preg_last_error()
```

**Error Constants**:
- `PREG_NO_ERROR`: No error
- `PREG_INTERNAL_ERROR`: Internal error
- `PREG_BACKTRACK_LIMIT_ERROR`: Backtrack limit exceeded
- `PREG_RECURSION_LIMIT_ERROR`: Recursion limit exceeded
- `PREG_BAD_UTF8_ERROR`: Malformed UTF-8 data
- `PREG_BAD_UTF8_OFFSET_ERROR`: Offset didn't correspond to valid UTF-8

```php
function safeRegexMatch($pattern, $subject) {
    $result = preg_match($pattern, $subject);
    
    if ($result === false) {
        $error = preg_last_error();
        switch ($error) {
            case PREG_NO_ERROR:
                return "No error";
            case PREG_INTERNAL_ERROR:
                return "Internal PCRE error";
            case PREG_BACKTRACK_LIMIT_ERROR:
                return "Backtrack limit exceeded";
            case PREG_RECURSION_LIMIT_ERROR:
                return "Recursion limit exceeded";
            case PREG_BAD_UTF8_ERROR:
                return "Malformed UTF-8 data";
            case PREG_BAD_UTF8_OFFSET_ERROR:
                return "Invalid UTF-8 offset";
            default:
                return "Unknown error";
        }
    }
    
    return $result;
}
```

## Chapter 10.1: Advanced PHP Regex Techniques

### Using PREG_OFFSET_CAPTURE for Position Tracking

```php
$text = "The price of item1 is $25.99 and item2 costs $45.50";
$pattern = "/\$(\d+)\.(\d+)/";

preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE);

foreach ($matches[0] as $i => $match) {
    echo "Price: {$match[0]} found at position {$match[1]}\n";
    echo "Dollars: {$matches[1][$i][0]} at position {$matches[1][$i][1]}\n";
    echo "Cents: {$matches[2][$i][0]} at position {$matches[2][$i][1]}\n\n";
}
```

### Multi-line and Single-line Modifiers

```php
// 'm' modifier - ^ and $ match line boundaries
$text = "Line 1\nLine 2\nLine 3";
preg_match_all("/^Line/m", $text, $matches);
// Matches all three lines

// 's' modifier - . matches newlines too
$html = "<div>\nContent\n</div>";
preg_match("/<div>.*<\/div>/s", $html, $matches);
// Matches the entire div including newlines

// 'x' modifier - ignore whitespace and allow comments
$pattern = "/
    ^                 # Start of string
    (\d{3})          # Area code
    [-.\s]?          # Optional separator
    (\d{3})          # Exchange
    [-.\s]?          # Optional separator
    (\d{4})          # Number
    $                # End of string
/x";
```

### Performance Considerations

```php
// Use specific quantifiers instead of .* when possible
// SLOW: "/.*@.*\..*/"
// FAST: "/[\w.]+@[\w.]+\.[a-z]{2,}/"

// Anchor patterns when validating entire strings
// SLOW: "/\d{3}-\d{2}-\d{4}/"  (can match partial strings)
// FAST: "/^\d{3}-\d{2}-\d{4}$/" (validates entire string)

// Use non-capturing groups when you don't need the captured value
// SLOWER: "/(cat|dog) food/"
// FASTER: "/(?:cat|dog) food/"
```

## Chapter 11: Advanced Concepts

### Lookaheads and Lookbehinds

```php
// Positive lookahead (?=...)
$pattern = "/\d+(?=px)/";     // Numbers followed by "px"
// Matches "12" in "12px" but not "12em"

// Negative lookahead (?!...)
$pattern = "/\d+(?!px)/";     // Numbers NOT followed by "px"

// Positive lookbehind (?<=...)
$pattern = "/(?<=\$)\d+/";    // Numbers preceded by "$"
// Matches "100" in "$100"

// Negative lookbehind (?<!...)
$pattern = "/(?<!\$)\d+/";    // Numbers NOT preceded by "$"
```

### Word Boundaries

```php
$pattern = "/\bcat\b/";       // Matches "cat" as a whole word
// Matches: "The cat sat" 
// Does NOT match: "category" or "concatenate"
```

## Chapter 12: Common Practical Examples

### Email Validation (Simplified)

```php
$pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

// Breaking it down:
// ^                    - Start of string
// [a-zA-Z0-9._%+-]+   - One or more valid username characters
// @                    - Literal @ symbol
// [a-zA-Z0-9.-]+      - One or more domain name characters
// \.                   - Literal dot
// [a-zA-Z]{2,}        - Two or more letters for TLD
// $                    - End of string
```

### Phone Number Extraction

```php
$pattern = "/\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}/";

// Matches various formats:
// (555) 123-4567
// 555-123-4567
// 555.123.4567
// 5551234567
```

### URL Matching

```php
$pattern = "/https?:\/\/[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/";

// Breaking it down:
// https?     - "http" optionally followed by "s"
// :\/\/      - Literal "://"
// [a-zA-Z0-9.-]+ - Domain characters
// \.         - Literal dot
// [a-zA-Z]{2,} - TLD (2 or more letters)
```

## Chapter 13: Common Pitfalls and Best Practices

### 1. Escaping Special Characters

```php
// WRONG - trying to match a literal dot
$pattern = "/file.txt/";      // Matches "file.txt", "filextxt", "file@txt", etc.

// CORRECT
$pattern = "/file\.txt/";     // Matches only "file.txt"
```

### 2. Being Too Greedy

```php
// WRONG - extracts content between tags
$html = '<div>Hello</div><div>World</div>';
$pattern = "/<div>(.*)<\/div>/";    // Matches "Hello</div><div>World"

// CORRECT
$pattern = "/<div>(.*?)<\/div>/";   // Matches "Hello" and "World" separately
```

### 3. Forgetting Word Boundaries

```php
// WRONG - matches partial words
$pattern = "/cat/";           // Matches "cat" in "category"

// CORRECT - matches whole words only
$pattern = "/\bcat\b/";       // Matches "cat" but not "category"
```

### 4. Not Validating the Entire String

```php
// WRONG - allows extra characters
$pattern = "/\d{3}-\d{2}-\d{4}/";   // Matches "123-45-6789xyz"

// CORRECT - validates entire string
$pattern = "/^\d{3}-\d{2}-\d{4}$/"; // Matches only "123-45-6789"
```

## Chapter 14: Testing and Debugging Regex

### Using preg_match with Error Checking

```php
function testRegex($pattern, $subject) {
    $result = preg_match($pattern, $subject, $matches);
    
    if ($result === false) {
        echo "Regex error: " . preg_last_error();
        return false;
    }
    
    if ($result === 1) {
        echo "Match found: " . $matches[0];
        return true;
    }
    
    echo "No match found";
    return false;
}
```

### Building Patterns Incrementally

Start simple and add complexity:

```php
// Step 1: Match any email-like structure
$pattern1 = "/\w+@\w+\.\w+/";

// Step 2: Allow dots in username
$pattern2 = "/[\w.]+@\w+\.\w+/";

// Step 3: Allow multiple domain parts
$pattern3 = "/[\w.]+@[\w.]+\.\w+/";

// Step 4: Add anchors for exact matching
$pattern4 = "/^[\w.]+@[\w.]+\.\w+$/";
```

## Summary: Key Takeaways

1. **Start simple** - Build patterns incrementally
2. **Understand greediness** - Use `?` to make quantifiers non-greedy when needed
3. **Use anchors** - `^` and `$` for exact string matching
4. **Escape meta characters** - Use `\` to match special characters literally
5. **Test thoroughly** - Regex can have unexpected matches
6. **Use word boundaries** - `\b` for whole word matching
7. **Group and capture** - Use `()` to extract parts of matches
8. **Choose appropriate functions** - `preg_match`, `preg_match_all`, `preg_replace`, `preg_split`

Remember: Regex is powerful but can become complex quickly. Always prioritize readability and maintainability. Sometimes a simple string function is better than a complex regex!

## Practice Exercises

Try creating patterns for:
1. Validating time in HH:MM format (24-hour)
2. Extracting hashtags from social media text
3. Finding and replacing multiple spaces with single spaces
4. Validating credit card numbers (basic format)
5. Extracting file extensions from file paths

The key to mastering regex is practice. Start with simple patterns and gradually work your way up to more complex ones!