<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */

namespace Mnix;

/**
 * Абстракция базы данных
 *
 * Пока поддерживается только MySql, больше пока и не требуется.
 * Архитектура - Multiton pattern, в $_instance лежат объекты, соотвествующие базам данных
 *
 * @category Mulanix
 */
class Db
{
    /**
     * Параметры соеденения с базой
     *
     * @var array
     */
    protected $_param;
    /**
     * Указатель coединения c cерверoм
     *
     * @var object(mysqli)
     */
    protected $_con;
    /**
     * Объект драйвера базы данных
     *
     * @var object(driver_db)
     */
    protected $_db;
    /**
     * Массив, содержащий параметры подключенний
     *
     * @var array
     */
    static protected $_instance = null;
    /**
     * Установка соединения с базой данных
     *
     * @param array|string|null
     * @return object(Mnix\Db)
     */
    public static function connect($param = null)
    {
        if (!isset($param)) {
                $paramObj['type' ] = constant('Mnix\Db\Base\TYPE' );
                $paramObj['login'] = constant('Mnix\Db\Base\LOGIN');
                $paramObj['pass' ] = constant('Mnix\Db\Base\PASS' );
                $paramObj['host' ] = constant('Mnix\Db\Base\HOST' );
                $paramObj['base' ] = constant('Mnix\Db\Base\BASE' );
        } else {
            $paramObj = $param;
        }

        if (isset(self::$_instance[$paramObj['type']])) {
            foreach (self::$_instance[$paramObj['type']] as $temp) {
                if ($temp->getParam() === $paramObj) return $temp;
            }
        }
        self::$_instance[$paramObj['type']][] = new Db($paramObj);
        //Mnix_Core::putMessage(__CLASS__, 'sys', 'Connect to '.$paramObj['type'].' "'.$paramObj['base'].'"');
        return end(self::$_instance[$paramObj['type']]);
    }
    /**
     * Защищенный конструктор
     *
     * @param array $param
     * @test ok
     */
    protected function __construct($param)
    {
        $this->_param = $param;
    }
    /**
     * Кладём указатель на соединение бд
     *
     * @param object(mysqli) $con
     */
    public function putCon($con)
    {
        $this->_con = $con;
    }
    /**
     * Возвращаем параметры соединения с бд
     *
     * @return array
     */
    public function getParam()
    {
        return $this->_param;
    }
    /**
     * Возвращаем указатель на соединение бд
     *
     * @return object(mysqli)
     */
    public function getCon()
    {
        return $this->_con;
    }
    /**
     * Возвращаем объект Mnix_Db_Select
     *
     * @return object(Mnix_Db_Select)
     */
    public function select()
    {
        return new Mnix_Db_Select($this);
    }
    public function update()
    {
        return new Mnix_Db_Update($this);
    }
    public function insert()
    {
        return new Mnix_Db_Insert($this);
    }
    public function delete()
    {
        return new Mnix_Db_Delete($this);
    }
    /**
     * Запрос к БД
     *
     * Если $data не пусто, то сработает _placeHolder
     *
     * @param string $sql
     * @param mixed $data
     * @return array
     */
    public function query($sql, $data = null)
    {
        $this->_setDb();
        if (isset($data)) $sql = $this->_placeHolder($sql, $data);
        Mnix_Core::putTime('db');
        $value = $this->_db->execute($sql);
        Mnix_Core::putMessage(__CLASS__, 'sys', 'Query: ' . $sql . ' for ' . Mnix_Core::putTime('db', true));
        Mnix_Core::putCount('db_q');
        $err = $this->_db->getError();
        if (!empty($err)) Mnix_Core::putMessage(__CLASS__, 'err', 'Error ' . $err);
        return $value;
    }
    /**
     * Установка драйвера БД
     */
    protected function _setDb()
    {
        if (!isset($this->_db)) $this->_db = new Mnix_Db_Driver_MySql($this);
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
                return "'".mysqli_escape_string($this->_con, $value)."'";
        }
    }
}