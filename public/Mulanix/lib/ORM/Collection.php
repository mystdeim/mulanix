<?php
/**
 * Mulanix Framework
 *
 * @package Mnix_ORM
 * @author deim
 * @copyright 2009
 */
/**
 * @package Mnix_ORM
 */
class Mnix_ORM_Collection implements Iterator
{
	protected $_members  = array();
	protected $_isLoad   = false;
	protected $_select;
	protected $_param;
    /**
     * Индекс элемента коллекции
     * @var int
     */
    protected $_currIndex = 0;

	public function __construct($class)
    {
		$this->_param = Mnix_ORM_Prototype::takeParam($class);
		$this->_param['class'] = $class;
	}
	protected function _select()
    {
		$this->_select = Mnix_Db::connect()->select()->from($this->_param['table']);
	}
    /**
     *
     * @param Mnix_Db_Select $obj 
     */
    public function putSelect($obj)
    {
		$this->_select = $obj;
	}
	public function find($condition, $data = null)
    {
		if (empty($this->_select)) $this->_select();
		$this->_select->where($condition, $data);
		$this->_isLoad = false;
		return $this;
	}
	/*public function add($data)
    {
	    
	}		
	public function getSelect()
    {
		return $this->_select;
	}*/
    //
	public function join($name) {
		if (empty($this->_select)) $this->_select();
		$param1 = Mnix_ORM_Prototype::takeParam($this->_param['class']);
		if (isset($param1['has_one'][$name])) {
			$class = $param1['has_one'][$name]['class'];
			$param2 = Mnix_ORM_Prototype::takeParam($class);
            //Пересчитываем столбцы для соединяемой таблицы
            foreach ($param1['fields'] as $key => $value) {
                $param1['fields'][$value] = $value;
                unset($param1['fields'][$key]);
            }
            //Пересчитываем столбцы для соединяющей таблицы
            foreach ($param2['fields'] as $key => $value) {
                $param2['fields'][$value] = $name.'.'.$value;
                unset($param2['fields'][$key]);
            }
            $this->_select->from($param1['table'], $param1['fields'])
                          ->joinLeft(array($param2['table']                => $param1['table']),
                                     array($param1['has_one'][$name]['fk'] => 'id'),
                                     $param2['fields']
            );
		}
		
		//die('Collection!!!! Тут надо исправить джойн!');
		if (isset($param1['has_many'][$name])) {
			$class = $param1['has_many'][$name]['class'];
			
		}		
		return $this;
	}
	/*public function order($condition, $desc = FALSE)
    {
		if (empty($this->_select)) $this->_setSelect();
		$this->_select->order($condition, $desc);
		return $this;
	}*/
    public function load()
    {
        $this->_load();
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
	//Переопределение виртуальных методов Iterator
	public function rewind()
    {
		//$this->_currIndex = 0;
        reset($this->_members);
    }
	public function next()
    {
		//$this->_currIndex++;
        next($this->_members);
	}
	public function current()
    {
		if (!$this->_isLoad) $this->_load();
		//return $this->_members[$this->_currIndex];
        return current($this->_members);
	}
	public function key()
    {
		//return $this->_currIndex;
        return key($this->_members);
	}
	public function valid()
    {
		//return $this->_currIndex < count($this->_members);
        return $this->current() !== false;
	}
	/*public function getAll() {
		if (empty($this->_members)) $this->_load();
		return $this->_members;
	}*/
}