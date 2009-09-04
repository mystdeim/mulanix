<?php

class MathException extends Exception {};

function f($a1, $a2)
{
    //echo '!!!!!<br />';
    $a = func_get_arg();
    //var_dump($a);
    //if (func_num_args() !== 2) throw new Exception();
}
function f2($a1)
{
    throw new MathException();
}
function errorHandler($errno, $errstr, $errfile, $errline) {
	throw new Exception($errstr, $errno);
}
set_error_handler('errorHandler');


try {
    //f('a', 'b', 'c');
    f('a');
} catch(Exception $e) {
    var_dump($e);
}

try {
    f2('$a1');
} catch(Exception $e) {
    var_dump($e);
}

function foo()
{
     $numargs = func_num_args();
     var_dump(func_get_arg());
     echo "Number of arguments: $numargs<br />\n";
     if ($numargs >= 2) {
         echo "Second argument is: " . func_get_arg(1) . "<br />\n";
     }
}

foo (1, 2, 3);

