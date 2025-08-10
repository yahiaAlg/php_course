<?php
require_once __DIR__ . "/config/settings.php"

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $siteDescription ?>">
    <title><?= $siteTitle ?></title>
    <link rel="stylesheet" href="<?= $css . 'style.css' ?>">
</head>

<body>
    <?php require_once  $includes_dir . "navigation.php" ?>
    <div class="content">
        <main>
            <?php include_once  $pages_content_dir . "{$starting_page}" ?>
        </main>
        <?php require_once $includes_dir . "sidebar.php" ?>
    </div>
    <?php include_once $includes_dir . "footer.php" ?>

</body>

</html>