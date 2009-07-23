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
    /**
     * Объект Mnix-Db
     * @var object(Mnix_Db)
     */
    protected $_db;
    /**
     * Массив условий where
     * @var array()
     */
	protected $_where = array();
    /**
     * Строка с уловием лимита(строка вместо массива, для быстроты)
     * @var string
     */
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
    /**
     * Конструктор
     *
     * @param Mnix_Db $obj
     */
    public function __construct($obj)
	{
		$this->_db = $obj;
	}
	/**
     * Указываем таблицу
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
     * //SELECT table.* FROM table WHERE field = 5
     * $db->select()
     *    ->from('table', '*')
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
    /**
     * Выполнение запроса через объект Mnix_Db
     * @return array()
     */
    public function query()
    {
        $arr = $this->_build();
        return $this->_db->query($arr['sql'], $arr['data']);
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
     * На вход:
     * <code>
     * array(
     *     0 => array(
     *         'data' => 'table'
     *         'sql'  => '?t'
     *  )
     * Стуктура возвращаемого массива:
     * <code>
     * array(
     *     'data' => array(
     *         0  => 'table'
     *      )
     *     'sql'  => '?t'
     * )
     * </code>
     * @param array $arr
     * @return array
     */
    protected function _helpBuild($arr) {
        if (isset($arr)) {
            $data = array();
            foreach ($arr as $temp) {
                $sql[] = $temp['sql'];
                $data = array_merge($data, $temp['data']);
            }
            $build['sql'] = implode(', ', $sql);
            $build['data'] = $data;
            return $build;
        } else return array('sql' => null, 'data' => array());
    }
}