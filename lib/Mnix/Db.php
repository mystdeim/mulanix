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
 * Архитектура - Registry pattern, в $_instance лежат объекты, соотвествующие базам данных
 *
 * @category Mulanix
 */
class Db
{
    /**
     * Массив, содержащий подключения
     *
     * @var array
     */
    static protected $_instances = null;
    /**
     * Драйвер
     *
     * @var object(Mnix\Db\Driver)
     */
    protected $_driver;
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

            if (!defined('\Mnix\Db\\' . $nameOfBase . '\DBMS')) {
                throw new Exception('Такой базы данных не существует: ' . $nameOfBase);
            }

            $dsn = constant('\Mnix\Db\\' . $nameOfBase . '\DBMS') . ':';
            
            if (constant('\Mnix\Db\\' . $nameOfBase . '\DBMS') === 'sqlite') {
                if (constant('\Mnix\Db\\' . $nameOfBase . '\BASE') !== ':memory:') $dsn .= \Mnix\Path\DB . '/';
                $dsn .= constant('\Mnix\Db\\' . $nameOfBase . '\BASE');
                $user = null;
                $pass = null;
            } else {
                $dsn .= 'dbname='.constant('\Mnix\Db\\' . $nameOfBase . '\BASE' ).';host='.constant('\Mnix\Db\\'.$nameOfBase.'\HOST' );
                $user = constant('\Mnix\Db\\' . $nameOfBase . '\USER');
                $pass = constant('\Mnix\Db\\' . $nameOfBase . '\PASS' );
            }
        }
        return self::$_instances[$nameOfBase] = new static($dsn, $user, $pass);
    }
    /**
     * Защищенный конструктор
     *
     * @param array $param
     * @test ok
     */
    protected function __construct($dsn, $user, $pass)
    {
        $this->_driver = new Db\Driver($dsn, $user, $pass);
    }
    /**
     * @return object(Mnix\Db\Select)
     */
    public function select()
    {
        return new Db\Select($this);
    }
    public function update()
    {
        return  new Db\Update($this);
    }
    public function insert()
    {
        return new Db\Insert($this);
    }
    public function delete()
    {
        return new Db\Delete($this);
    }
    public function base()
    {
        return new Db\Base($this);
    }
}