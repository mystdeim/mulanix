<?php
/**
 * Mulanix Framework
 */
namespace Mnix\Db;

require_once 'PHPUnit/Extensions/Database/TestCase.php';
require_once 'PHPUnit/Framework.php';

require_once '_files/DriverSub.php';
/**
 * Mulanix Framework
 *
 * @author deim
 */
class DriverTest extends \PHPUnit_Extensions_Database_TestCase
{
    public function  __construct()
    {
        $this->connection = new DriverSub('sqlite::memory:');
        $this->connection->query("
            CREATE TABLE person (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255),
                surname VARCHAR(255)
            );
        ");
    }
    protected function getConnection()
    {
        return $this->createDefaultDBConnection($this->connection, 'sqlite');
    }
    protected function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/_files/person.xml');
    }
    public function tearDown()
    {

    }
    public function testConstruct()
    {
        $this->assertEquals('Mnix\Db\DriverSub', get_class($this->getConnection()->getConnection()));
    }
    /*public function testConnect()
    {
        $this->assertEquals(1,1);
    }*/
}