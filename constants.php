<?php
define('APP_NAME', 'MyApplication');
define('APP_VERSION', '1.0.0');
define('APP_AUTHOR', 'John Doe');
define('APP_LICENSE', 'MIT License');
define('APP_DESCRIPTION', 'This is a sample application for demonstration purposes.');
define('APP_URL', 'https://www.myapplication.com');
define('APP_COPYRIGHT', '© 2023 John Doe. All rights reserved.');
define('APP_RELEASE_DATE', '2023-10-01');

// theme configurations
define('THEME_NAME', 'dark theme');
define('THEME_VERSION', '1.0.0');
define('THEME_DARK_MODE', "flatlaf dark");
define('THEME_LIGHT_MODE', "flatlaf light");

const THEME_COLOR_PRIMARY = '#2f4f7f';


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="styles.css">
    <meta name="description" content="<?= APP_DESCRIPTION ?>">
    <meta name="keywords" content="application, <?= APP_NAME ?>, version <?= APP_VERSION ?>, <?= APP_AUTHOR ?>">
    <meta name="author" content="<?= APP_AUTHOR ?>">
    <title><?= APP_NAME ?> <?= APP_VERSION ?> - <?= APP_RELEASE_DATE ?></title>
    <?php if (THEME_NAME == 'dark theme'): ?>
        <link rel="stylesheet" href="dark-theme.css">
    <?php else: ?>
        <link rel="stylesheet" href="light-theme.css">
    <?php endif; ?>
</head>

<body>
    <nav>
        <div id="logo">
            <a href="<?= APP_URL ?>">
                <img src="logo.png" alt="<?= APP_NAME ?> Logo">
            </a>
        </div>
    </nav>

    <footer>
        <p>&copy; <?= APP_COPYRIGHT ?></p>
        <p>Version: <?= APP_VERSION ?> | Release Date: <?= APP_RELEASE_DATE ?></p>
        <p>Author: <?= APP_AUTHOR ?> | License: <?= APP_LICENSE ?></p>
    </footer>
</body>

</html>