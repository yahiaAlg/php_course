<?php
// a course about PHP exceptions
// exceptions are used to handle runtime errors and unexpected events
// a function which divide the numbers in a table by some given number

function show_table($table)
{
    echo "<pre>";
    print_r($table);
    echo "</pre>";
}

function divide_numbers($table, $divisor)
{
    // Manually check for division by zero and throw an exception
    if ($divisor == 0) {
        throw new Exception("Cannot divide by zero!");
    }

    $result = array();
    foreach ($table as $number) {
        $result[] = $number / $divisor;
    }
    echo "The result is: ";
    show_table($result);
    return $result;
}

// Initialize result variable
$result = null;
$numbers = [];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Exception Handling Demo</title>
</head>

<body>
    <form method="get">
        <label for="divider">Divider:</label>
        <input type="number" id="divider" name="divider" value="0">
        <input type="submit" name="submit" value="calculate">
    </form>
    <div class="result">

    </div>
</body>

</html>