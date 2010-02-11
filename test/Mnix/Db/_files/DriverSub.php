<?php
/**
 * Mulanix Framework
 */
namespace Mnix\Db;

require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/boot/bootstrap.php';
//require_once dirname(dirname(dirname(__DIR__))) . '/lib/Mnix/Exception.php';
//require_once dirname(dirname(dirname(__DIR__))) . '/lib/Mnix/Core.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Driver.php';
//require_once \Mnix\Path\LIB . '/Mnix/Db/Driver/Statement.php';
require_once dirname(__DIR__) . '/Driver/_files/StatementSub.php';
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
    public function __construct($dsn, $username = null, $password = null, $driver_options = array())
    {
        parent::__construct($dsn, $username, $password, $driver_options);
        $this->setAttribute(\PDO::ATTR_STATEMENT_CLASS, array('Mnix\Db\Driver\StatementSub', array($this)));
    }
}