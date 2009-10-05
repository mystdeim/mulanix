<?php

require_once '../test/Mnix/CacheSub.php';


class T1
{
    public function f1()
    {
        $obj = new T2();
        $obj->f2();
    }
}
class T2
{
    public function f2()
    {
        $obj = new Mnix_CacheSub();
        var_dump(debug_backtrace());
    }
}

$obj = new T1();
$obj->f1();

//$obj = new Mnix_CacheSub();