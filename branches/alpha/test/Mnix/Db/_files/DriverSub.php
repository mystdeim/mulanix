<?php
/**
 * Mulanix Framework
 */
namespace Mnix\Db;

require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/boot/bootstrap.php';
//require_once dirname(dirname(dirname(__DIR__))) . '/lib/Mnix/Exception.php';
//require_once dirname(dirname(dirname(__DIR__))) . '/lib/Mnix/Core.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Driver.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Driver/Statement.php';
/**
 * Mulanix Framework
 *
 * @author deim
 */
class DriverSub extends Driver
{
    /*public function  __get($name)
    {
        return $this->$name;
    }
    public function  __set($name,  $value)
    {
        $this->$name = $value;
    }
    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this, $name), $arguments);
    }*/
}