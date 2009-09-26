<?php

var_dump(microtime());
var_dump((float)microtime());
var_dump(microtime(true));

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$time_start = microtime_float();

// Sleep for a while
usleep(100);

$time_end = microtime_float();
$time = $time_end - $time_start;

echo "Did nothing in $time seconds\n";

require_once '../boot/bootstrap.php';
require_once '../test/Mnix/CoreSub.php';
Mnix_Core::instance()->run()->finish();