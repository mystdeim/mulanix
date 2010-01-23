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
class Select extends \Mnix\Db\Base implements \Mnix\Db\iSelect
{
    /**
     *
     * @var array
     */
    protected $_tables = array();
    /**
     *
     * @param mixed $table
     * @param mixed $column
     * @return object(\Mnix\Db\Xml\Select)
     */
    public function table($table, $column = null)
    {
        if (!is_array($table)) $this->_tables[$table] = null;
        else {
            $this->_tables[key($table)]['alias'] = current($table);
            $table = key($table);
        }
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
    /**
     *
     * @return array
     */
    public function execute()
    {
        //Обходим таблицы из которых бцдет выборка
        foreach ($this->_tables as $tableName => $tableValue) {
            $tableResult = $this->_driver->query('/root/' . $tableName . '/item');
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
        }
        return $result;
    }
}