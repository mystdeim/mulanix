<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Mnix\Db\Xml;
/**
 * Description of Delete
 *
 * @author deim
 */
class Delete extends \Mnix\Db\Xml\Base
{
    /**
     * Таблица
     *
     * @var string
     */
    protected $_table;
    /**
     * Условие
     *
     * @var string
     */
    protected $_where = null;
    /**
     * Выбор таблицы
     *
     * TODO: Можно сделать проверку на существование таблицы
     *
     * @param mixed $table
     * @param mixed $column
     * @return object(\Mnix\Db\Xml\Select)
     */
    public function table($table)
    {
        $this->_table = $table;
        return $this;
    }
    /**
     * Составление условия
     *
     * @param string $condition
     * @param mixed $data
     * @return object(\Mnix\Db\Xml\Delete)
     */
    public function where($condition, $data = null)
    {
        $this->_where = $this->_placeHolder($condition, $data);
        return $this;
    }
    /**
     * Выполение операции и возврат удалённых значений
     *
     * @return array
     */
    public function execute()
    {
        //var_dump($this->_table);
        $query = '/root/' . $this->_table . '/item';
        if (isset($this->_where)) $query .= '[' . $this->_where . ']';
        $nodeList = $this->_driver->query($query);
        foreach ($nodeList as $domElement) {
            //var_dump($domElement->ownerDocument->saveXML($domElement));
            $domElement->parentNode->removeChild($domElement);
        }
        if (count($nodeList)) $this->_driver->save();
        return $this->_NodesToArray($nodeList);
    }
}