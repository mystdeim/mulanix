<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Mnix\Db\Driver;
/**
 * Description of Base
 *
 * @author deim
 */
abstract class Sql
{
    /**
     * Указатель coединения c cерверoм
     */
    protected $_con = null;
    /**
     * Параметры соеденения
     *
     * @var array
     */
    protected $_param;
    /**
     * Конструктор
     *
     * @param array $param Параметры соеденения
     */
    public function __construct($param)
    {
        $this->_param = $param;
    }
    /**
     * Плэйсхолдер
     *
     * Пример:
     * <code>
     * $string = 'SELECT * FROM ?t WHERE ?t = ?i;
     * $data = array('table','field', '5');
     * $result = $this->_placeHolder($string, $data);
     * echo $result;
     * //SELECT * FROM `table` WHERE `field` = 5
     * </code>
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
     * Установка соединения с базой
     */
    abstract protected function _setCon();
    /**
     * Экранирование
     *
     * @param mixed $value
     * @param string $mode
     */
    abstract protected function _shielding($value, $mode);
}