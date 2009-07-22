<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Db
 * @version 2009-05-08
 * @since 2008-10-01
 */
/**
 * @category Mulanix
 * @package Mnix_Db
 */
class Mnix_Db
{
    protected $_param;
    protected $_con; //указатель coединения c cерверoм
    protected $_db;
    static protected $_instance = null;
    /**
     * Устанавливаем параметры соеднения с базой или указываем имя базы
     * @param mixed $nameDB
     */
    public static function connect($param = MNIX_DEFAULT_DB)
    {
        if (!is_array($param)) {
            $paramObj['type'] = constant('MNIX_DB_' . $param .'_TYPE');
            $paramObj['login'] = constant('MNIX_DB_' . $param .'_LOGIN');
            $paramObj['pass'] = constant('MNIX_DB_' . $param .'_PASS');
            $paramObj['host'] = constant('MNIX_DB_' . $param .'_HOST');
            $paramObj['base'] = constant('MNIX_DB_' . $param .'_BASE');
        } else {
            $paramObj = $param;
        }
        if (isset(self::$_instance[$paramObj['type']])) {
            foreach (self::$_instance[$paramObj['type']] as $temp) {
                if ($temp->getParam() === $paramObj) return $temp;
            }
        }
        self::$_instance[$paramObj['type']][] = new Mnix_Db($paramObj);
        Mnix_Core::putMessage(__CLASS__, 'sys', 'Connect to '.$paramObj['type'].' "'.$paramObj['base'].'"');
        return end(self::$_instance[$paramObj['type']]);

    }
    /**
     * Кладём указатель на соединение бд
     * @param object(mysqli) $con
     */
    public function putCon($con)
    {
        $this->_con = $con;
    }
    /**
     * Возвращаем параметры соединения с бд
     * @return array
     */
    public function getParam()
    {
        return $this->_param;
    }
    /**
     * Возвращаем указатель на соединение бд
     * @return object(mysqli)
     */
    public function getCon()
    {
        return $this->_con;
    }
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
        if (empty($this->_db)) {
            $name = 'Mnix_Db_Driver_'.$this->_param['type'];
            $this->_db = new $name($this);
        }
    }
    /**
     * Плэйсхолдер
     *
     * @param string $condition
     * @param mixed $data
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
     * <code>
     * array(
     *     't' => '`table`',
     *     'i' => (int)5,
     *     'f' => (float)45.98,
     *     'n' => 'text',
     *     's' => "'".mysqli_escape_string('text')."'"
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
            //Экранирование таблицы, возможно составное имя
            case 't':
                $dot = strpos($value, '.');
                //if ($dot) return '`'.mysqli_escape_string($this->_con, substr($value, 0, $dot)).'`'.substr($value, $dot++);
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
    protected function __construct($param)
    {
        $this->_param = $param;
    }
}