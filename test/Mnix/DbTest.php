<?php
/**
 * Mulanix Framework
 *
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */
require_once 'PHPUnit/Extensions/Database/TestCase.php';
require_once 'PHPUnit/Framework.php';

require_once '_files/DbSub.php';

/**
 * Mulanix
 */
class Mnix_DbTest extends PHPUnit_Framework_TestCase
{
    /*public function testConstruct()
    {
        $dms = 'sqlite:/base.db';
        $db = new Mnix\DbSub($dms);
        $this->assertEquals('sqlite:/base.db', $this->_driver);
        $dms = 'sqlite:/base.db';
        $db = new Mnix\DbSub($dms);
        $this->assertEquals('sqlite:/base.db', $this->_driver);
    }*/
    public function testConnect()
    {
        $this->assertNull(Mnix\DbSub::instances());

        $db = Mnix\DbSub::connect('base0');
        $this->assertEquals('Mnix\DbSub', get_class($db));
        $this->assertEquals(Mnix\Db\base0\DBMS.':'.Mnix\Path\DB.'/'.Mnix\Db\base0\BASE , $db->_driver);
        $this->assertEquals(1, count(\Mnix\DbSub::instances()));

        $db = Mnix\DbSub::connect('base1');
        $this->assertEquals('Mnix\DbSub', get_class($db));
        $this->assertEquals(Mnix\Db\base1\DBMS.':dbname='.Mnix\Db\base1\BASE.';host='.Mnix\Db\base1\HOST.Mnix\Db\base1\USER.Mnix\Db\base1\PASS , $db->_driver);
        $this->assertEquals(2, count(\Mnix\DbSub::instances()));

        $db = Mnix\DbSub::connect('base3');
        $this->assertEquals('Mnix\DbSub', get_class($db));
        $this->assertEquals(Mnix\Db\base3\DBMS.':'.Mnix\Db\base3\BASE , $db->_driver);
        $this->assertEquals(3, count(\Mnix\DbSub::instances()));

        //Пишем базу, которой не существует в конфиге
        try {
            $db = Mnix\DbSub::connect('unExistBase');
            $this->fail();
        } catch (\Mnix\Exception $e) {
            $this->assertEquals('Mnix\Exception', get_class($e));
        }

        unset($db);
        
        $db = Mnix\DbSub::connect();
        $this->assertEquals('Mnix\DbSub', get_class($db));
        $this->assertEquals(3, count(\Mnix\DbSub::instances()));
    }
    public function testReturn()
    {
        $db = new Mnix\DbSub(null, null, null);
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