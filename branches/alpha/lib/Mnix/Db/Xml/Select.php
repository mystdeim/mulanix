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
    protected $_many = null;
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
            $this->_tables[$table]['columns'] = $columns;
        } else {
           if (!isset($this->_tables[$table])) $this->_tables[$table] = array();
        }
        
        return $this;
    }
    public function where($condition, $data = null)
    {
        $this->_where = $this->_placeHolder($condition, $data);
        //var_dump($this->_where);
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
    protected function _sqlParser($str)
    {
        $items = explode(' ', $str);

        $flag = true;
        $i = 2;
        foreach ($items as $item) {
            if ((strpos($item, '@') === 0) && (strpos($item, '.') !== false)) {
                $tableField = explode('.', $item);
                if ($i) {
                    if ($flag) {
                        $table = substr($tableField[0], 1);
                        $field = $tableField[1];
                        $flag = false;
                    } else {
                        $this->_many[$table][substr($tableField[0], 1)] = array($field => $tableField[1]);
                        $i--;
                        $flag = true;
                    }
                }
            }
        }
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
                    $this->_sqlParser($this->_where);
                }

            } 
        }
        unset($tableValue);
        foreach ($this->_tables as $tableName => $tableValue) {
            if (!isset($tableValue['query'])) $tableValue['query'] = '/root/' . $tableName . '/item';
            $AllTableResult[$tableName] = $this->_NodesToArray($this->_driver->query($tableValue['query']));
        }

        $result = array();
        if (count($this->_tables) === 1) {
            foreach (current($AllTableResult) as $value) {
                $result[][key($AllTableResult)] = $value;
            }
        } else {

            if (isset($this->_many)) {

                //Лямда-функция, которая ищет соответсвие в массиве
                $find = function($arr, $needle) {
                    foreach($arr as $temp) {
                        if ($temp[key($needle)] === current($needle)) return $temp;
                    }
                    return false;
                };


                foreach ($AllTableResult[ key($this->_many) ] as $item) {
                    reset($this->_many[key($this->_many)]);
                    $tableA = key($this->_many[key($this->_many)]);
                    $fkA = key(current($this->_many[key($this->_many)]));
                    $idA = current(current($this->_many[key($this->_many)]));
                    $testA = $find($AllTableResult[$tableA], array($idA => $item[$fkA]));
                    next($this->_many[key($this->_many)]);
                    $tableB = key($this->_many[key($this->_many)]);
                    $fkA = key(current($this->_many[key($this->_many)]));
                    $idA = current(current($this->_many[key($this->_many)]));
                    $testB = $find($AllTableResult[$tableB], array($idA => $item[$fkA]));

                    if ($testA && $testB) {
                        $arr[key($this->_many)] = $item;
                        $arr[$tableA] = $testA;
                        $arr[$tableB] = $testB;
                        $result[] = $arr;
                    }
                }

            }

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
        $resultFinish =array();

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