<?php

class E_E extends Exception {}
class E_E_E extends E_E {}

require '../test/Helper.php';
require '../lib/Mnix/Exception.php';
require '../test/Mnix/Core/_files/CoreSub.php';

class T1
{
    public function __construct($arg)
    {
        //$a++;
        $core = new Mnix_CoreSub();
        Mnix_CoreSub::log('s', 'test note');
        Mnix_CoreSub::log('s', 'test not with trace', true);
        Mnix_CoreSub::log('w', 'test warning');
        //throw new Mnix_Exception('test error');
    }
}

class T2
{
    public function __construct()
    {
        $t1 = new T1('a');
    }
}

$t2 = new T2();

/*try {
    $t2 = new T2();
} catch(Mnix_Exception $e) {
    //var_dump(debug_backtrace(false));
    var_dump($e);
} catch(Exception $e) {
    echo 'Exception';
}*/
