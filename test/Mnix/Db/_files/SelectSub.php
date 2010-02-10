<?php
/**
 * Mulanix Framework
 */
namespace Mnix\Db;

require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/boot/bootstrap.php';
require_once \Mnix\Path\LIB . '/Mnix/Exception.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Criteria.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Select.php';
/**
 * Mulanix Framework
 *
 * @author deim
 */
class SelectSub extends Select
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
    public function queryBuilder()
    {
        return $this->_queryBuilder();
    }
}