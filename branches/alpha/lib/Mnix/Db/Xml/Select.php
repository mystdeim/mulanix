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

        if (count($this->_tables) === 1) {
            //var_dump(current($AllTableResult));
            foreach (current($AllTableResult) as $value) {
                $result[][key($AllTableResult)] = $value;
            }
        } else {
            if (isset($this->_join)) {

            }
        }


        /*foreach ($this->_tables as $tableName => $tableValue) {
            $tableResult = $this->_NodesToArray($this->_driver->query($tableValue['query']));
            //Если существуют столбцы, то смотрим их
            if (isset($tableValue['columns'])) {
                //Если * то пишем всё
                if (array_key_exists('*', $tableValue['columns'])) $result = array_merge($tableResult);
                else {
                    //Обходим каждую строчку результата
                    foreach($tableResult as $itemName => $itemValue) {
                        //Обходим каждый столбец
                        foreach ($itemValue as $attrName => $attrValue) {
                            // и выбираем нужные столбцы
                            if (array_key_exists($attrName, $tableValue['columns'])) {
                                //Учитываем алиасы столбцов
                                if (!isset($tableValue['columns'][$attrName])) $item[$attrName] = $attrValue;
                                else $item[$tableValue['columns'][$attrName]] = $attrValue;
                            }
                        }
                        $result[] = $item;
                    }
                }
            }
        }*/

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
                        foreach($this->_tables[$tableName]['columns'] as $column => $alias) {
                            if (isset($alias)) $arr[$alias] = $tableValue[$column];
                            else $arr[$column] = $tableValue[$column];
                        }
                        $valueItem = array_merge($valueItem, $arr);
                    }
                }
            }
            $resultFinish[] = $valueItem;
        }

        return $resultFinish;
    }
}