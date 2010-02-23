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
    protected $_array = array();
    protected $_index = 0;
    public function __construct($class)
    {
        $this->_class = $class;
        $param = $class::getParam();
        $this->_table = $param['table'];
    }
    public function putSelect($obj)
    {
        $this->_select = $obj;
    }
    public function load()
    {
        $this->_load();
    }

    public function current()
    {
        return current($this->_array);
    }
    public function key()
    {
        return key($this->_array);
    }
    public function next()
    {
        return next($this->_array);
    }
    public function rewind()
    {
        reset($this->_array);
    }
    public function valid()
    {
        return $this->current() !== false;
    }
    public function offsetSet($offset, $value)
    {
        if (!isset($offset))  $this->_array[] = $value;
        else $this->_array[$offset] = $value;
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
        return count($this->_array);
    }

    protected function _getDriver()
    {
        if (!isset($this->_driver)) $this->_driver = Db::connect()->driver();
        return $this->_driver;
    }
    protected function _select()
    {
        if (!isset($this->_select)) {
            $this->_select = new Db\Select($this->_getDriver());
            $this->_select->table($this->_table, '*');
        }
        return $this->_select;
    }
    protected function _load()
    {
        if (empty($this->_select)) $this->_select();
        $res = $this->_select->query();

        //Создаём элементы коллекции
        foreach ($res as $temp) {
            $obj = new $this->_param['class'];
            $obj->set($temp);
            $this->_members[] = $obj;
        }

        $this->_isLoad = TRUE;

        //Потом можно удалить
        unset($this->_select);
    }
}