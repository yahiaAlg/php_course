<?php
// config.php - Site-wide configuration
$root_dir = dirname(dirname(__FILE__));
$includes_dir = __DIR__ . "/../includes/";
$static_url = "../static/";
$css = $static_url . "css/";
$js = $static_url . "js/";
$img = $static_url . "images/";
$pages_content_dir = __DIR__ . "/../pages/";
$pages_url = "../";
$siteTitle = "include demo";
$siteDescription = "A demonstration of PHP includes";
$starting_page = "contact.php";
$navLinks = [
    'main' => $pages_url . '',
    'us' => $pages_url . 'about.php',
    'get in touch' => $pages_url . 'contact.php'
];

// Database configuration (we'll use this in the file handling section)
$dbConfig = [
    'host' => 'localhost',
    'username' => 'myuser',
    'password' => 'mypassword',
    'database' => 'mydb'
];
