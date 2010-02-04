<?php
/*
 * Mulanix Framework
 */

namespace Mnix\Db\Driver;

require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/boot/bootstrap.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Db/Driver/Sql.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Db/Driver/MySql.php';

/**
 * Mulanix Framework
 *
 * @author deim
 */
class MySqlSub extends \Mnix\Db\Driver\MySql
{
    public function  __get($name)
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
    }
}