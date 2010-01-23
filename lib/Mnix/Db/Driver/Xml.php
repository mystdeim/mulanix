<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id: MySql.php 80 2009-08-25 12:26:43Z mystdeim $
 * @author mystdeim <mysteim@gmail.com>
 */
namespace Mnix\Db\Driver;
/**
 * Драйвер доступа к xml
 *
 * @category Mulanix
 */
class Xml
{
    /**
     * Путь к xml-файлу
     *
     * @var mixed
     */
    protected $_file;
    /**
     * Загруженный DOMDocument
     *
     * @var DOMDocument
     */
    protected $_dom = null;
    /**
     * Конструктор
     *
     * @param array $papam
     */
    public function __construct($param)
    {
        $this->_file = \Mnix\Path\DB . '/' . $param['file'];
    }
    /**
     * Xpath-запрос
     *
     * @param string $query
     * @return array
     */
    public function query($query)
    {
        if (!isset($this->_dom)) $this->_load();
        $xpath = new \DOMXPath($this->_dom);
        $nodeList = $xpath->query($query);
        //var_dump($nodeList);
        $result = array();
        //var_dump($nodeList->ownerDocument->saveXML($nodeList));
        foreach ($nodeList as $domElement){
            $attributes = $domElement->attributes;
            //var_dump($domElement->ownerDocument->saveXML($domElement));
            foreach ($attributes as $attr) {
                $node[$attr->nodeName] = $attr->nodeValue;
            }
            $result[] = $node;
        }
        return $result;
    }
    /**
     * Загрузка xml-файла
     */
    protected function _load()
    {
        $this->_dom = new \DOMDocument();
        $this->_dom->load($this->_file);
    }
}