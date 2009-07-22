<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Db
 * @version 2009-05-08
 * @since 2008-10-01
 */
/**
 * @category Mulanix
 * @package Mnix_Db
 */
abstract class Mnix_Db_Criterion {
    protected $_db;
	protected $_where = array();
	protected $_limit = null;
    /**
     * Массив таблиц
     *
     * Стуктура массива:
     * <code>
     * array(
     *     0 => array(
     *         'data' => array(
     *             0 => 'data'
     *          )
     *         'sql' => 'sql'
     *     )
     * )
     * </code>
     * @var array
     */
	protected $_table = array();
    protected $_data;
	/**
     * Указываем таблицы
     * @param mixed $table
     * @return object(Mnix_Db_Criterion)
     */
	public function table($table) 
	{
		$this->_table = $this->shielding($table, 't').' ';
		return $this;
	}
	/**
     * Сортировки
     * @param mixed $condition
     * @param mixed $desc
     * @return object(Mnix_Db_Criterion)
     */
	public function order($condition, $desc = FALSE)
	{
		if (!is_array($condition)) {
			$this->_orderHelper($condition, $desc);
		} else {
			foreach ($condition as $value) {
				$mass = explode(' ', $value);
				if (isset($mass[1])&& $mass[1] === 'DESC') $mass[1] = TRUE;
				else $mass[1] = FALSE;
				$this->_orderHelper($mass[0], $mass[1]);
			}
		}
		return $this;
	}
	/**
     * Добавление предложения WHERE
     *
     * Простой пример
     * <code>
     * //SELECT * FROM table WHERE field = 5
     * $db->select()
     *    ->from('table')
     *    ->where('?t = ?i', array('field', 5))
     * </code>
     *
     * @param mixed $condition текстовое условие
     * @param mixed $data данные
     * @return object(Mnix_Db_Criterion)
     */
	public function where($condition, $data = null)
	{
		$this->_where[]['sql'] = $condition;
        if (isset($data)) {
            if (!is_array($data)) $this->_where[count($this->_where)-1]['data'][] = $data;
            else $this->_where[count($this->_where)-1]['data'] = array_merge($data);
        }

		return $this;
	}
	/*public function in($table, $data, $mode) {
		$in = NULL;
		foreach ($data as $temp) $in[] = $this->shielding($temp, $mode);
		$in = $this->shielding($table, 't').' IN ('.implode(', ', $in).')';
		if (empty($this->_where)) $this->_where = $in;
		else $this->_where .= ' AND '.$in;
		return $this;
	}
	
	public function andIn($table, $data, $mode) {
		$this->in($table, $data, $mode);
	}
	
	public function orIn($table, $data, $mode) {
		$in = NULL;
		foreach ($data as $temp) $in[] = $this->shielding($temp, $mode);
		$in = $this->shielding($table, 't').' IN ('.implode(', ', $in).')';
		if (empty($this->_where)) $this->_where = $in;
		else $this->_where .= ' OR '.$in;
		return $this;
	}*/
	
	
	//SET, VALUE
	public function set($name, $data = NULL) 
	{
	    if ($data !== NULL) {
	        if (!is_array($data)) $data = array($data);
	        else reset($data);
	    }
		foreach ($name as $key => $value) {
		    if ($data !== NULL) {
		        
		    }
			$this->set_arr[$this->shielding($key, 't')] =  $this->PlaceHolder($value, $data);
		}
		return $this;
	}
	
	public function value($name, $data = NULL) 
	{
		foreach ($name as $key => $value) {
			$this->set_arr[$this->shielding($key, 't')] =  $this->PlaceHolder($value, $data);
		}
		return $this;
	}	
	//Join
	public function join($data) 
	{
		
	}
	
	/**
     * Лимит выборки
     * @param mixed $first
     * @param mixed $last
     * @return object(Mnix_Db_Criterion)
     */
	public function limit($first, $last = null)
	{
		$this->_limit = ' LIMIT ' . (int)$first;
		if (isset($last)) $this->_limit .= ', '.(int)$last;
		return $this;
	}
    public function query()
    {
        $arr = $this->_build();
        return $this->_db->query($arr['sql'], $arr['data']);
    }
    /**
     * Конструктор
     * 
     * @param Mnix_Db $obj
     */
    public function __construct($obj)
	{
		$this->_db = $obj;
	}
	
	//Вспомогательные функции
	protected function _orderHelper($condition, $desc) {
		if (empty($this->order)) $this->order = $this->shielding($condition, 't');
		else $this->order .= ', '.$this->shielding($condition, 't');	
		if ($desc) $this->order .= ' DESC';
	}
    /**
     * Собиратель SQL
     *
     * Стуктура возвращаемого массива:
     * <code>
     * array(
     *     0 => array(
     *         'data' => array(
     *             0 => 'data'
     *          )
     *         'sql' => 'sql'
     *     )
     * )
     * </code>
     * @return array
     */
	abstract protected function _build();
    /**
     * Хелпер к Собирателю SQL
     *
     * Стуктура возвращаемого массива:
     * <code>
     * array(
     *     'data' => array(
     *         0 => 'data'
     *      )
     *     'sql' => 'sql'
     * )
     * </code>
     * @param array $arr
     * @return array
     */
    protected function _helpBuild($arr)
    {
        $data = array();
        foreach ($arr as $temp) {
            $sql[] = $temp['sql'];
            $data = array_merge($data, $temp['data']);
        }
        $build['sql'] = implode(', ', $sql);
        $build['data'] = $data;
        return $build;
    }
}