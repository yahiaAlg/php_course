<?php
echo time() / 3600 / 24 / 365 . "<br>";

echo date("Y,M D H:i:s", time()) . "<br>";
echo "time sine 1970 till 1 january 2001 at 5:30 am is:" . mktime(5, 30, 00, 1, 1, 2001) . " seconds<br>";
echo date("Y-m-d H:i:s", mktime(5, 30, 00, 1, 1, 2001));

//TIME ZONES
echo date_default_timezone_get() . "<br>";

echo "time in UTC is before fixing it on local timezone: " . date("Y-m-d H:i:s", time()) . "<br>";
date_default_timezone_set("Africa/Algiers");
echo date_default_timezone_get() . "<br>";
echo "time in UTC is after fixing it on local timezone: " . date("Y-m-d H:i:s", time()) . "<br>";
echo date("Y-m-d, D H:i", strtotime("next friday +5 hours", mktime(5, 30, 00, 1, 1, 2001))) . "<br>";
echo "next forth of july" . date("Y-m-d", mktime(0, 0, 0, 6, 4, 2025));

$duration_in_month = (mktime(0, 0, 0, 6, 4, 2026) - time()) / 3600 / 24 / 30;
echo "<br>next forth of july is after " . $duration_in_month . " month";
