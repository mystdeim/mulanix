<?php
/**
 * Mulanix Framework
 *
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */
namespace Mnix;

require_once 'PHPUnit/Extensions/Database/TestCase.php';
require_once 'PHPUnit/Framework.php';

require_once '_files/DbSub.php';

/**
 * Mulanix
 */
class DbTest extends \PHPUnit_Framework_TestCase
{
    public function testConnect()
    {
        DbSub::clearInstance();
        $this->assertNull(DbSub::instances());

        $db = DbSub::connect('base0');
        $this->assertEquals('Mnix\DbSub', get_class($db));
        $this->assertEquals(Db\base0\DBMS. ':' . Db\base0\BASE , $db->_driver);
        $this->assertEquals(1, count(DbSub::instances()));

        $db = DbSub::connect('base1');
        $this->assertEquals('Mnix\DbSub', get_class($db));
        $this->assertEquals(Db\base1\DBMS.':dbname='.Db\base1\BASE.';host='.Db\base1\HOST.Db\base1\USER.Db\base1\PASS , $db->_driver);
        $this->assertEquals(2, count(DbSub::instances()));

        //Пишем базу, которой не существует в конфиге
        try {
            $db = DbSub::connect('unExistBase');
            $this->fail();
        } catch (Exception $e) {
            $this->assertEquals('Mnix\Exception', get_class($e));
        }

        unset($db);
        
        $db = DbSub::connect();
        $this->assertEquals('Mnix\DbSub', get_class($db));
        $this->assertEquals(2, count(DbSub::instances()));
    }
    public function testReturn()
    {
        $db = new DbSub(null, null, null);
        $this->assertEquals('Mnix\Db\Select', get_class($db->select()));
        $this->assertEquals('Mnix\Db\Update', get_class($db->update()));
        $this->assertEquals('Mnix\Db\Insert', get_class($db->insert()));
        $this->assertEquals('Mnix\Db\Delete', get_class($db->delete()));
        $this->assertEquals('Mnix\Db\Base', get_class($db->base()));
    }
}
/*class Mnix_DbTest extends PHPUnit_Extensions_Database_TestCase
{
    protected function getConnection()
    {
        $this->connection = new Mnix\Db\DriverSub('sqlite::memory:');
        return $this->createDefaultDBConnection($this->connection, 'sqlite');
    }
    protected function getDataSet()
    {
        $this->connection->query("
            CREATE TABLE person (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255),
                surname VARCHAR(255),
                age INTEGER
            );
        ");
        return $this->createFlatXMLDataSet(__DIR__ . '/_files/person.xml');
    }
    public function tearDown()
    {

    }
    public function testConstruct()
    {
        $this->assertEquals('Mnix\Db\DriverSub', get_class($this->connection));
    }
    public function testConnect()
    {
        $this->assertEquals(1,1);
    }
    public function tearDown()
    {
        Mnix\DbSub::clearInstance();
    }
    public function testConnect()
    {
        $this->assertNull(Mnix\DbSub::instances());
        
        $obj = Mnix\Db::connect('base1');
        $this->assertEquals('Mnix\Db', get_class($obj));

        $obj = Mnix\DbSub::connect('base0');
        $this->assertEquals('Mnix\DbSub', get_class($obj));
        $this->assertEquals('Mnix\Db\Driver\Xml', get_class($obj->_driver));
        $this->assertEquals(2, count(\Mnix\DbSub::instances()));

        //Подключаемся к базе, у которой ошибка в типе
        try {
            $obj = Mnix\DbSub::connect('base2');
            $this->fail();
        } catch (\Mnix\Exception $e) {
            $this->assertEquals('Mnix\Exception', get_class($e));
        }

        //Пишем базу, которой не существует в конфиге
        try {
            $obj = Mnix\DbSub::connect('unExistBase');
            $this->fail();
        } catch (\Mnix\Exception $e) {
            $this->assertEquals('Mnix\Exception', get_class($e));
        }

        unset($obj);
        
        $obj = Mnix\DbSub::connect('base1');
        $this->assertEquals('Mnix\Db', get_class($obj));
        $this->assertEquals(2, count(\Mnix\DbSub::instances()));
    }
    public function testQueryObjects()
    {
       $db = Mnix\Db::connect('base1');
       $this->assertEquals('Mnix\Db\Sql\Select', get_class($db->select()));

       $db = Mnix\Db::connect('base0');
       $this->assertEquals('Mnix\Db\Xml\Select', get_class($db->select()));
    }
}*/