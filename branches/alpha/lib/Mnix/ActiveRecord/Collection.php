<?php
/**
 * Mulanix Framework
 */
namespace Mnix\ActiveRecord;
/**
 * Mulanix Framework
 *
 * @author deim
 */
class Collection extends Common implements \Iterator, \ArrayAccess, \Countable
{
    protected $_class;
    protected $_table;
    protected $_members = array();
    protected $_index = 0;
    public function __construct($class)
    {
        $this->_class = $class;
        $param = $class::getParam();
        $this->_table = $param['table'];
    }
    public function select($obj)
    {
        $this->_select = $obj;
    }
    public function load()
    {
        $this->_load();
    }

    public function current()
    {
        return current($this->_members);
    }
    public function key()
    {
        return key($this->_members);
    }
    public function next()
    {
        return next($this->_members);
    }
    public function rewind()
    {
        reset($this->_members);
    }
    public function valid()
    {
        return $this->current() !== false;
    }
    public function offsetSet($offset, $value)
    {
        /*if (!isset($offset))  $this->_members[] = $value;
        else $this->_members[$offset] = $value;*/
    }
    public function offsetExists($offset)
    {
        //return isset($this->container[$offset]);
    }
    public function offsetUnset($offset)
    {
        //unset($this->container[$offset]);
    }
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
        //return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }
    public function count()
    {
        //var_dump(count($this->_members));
        return count($this->_members);
    }

    protected function _select()
    {
        if (!isset($this->_select)) {
            $this->_select = new \Mnix\Db\Select($this->_getDriver());
            $this->_select->table($this->_table, '*');
        }
        return $this->_select;
    }
    protected function _load()
    {
        $res = $this->_select()->execute();

        //Создаём элементы коллекции
        foreach ($res as $temp) {
            $obj = new $this->_class;
            $obj->set($temp);
            $this->_members[] = $obj;
        }

        //Потом можно удалить
        unset($this->_select);
    }
}