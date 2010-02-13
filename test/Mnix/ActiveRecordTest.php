<?php
/**
 * Mulanix Framework
 */
namespace Mnix;

require_once 'PHPUnit/Extensions/Database/TestCase.php';
require_once 'PHPUnit/Framework.php';

require_once '_files/ActiveRecordSub.php';
require_once '_files/ActiveRecord/Person.php';
/**
 * Mulanix Framework
 *
 * @author deim
 */
class ActiveRecordTest extends \PHPUnit_Extensions_Database_TestCase
{
    public function  __construct()
    {
        $this->connection = new Db\Driver('sqlite::memory:');
        $this->connection->query("
            CREATE TABLE person (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255),
                surname VARCHAR(255),
                age INTEGER,
                car_id INTEGER
            );
        ");
        $this->connection->query("
            CREATE TABLE car (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255)
            );
        ");
    }
    protected function getConnection()
    {
        return $this->createDefaultDBConnection($this->connection, 'sqlite');
    }
    protected function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/_files/ActiveRecord.xml');
    }
    public function testConstruct()
    {
        $obj = new ActiveRecordSub();
        $this->assertEquals('Mnix\ActiveRecordSub', get_class($obj));
        $this->assertFalse($obj->_isLoad);
        $this->assertNull($obj->_select);
        $this->assertEquals(array(), $obj->_cortege);
    }
    public function test_Select()
    {
        $person = new ActiveRecord\Person(1);
        $person->setDriver($this->connection);
        /*$person->select();
        var_dump($person->_select);*/
    }
    public function testSimple()
    {
        $person = new ActiveRecord\Person(1);
        $person->setDriver($this->connection);
        $person->load();
        var_dump($person);
    }
}