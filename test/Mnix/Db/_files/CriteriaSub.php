<?php
/**
 * Mulanix Framework
 */
namespace Mnix\Db;

require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/boot/bootstrap.php';
require_once \Mnix\Path\LIB . '/Mnix/Exception.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Driver.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Driver/Statement.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Criteria.php';
/**
 * Mulanix Framework
 *
 * @author deim
 */
class CriteriaSub extends Criteria
{
    public function  __get($name)
    {
        return $this->$name;
    }
    /*public function  __set($name,  $value)
    {
        $this->$name = $value;
    }*/
    /*public function __call($name, $arguments)
    {
        return call_user_func_array(array($this, $name), $arguments);
    }*/
    protected function _queryBuilder()
    {
        return 'SELECT * FROM ' . implode(', ', $this->_table) . $this->_where;
    }
    public function queryBuilder()
    {
        return $this->_queryBuilder();
    }
}