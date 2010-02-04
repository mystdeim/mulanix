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
     * @param string|null
     * @return object(Mnix\Db)
     */
    public static function connect($nameOfBase = null)
    {
        //Если название базы не было передано в параметре, то берём из Mnix\Core\BASE
        if (!isset($nameOfBase)) $nameOfBase = constant('Mnix\Core\BASE');

        //Если существует уже существует подключение в реестре, то возвращаем его
        if (isset(self::$_instances[$nameOfBase])) return self::$_instances[$nameOfBase];
        else {

            if (!defined('Mnix\Db\\' . $nameOfBase . '\DBMS')) {
                throw new Exception('Такой базы данных не существует в config.xml: ' . $nameOfBase);
            }

            $dbmsOfBase = constant('Mnix\Db\\' . $nameOfBase . '\DBMS');
            switch ($paramObj['dbms' ] = $dbmsOfBase) {
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
                    throw new Exception('Не существует такого типа базы данных: ' . $dbmsOfBase);
                    break;
            }
        }
        return self::$_instances[$nameOfBase] = new static($paramObj);
    }
    /**
     * Защищенный конструктор
     *
     * @param array $param
     * @test ok
     */
    protected function __construct($param)
    {
        if (in_array($param['dbms'], array('mysql')))  $this->_type = 'Sql';
        else $this->_type = 'Xml';

        $driver = '\Mnix\Db\Driver\\' . $param['dbms'];
        $this->_driver = new $driver($param);
    }
    /**
     * @return object(Mnix\Db\Sql\Select)|object(Mnix\Db\Xml\Select)
     */
    public function select()
    {
        return $this->_queryObject('Select');
    }
    public function update()
    {
        return $this->_queryObject('Update');
    }
    public function insert()
    {
        return $this->_queryObject('Insert');
    }
    public function delete()
    {
        return $this->_queryObject('Delete');
    }
    public function base()
    {
        return $this->_queryObject('Base');
    }
    protected function _queryObject($what)
    {
        $name = '\Mnix\Db\\' . $this->_type . '\\' . $what;
        return new $name($this->_driver);
    }
}