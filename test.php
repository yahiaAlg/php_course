<?php
$names = ["yahia", "oussama", "aicha", "alp", "humongusaur"];
$sorted_array = usort($names, function (string $a, string $b) {
    return strlen($a) - strlen($b);
});
$rev_names = array_reverse($names);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <pre>
        <?= print_r($names, true); ?>
    </pre>
    <pre>
        <?= print_r($sorted_array, true); ?>
    </pre>
    <pre>
        <?= print_r($rev_names, true); ?>
    </pre>
</body>

</html>