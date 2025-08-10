<?php

echo "=== UNDERSTANDING preg_filter() vs preg_replace() ===\n\n";

// Pattern to match words starting with 'a'
$pattern = '/\ba\w+/i';
$replacement = '[MATCHED]';

// Example 1: Single string WITH a match
echo "Example 1: Single string WITH a match\n";
echo "----------------------------------------\n";
$input1 = "The apple is red";
$result1_filter = preg_filter($pattern, $replacement, $input1);
$result1_replace = preg_replace($pattern, $replacement, $input1);

echo "Input: '$input1'\n";
echo "preg_filter result: " . ($result1_filter ?? 'NULL') . "\n";
echo "preg_replace result: $result1_replace\n\n";

// Example 2: Single string WITHOUT a match
echo "Example 2: Single string WITHOUT a match\n";
echo "------------------------------------------\n";
$input2 = "The dog is brown";
$result2_filter = preg_filter($pattern, $replacement, $input2);
$result2_replace = preg_replace($pattern, $replacement, $input2);

echo "Input: '$input2'\n";
echo "preg_filter result: " . ($result2_filter ?? 'NULL') . "\n";
echo "preg_replace result: $result2_replace\n\n";

// Example 3: Array input - some strings match, some don't
echo "Example 3: Array input - mixed matches\n";
echo "--------------------------------------\n";
$input3 = [
    "I have an apple",      // matches 'an' and 'apple'
    "The dog is cute",      // no match
    "Amazing weather",      // matches 'Amazing'
    "No fruits here",       // no match
    "Another day"           // matches 'Another'
];

$result3_filter = preg_filter($pattern, $replacement, $input3);
$result3_replace = preg_replace($pattern, $replacement, $input3);

echo "Input array:\n";
foreach ($input3 as $key => $value) {
    echo "  [$key]: '$value'\n";
}

echo "\npreg_filter result (only strings with matches):\n";
if ($result3_filter) {
    foreach ($result3_filter as $key => $value) {
        echo "  [$key]: '$value'\n";
    }
} else {
    echo "  NULL\n";
}

echo "\npreg_replace result (all strings, modified or unchanged):\n";
foreach ($result3_replace as $key => $value) {
    echo "  [$key]: '$value'\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "KEY DIFFERENCE SUMMARY:\n";
echo "- preg_filter(): Excludes strings without matches\n";
echo "- preg_replace(): Includes all strings (changed or unchanged)\n";
echo str_repeat("=", 60) . "\n\n";

// Example 4: Practical use case - filtering email addresses
echo "Example 4: Practical use case - Extract valid emails\n";
echo "---------------------------------------------------\n";
$contacts = [
    "john@example.com",
    "invalid-email",
    "jane.doe@company.org",
    "not an email at all",
    "admin@site.net"
];

$email_pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
$email_replacement = 'EMAIL: $0'; // $0 refers to the entire match

echo "Original contacts:\n";
foreach ($contacts as $contact) {
    echo "  - $contact\n";
}

$valid_emails = preg_filter($email_pattern, $email_replacement, $contacts);

echo "\nFiltered result (only valid emails):\n";
if ($valid_emails) {
    foreach ($valid_emails as $email) {
        echo "  - $email\n";
    }
} else {
    echo "  No valid emails found\n";
}

// Example 5: Multiple patterns
echo "\nExample 5: Multiple patterns\n";
echo "----------------------------\n";
$texts = [
    "I love cats and dogs",
    "Birds are flying",
    "Fish swim in water",
    "No animals here"
];

$animal_patterns = [
    '/cats?/',
    '/dogs?/',
    '/birds?/i',
    '/fish/'
];

$animal_replacements = [
    '🐱',
    '🐶',
    '🐦',
    '🐟'
];

echo "Original texts:\n";
foreach ($texts as $text) {
    echo "  - $text\n";
}

$result = preg_filter($animal_patterns, $animal_replacements, $texts);

echo "\nFiltered result (only texts with animals):\n";
if ($result) {
    foreach ($result as $text) {
        echo "  - $text\n";
    }
}
