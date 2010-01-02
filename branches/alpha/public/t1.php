<?php


require_once '../test/Mnix/CoreSub.php';
require_once '../test/Mnix/CacheSub.php';

class B
{
    public function b1()
    {

        $b = new Mnix\Cache();

    }
}

class A
{
    public function __construct()
    {
        $b = new B();
        $b->b1();
    }
}


$a = new A();
$core = \Mnix\CoreSub::instance();
//echo '<pre>'.$core->_log.'</pre>';

$a = Mnix\Core::instance();

//$b = new Mnix\Cache();