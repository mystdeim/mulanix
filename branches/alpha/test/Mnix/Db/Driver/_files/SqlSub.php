<?php
/* 
 * Mulanix Framework
 */

namespace Mnix\Db\Driver;

require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/boot/bootstrap.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Db/Driver/Sql.php';

/**
 * Mulanix Framework
 *
 * @author deim
 */
class SqlSub extends \Mnix\Db\Driver\Sql
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
    protected function _setCon() {}
    protected function _shielding($value, $mode) {}
}