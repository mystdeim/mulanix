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
     * Тип базы данных
     *
     * @var string
     */
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
}