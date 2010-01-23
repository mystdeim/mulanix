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
class Base
{
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
     * Экранирование
     *
     * Пример:
     * <code>
     * array(
     *     't' => '`table`',
     *     'i' => (int)5,
     *     'f' => (float)45.98,
     *     'n' => 'text',
     *     's' => "'".mysqli_escape_string('text')."'")
     * $string = 'table';
     * echo $this->_shielding($string, 't');
     * //`table`
     * </code>
     *
     * @param string $value
     * @param string $mode
     * @return mixed
     */
    protected function _shielding($value, $mode)
    {
        switch ($mode) {
            //Экранирование таблицы, возможно составное имя
            case 't':
                $dot = strpos($value, '.');
                if ($dot) return '`' . mysqli_escape_string($this->_con, substr($value, 0, $dot)) .
                    '`.`' . mysqli_escape_string($this->_con, substr($value, ++$dot)) . '`';
                else return '`'.mysqli_escape_string($this->_con, $value).'`';
            case 'i':
                return (int)$value;
            case 'f':
                return (float)$value;
            case 'n':
                return $value;
            case 's':
                //return "'".mysqli_escape_string($this->_con, $value)."'";
                //TODO: тут нужно защитить
                return $value;
        }
    }
}