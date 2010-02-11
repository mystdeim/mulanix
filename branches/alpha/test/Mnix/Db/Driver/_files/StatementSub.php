<?php
/**
 * Mulanix Framework
 */
namespace Mnix\Db\Driver;

require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/boot/bootstrap.php';
require_once \Mnix\Path\LIB . '/Mnix/Exception.php';
//require_once \Mnix\Path\LIB . '/Mnix/Db/Driver.php';
require_once dirname(dirname(__DIR__)) . '/_files/DriverSub.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Driver/Statement.php';

/**
 * Mulanix Framework
 *
 * @author deim
 */
class StatementSub extends Statement
{
    /*public function  __get($name)
    {
        return $this->$name;
    }*/
    /*public function  __set($name,  $value)
    {
        $this->$name = $value;
    }*/
    /*public function __call($name, $arguments)
    {
        return call_user_func_array(array($this, $name), $arguments);
    }*/
}