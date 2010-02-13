<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Db
 * @version 2009-07-25
 * @since 2008-10-01
 */
namespace Mnix\Db;
/**
 * Представляет объектно-ориентированный интерфейс создания SELECT-запросов
 *
 * @category Mulanix
 * @package Mnix_Db
 */
class Select extends Criteria
{
    protected $_column = array();
    protected $_order;
    /**
     * Добавление предложения FROM
     *
     * Указываем таблицу:
     * <code>
     * //SELECT table.* FROM table
     * $db->select()
     *    ->from('table', '*')
     *    ->query();
     * </code>
     * 
     * Указываем таблицу и столбцы:
     * <code>
     * //SELECT table.id, table.text FROM table
     * $db->select()
     *    ->from('table',
     *           array('id', 'text'))
     *    ->query();
     * </code>
     *
     * Указываем таблицу и псевдонимы столбцов:
     * <code>
     * //SELECT table.id AS "i", table.text AS "tx" FROM table
     * $db->select()
     *    ->from('table',
     *           array('id' => 'i', 'text' => 'tx'))
     *    ->query();
     * </code>
     *
     * Указываем несколько таблиц
     * <code>
     * //SELECT table.id AS "i", table.text AS "tx" FROM table, table2
     * $db->select()
     *    ->from('table2')
     *    ->from('table', array('id' => 'i', 'text' => 'tx)
     *    ->query();
     * </code>
     *
     * @param mixed $table таблица
     * @param mixed $columns столбцы
     * @return object(Mnix\Db\Select)
     */
    public function table($table, $column = null)
    {
        $this->_table[$table] = $table;
        $this->_helpColumn($table, $column);
        return $this;
    }
    /**
     * Добавление предложения FROM LEFT JOIN
     *
     * Пример:
     * <code>
     * //SELECT table.* FROM table LEFT JOIN jtable ON table.fk = jtable.id
     * $db->select()
     *    ->from('table')
     *    ->join(array('jtable' => 'table'),
     *           array('id'     => 'fk'))
     * </code>
     *
     * @param array $tables $key => $value $key - что, $value - к чему присоединять
     * @param array $on аналогично
     * @param array $column список столбцов
     */
    public function joinLeft($table, $on, $column = null)
    {
        if (isset($this->_table[current($table)])) {
            $this->_table[current($table)] .= ' LEFT JOIN '.key($table).' ON '.key($table).'.'.key($on).' = '.current($table).'.'.current($on);
        }
        $this->_helpColumn(key($table), $column);
        return $this;
    }
    /**
     * Сортировки
     *
     * @param mixed $column
     * @param mixed $desc
     * @return object(Mnix\Db\Select)
     */
    public function order($column, $desc = false) {
        $this->_order = ' ORDER BY ' . $column;
        if ($desc) $this->_order .= ' DESC';
        return $this;
    }
    protected function _helpColumn($table, $column)
    {
        if (isset($column)) {
            if (is_array($column)) {
                foreach ($column as $name => $alias) {
                    if (is_int($name)) $temp = $alias;
                    else $temp = $temp = $name . ' AS "'. $alias . '"';
                    $this->_column[] = $table . '.' . $temp;
                }
            } else $this->_column[] = $table . '.' . $column;
        }
    }
    protected function _queryBuilder()
    {
        return 'SELECT ' . implode(', ', $this->_column) . ' FROM ' . implode(', ', $this->_table) . $this->_where . $this->_order;
    }
}