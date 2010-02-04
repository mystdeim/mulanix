<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Mnix\Db\Xml;
/**
 * Description of Base
 *
 * @author deim
 */
class Common extends \Mnix\Db\Common
{
    /**
     * Плэйсхолдер
     *
     * @param string $condition
     * @param array|string|float|int|null $data
     * @return string
     */
    protected function _placeHolder($condition, $data = null)
    {
        if (isset($data)) {
            if (!is_array($data)) $data = array($data);
            else reset($data);
        }
        foreach ($data as $temp) {
            $until = strpos($condition, '?');
            $condition = substr($condition, 0, $until)
                . $this->_shielding($temp, substr($condition, $until+1, 1))
                . substr($condition, $until+2);
        }
        return $condition;
    }
    /**
     * Экранирование
     *
     * Пример:
     * <code>
     * array(
     *     't' => $value,
     *     'c' => '@'.$value,
     *     'i' => (int)$value,
     *     'f' => (float)$value,
     *     'n' => $value,
     *     's' => htmlentities($value, ENT_QUOTES)
     * )
     * </code>
     *
     * @param string $value
     * @param string $mode
     * @return mixed
     */
    protected function _shielding($value, $mode)
    {
        switch ($mode) {
            case 't':
                return $value;
            case 'c':
                return '@'.$value;
            case 'i':
                return (int)$value;
            case 'f':
                return (float)$value;
            case 'n':
                return $value;
            case 's':
                return htmlentities($value, ENT_QUOTES);
        }
    }
    /**
     * Преобразовывает объект класса DOMNodeList в ассоциативный массив
     *
     * @param DOMNodeList $nodeList
     * @return array
     */
    protected function _NodesToArray($nodeList)
    {
        $result = array();
        foreach ($nodeList as $domElement){
            foreach ($domElement->attributes as $attr) $node[$attr->nodeName] = $attr->nodeValue;
            $result[] = $node;
        }
        return $result;
    }
}