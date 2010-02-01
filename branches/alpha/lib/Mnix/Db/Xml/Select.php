<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id: MySql.php 80 2009-08-25 12:26:43Z mystdeim $
 * @author mystdeim <mysteim@gmail.com>
 */
namespace Mnix\Db\Xml;
/**
 * @category Mulanix
 */
class Select extends \Mnix\Db\Xml\Base implements \Mnix\Db\iSelect
{
    /**
     * Таблицы
     *
     * @var array
     */
    protected $_tables = array();
    protected $_join = null;
    /**
     * Указание таблицы
     *
     * @param mixed $table
     * @param mixed $column
     * @return object(\Mnix\Db\Xml\Select)
     */
    public function table($table, $column = null)
    {
        if (isset($column)) {
            if (!is_array($column)) $columns = array($column => null);
            else {
                if (is_int(key($column))) {
                    foreach ($column as $key => $val) $columns[$val] = null;
                } else $columns = $column;
            }
        }
        $this->_tables[$table]['columns'] = $columns;
        
        return $this;
    }
    public function where($condition, $data = null)
    {
        $this->_where = $this->_placeHolder($condition, $data);
        return $this;
    }
    /**
     * Join
     *
     * Работает как LEFT JOIN
     *
     * @param <type> $table
     * @param <type> $condition
     * @param <type> $column
     * @return <type>
     */
    public function join($table, $condition, $column = null)
    {
        $this->_join[key($table)] = array(
                'table' => current($table),
                'on'    => $condition
        );

        $this->table(key($table), $column);

        return $this;
    }
    /**
     * Выполнение запроса
     *
     * @return array
     */
    public function execute()
    {
        //Проверка условий
        foreach ($this->_tables as $tableName => &$tableValue) {

            //Если условие существует
            if (isset($this->_where)) {

                //Если выборка, только из 1 таблицы
                if (count($this->_tables) === 1) {
                    $tableValue['query'] = '/root/' . $tableName . '/item[' . $this->_where . ']';
                } else {

                }

            //Если нет, берерём все столбцы
            } else {
                $tableValue['query'] = '/root/' . $tableName . '/item';
            }
        }
        unset($tableValue);

        foreach ($this->_tables as $tableName => $tableValue) {
            $AllTableResult[$tableName] = $this->_NodesToArray($this->_driver->query($tableValue['query']));
        }

        $result = array();
        if (count($this->_tables) === 1) {
            foreach (current($AllTableResult) as $value) {
                $result[][key($AllTableResult)] = $value;
            }
        } else {
            if (isset($this->_join)) {

                //Обходим джойны
                foreach ($this->_join as $nameJoinTable => $params) {

                    //Обходим результаты главной таблицы
                    foreach ($AllTableResult[ $params['table'] ] as $item) {

                        //Флаг была ли запись
                        $flag = true;
//var_dump($item);
                        //Обходим результаты присоединяемой таблицы
                        foreach ($AllTableResult[ $nameJoinTable ] as $joinItem) {
                            if ($joinItem[ key($params['on']) ] === $item[ current($params['on']) ]) {
                                $i = count($result);
                                $result[$i][ $params['table'] ] = $item;
                                $result[$i][ $nameJoinTable   ] = $joinItem;
                                $flag = false;
                            }
                        }
                        //var_dump($item);
                        if ($flag) $result[][ $params['table'] ] = $item;
                    }

                }
            }
        }
//var_dump($result);

        foreach ($result as $resultValue) { 
            $valueItem = array();

            //Обходим таблицы
            foreach ($resultValue as $tableName => $tableValue) {
                
                //Если существуют столбцы, то смотрим их
                if (isset($this->_tables[$tableName]['columns'])) {

                    //Если * то пишем всё
                    if (array_key_exists('*', $this->_tables[$tableName]['columns'])) {
                        $valueItem = array_merge($valueItem, $tableValue);
                    }
                    else {
                        $arr = array();
                        foreach($this->_tables[$tableName]['columns'] as $column => $alias) {
                            if (isset($alias)) $arr[$alias] = $tableValue[$column];
                            else $arr[$column] = $tableValue[$column];
                        }
                        $valueItem = array_merge($valueItem, $arr);
                    }
                }
            }
            if (count($valueItem)) $resultFinish[] = $valueItem;
        }
        return $resultFinish;
    }
}