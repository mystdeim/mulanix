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
    protected $_type;
    /**
     * Объект драйвера базы данных
     *
     * @var object(driver_db)
     */
    protected $_driver;
    /**
     * Массив, содержащий подключения
     *
     * @var array
     */
    static protected $_instances = null;
    /**
     * Установка соединения с базой данных
     *
     * @param array|string|null
     * @return object(Mnix\Db)
     */
    public static function connect($nameOfBase = null)
    {
        if (!isset($nameOfBase)) $nameOfBase = constant('Mnix\Core\BASE');
        if (isset(self::$_instances[$nameOfBase])) {
            return self::$_instances[$nameOfBase];
        } else {
            $typeOfBase = constant('Mnix\Db\\' . $nameOfBase . '\TYPE');
            switch ($typeOfBase) {
                case 'mysql':
                    $paramObj['login'] = constant('Mnix\Db\\' . $nameOfBase . '\LOGIN');
                    $paramObj['pass' ] = constant('Mnix\Db\\' . $nameOfBase . '\PASS' );
                    $paramObj['host' ] = constant('Mnix\Db\\' . $nameOfBase . '\HOST' );
                    $paramObj['base' ] = constant('Mnix\Db\\' . $nameOfBase . '\BASE' );
                    break;
                case 'xml':
                    $paramObj['file'] = constant('Mnix\Db\\' . $nameOfBase . '\FILE');
                    break;
                default:
                    throw new Exception('Не существует такого типа базы данных: ' . $typeOfBase);
                    break;
            }
            $paramObj['type' ] = $typeOfBase;
        }
        self::$_instances[$nameOfBase] = new static($paramObj);
        Core::log('s', 'Создано подключение к базе данных: ' . $nameOfBase);
        return self::$_instances[$nameOfBase];
    }
    /**
     * Защищенный конструктор
     *
     * @param array $param
     * @test ok
     */
    protected function __construct($param)
    {
        $this->_type = $param['type'];
        $driver = '\Mnix\Db\Driver\\' . $param['type'];
        $this->_driver = new $driver($param);
    }
    /**
     * Возвращаем объект Mnix_Db_Select
     *
     * @return object(Mnix_Db_Select)
     */
    public function select()
    {
        $this->_SUID('select');
        return new Mnix_Db_Select($this);
    }
    protected function _SUID($what)
    {
        $name = '\Mnix\Db\\' . $what;
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
        /*$this->_setDb();
        if (isset($data)) $sql = $this->_placeHolder($sql, $data);
        Mnix_Core::putTime('db');
        $value = $this->_db->execute($sql);
        Mnix_Core::putMessage(__CLASS__, 'sys', 'Query: ' . $sql . ' for ' . Mnix_Core::putTime('db', true));
        Mnix_Core::putCount('db_q');
        $err = $this->_db->getError();
        if (!empty($err)) Mnix_Core::putMessage(__CLASS__, 'err', 'Error ' . $err);
        return $value;*/
        
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
                //return "'".mysqli_escape_string($this->_con, $value)."'";
                //TODO: тут нужно защитить
                return $value;
        }
    }
}