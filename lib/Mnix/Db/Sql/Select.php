<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Db
 * @version 2009-07-25
 * @since 2008-10-01
 */
namespace Mnix\Db\Sql;
/**
 * Представляет объектно-ориентированный интерфейс создания SELECT-запросов
 *
 * @category Mulanix
 * @package Mnix_Db
 */
class Select extends \Mnix\Db\Sql\Criterion
{
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
     * //SELECT table.id AS i, table.text AS tx FROM table
     * $db->select()
     *    ->from('table',
     *           array('id' => 'i', 'text' => 'tx'))
     *    ->query();
     * </code>
     *
     * Указываем несколько таблиц + в первой уточняем столбцы:
     * <code>
     * //SELECT table.id AS 'i', table.text AS 'tx' FROM table, table2
     * $db->select()
     *    ->from('table')
     *    ->from('table2')
     *    ->from('table', array('id' => 'i', 'text' => 'tx)
     *    ->query();
     * </code>
     *
     * @param mixed $table таблица
     * @param mixed $columns столбцы
     * @return object(Mnix_Db_Select)
     */
    public function from($table, $column = null)
    {
        $this->_table[$table]['column'] = $this->_helpColumn($column);
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
        //var_dump($table);
        $this->_table[current($table)]['join'] = array(key($table) =>
            array(
                'on' => $on,
                'column' => $this->_helpColumn($column)
            )
        );
        return $this;
    }
    /**
     * @see Mnix_Db_Criterion
     */
    protected function _build()
    {
        $build['sql'] = 'SELECT ';
        $build['data'] = array();
        //FROM
        foreach ($this->_table as $table => $value) {
            //Column
            if (isset($value['column'])) {
                foreach ($value['column'] as $column => $alias) {
                    if (isset($alias)) {
                        $column_arr[]['sql'] = '?t AS ?s';
                        $column_arr[count($column_arr)-1]['data'][] = $table.'.'.$column;
                        $column_arr[count($column_arr)-1]['data'][] = $alias;
                    } else {
                        if ($column === '*') {
                            $column_arr[]['sql'] = '?t.*';
                            $column_arr[count($column_arr)-1]['data'][] = $table;
                        } else {
                            $column_arr[]['sql'] = '?t';
                            $column_arr[count($column_arr)-1]['data'][] = $table.'.'.$column;
                        }
                    }
                }
            }
            //Table
            $table_arr[]['data'][] = $table;
            if (isset($value['join'])) {
                $join = $value['join'];
                $jtable = key($join);
                if (isset($value['join'][$jtable]['column'])) {
                    foreach($value['join'][$jtable]['column'] as $column => $alias) {
                        if (isset($alias)) {
                            $column_arr[]['sql'] = '?t AS ?s';
                            $column_arr[count($column_arr)-1]['data'][] = $jtable.'.'.$column;
                            $column_arr[count($column_arr)-1]['data'][] = $alias;
                        } else {
                            $column_arr[]['sql'] = '?t';
                            $column_arr[count($column_arr)-1]['data'][] = $jtable.'.'.$column;
                        }
                    }
                }
                $table_arr[count($table_arr)-1]['sql'] = '?t LEFT JOIN ?t ON ?t = ?t';
                $table_arr[count($table_arr)-1]['data'][] = key($join);
                $table_arr[count($table_arr)-1]['data'][] = $table . '.' .current($join[key($join)]['on']);
                $table_arr[count($table_arr)-1]['data'][] = key($join) . '.' . key($join[key($join)]['on']);
            } else {
                $table_arr[count($table_arr)-1]['sql'] = '?t';
            }
        }
        if (!isset($column_arr)) $column_arr = null;
        $arr = $this->_helpBuild($column_arr);
        $build['sql'] .= $arr['sql'];
        $build['data'] = array_merge($build['data'], $arr['data']);
        $arr = $this->_helpBuild($table_arr);
        $build['sql'] .= ' FROM '. $arr['sql'];
        $build['data'] = array_merge($build['data'], $arr['data']);
        //WHERE
        if ($this->_where) {
            $arr = $this->_helpBuild($this->_where);
            $sql = ' WHERE ' . $arr['sql'];
            $build['data'] = array_merge($build['data'], $arr['data']);
            $build['sql'] .= $sql;
        }
        //LIMIT
        if (isset($this->_limit)) $build['sql'] .= $this->_limit;
        return $build;
    }
    /**
     * Хелпер
     *
     * Приводит массив полей к стандартному виду:
     * <code>
     * array(
     *     'field1' => 'alias1'
     *     'field2' => 'alias2')
     * </code>
     * Если нет аливсов, то:
     * <code>
     * array(
     *     'field1' => 'null'
     *     'field2' => 'null')
     * </code>
     *
     * @param array $column
     * @return array
     */
    protected function _helpColumn($column)
    {
        if (isset($column)) {
            if (!is_array($column)) $column = array($column);
            if (is_int(key($column))) {
                foreach ($column as $key => $val) $columns[$val] = null;
            } else $columns = $column;
        } else $columns = null;
        return $columns;
    }
}