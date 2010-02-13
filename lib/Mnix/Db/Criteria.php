<?php
/**
 * Mulanix Framework
 */
namespace Mnix\Db;
/**
 * Формирование SQL-запроса
 *
 * Абстракный класс, содержащий некоторые повторяющиеся методы: where(), limit() и тп.
 * Представляет объектно-ориентированный интерфейс создания SQl-запросов.
 */
abstract class Criteria {
    /**
     * Объект Mnix\Db\Driver
     *
     * @var object(Mnix\Db\Driver)
     */
    protected $_pdo;
    protected $_boundParams = array();
    /**
     * Массив условий where
     *
     * @var string
     */
    protected $_where;
    /**
     * Строка с уловием лимита(строка вместо массива, для быстроты)
     *
     * @var string
     */
    protected $_limit = null;
    /**
     * Массив таблиц
     *
     * Стуктура массива для класса \Mnix\Db\Select:
     * <code>
     * array(
     *     'table0' => 'table0'
     *     'table1' => 'table1'
     * )
     * </code>
     * Для остальных классов:
     * <code>
     * array(
     *     0 => 'table0'
     *     1 => 'table1'
     * )
     * </code>
     * 
     * @var array
     */
    protected $_table = array();
    /**
     * Конструктор
     *
     * @param object(Mnix\Db\Driver) $obj
     */
    public function __construct($pdo) {
        $this->_pdo = $pdo;
    }
    /**
     * Указываем таблицу
     *
     * @param mixed $table
     * @return object(Mnix\Db\Criterion)
     */
    public function table($table) {
        $this->_table[] = $table;
        return $this;
    }
    public function bindValue($parameter, $value, $type = \PDO::PARAM_STR)
    {
        $this->_boundParams[$parameter] = array(
            'value' => $value,
            'type' => $type
        );
        return $this;
    }
    /**
     * Добавление предложения WHERE
     *
     * Примеры:
     * 1. SELECT table.* FROM table WHERE id > 10
     * <code>
     * $db->select()
     *    ->from('table', '*')
     *    ->where('id > 10')
     *    ->execute();
     * </code>
     *
     * @param mixed $condition текстовое условие
     * @return object(Mnix\Db\$this)
     */
    public function where($condition) {
        $this->_where = ' WHERE ' . $condition;
        return $this;
    }
    /**
     * Лимит выборки
     *
     * Примеры:
     * 1. SELECT table.* FROM table WHERE LIMIT 5
     * <code>
     * $db->select()
     *    ->from('table', '*')
     *    ->limit(5)
     *    ->query();
     * </code>
     * 2. SELECT table.* FROM table WHERE LIMIT 5, 10
     * <code>
     * $db->select()
     *    ->from('table', '*')
     *    ->limit(5, 10)
     *    ->query();
     * </code>
     *
     * @param mixed $first
     * @param mixed $last
     * @return object(Mnix_Db_Criterion)
     */
    public function limit($first, $last = null) {
        $this->_limit = ' LIMIT ' . $first;
        if (isset($last)) $this->_limit .= ', ' . $last;
        return $this;
    }
    /**
     * Выполнение запроса
     *
     * @return array
     */
    public function execute()
    {
        $statement = $this->_pdo->prepare($this->_queryBuilder());
        //var_dump($this->_queryBuilder());
        foreach($this->_boundParams as $param => $val) {
            $statement->bindValue($param, $val['value'], $val['type']);
        }
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    /**
     * Собиратель SQL
     */
    protected abstract function _queryBuilder();
    /*protected function _helpWhere()
    {
        if (count($this->_where)) {
            if (count($this->_where) === 1) {
                return current($this->_where);
            } else {
                foreach ($this->_where as $where) {

                }
            }
        } else return null;
    }*/
}