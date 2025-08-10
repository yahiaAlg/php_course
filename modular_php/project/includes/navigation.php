    <!-- navigation  -->
    <nav>
        <div id="logo">
            <h1><?= $siteTitle ?></h1>
        </div>
        <ul>
            <?php
            foreach ($navLinks as $navTitle => $navLink) {

                echo "<li><a href='" . $navLink . "'>{$navTitle}</a></li>";
            }

            ?>
        </ul>
    </nav>