<?php

ini_set('log_errors', true);
ini_set('error_reporting', E_ALL  & ~E_NOTICE);
//ini_set('display_errors', false);
ini_set('error_log', '/home/deim/Desktable/my-error.log');
echo $a;
//error_log("You messed up!", 3, "/home/deim/Desktable/my-error.log");

function errorHandler($errno, $errstr, $errfile, $errline) {
	throw new Exception($errstr, $errno);
}
set_error_handler('errorHandler');

function f($a)
{
    echo 'fff';
    var_dump(debug_backtrace());
    throw new Exception('fff');
}

//f();

try {
    //throw new Exception('fff', 1);
    //f('f');
    f();
    //echo 10 / 0;
} catch (Exception $e) {
    var_dump($e);
}

f();