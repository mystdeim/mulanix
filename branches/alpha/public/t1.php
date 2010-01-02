<?php


require_once '../test/Mnix/CoreSub.php';
//require_once '../test/Mnix/CacheSub.php';

$core = \Mnix\Core::instance();

class B
{
    public function b1()
    {
        //\Mnix\Core::instance()->log('s', 'AAAAA');
        $b = new \Mnix\Cache();
        var_dump($b->dir());

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
var_dump(Mnix\Core::instance());
//echo '<pre>'.$core->_log.'</pre>';