<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */
namespace Mnix\Db\Driver;
/**
 * Драйвер доступа к MySql
 *
 * @category Mulanix
 */
class MySql extends Sql
{
    /**
     * Выполнение запросса к БД
     *
     * @param string $sql
     * @return array
     */
    public function execute($sql) {
        if (!isset($this->_con)) $this->_setCon();
        $result = mysqli_query($this->_con, $sql);
        if ($result && $result !== TRUE) {
            for($data=array(); $row = mysqli_fetch_assoc($result); $data[] = $row);
            mysqli_free_result($result);
            return $data;
        } else return $result;
    }
    /**
     * Получение ошибок
     *
     * @return string
     */
    /*public function getError() {
        return mysqli_error($this->_con);
    }*/
    /**
     * Экранирование
     *
     * Пример:
     * <code>
     * array(
     *     't' => '`table`',
     *     'c' => '`column`',
     *     'i' => (int)5,
     *     'f' => (float)45.98,
     *     'n' => 'text',
     *     's' => "'".mysqli_escape_string('text')."'"
     * );
     * echo $this->_shielding('table', 't'); //`table`
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
    /**
     * Установка соединения с базой
     */
    protected function _setCon()
    {
        $this->_con = mysqli_connect($this->_param['host'],
                                     $this->_param['login'],
                                     $this->_param['pass'],
                                     $this->_param['base']);
    }
}