<?php
/**
 * Mulanix Framework
 */
require_once 'PHPUnit/Extensions/Database/TestCase.php';
require_once 'PHPUnit/Framework.php';

require_once '_files/DriverSub.php';
/**
 * Mulanix Framework
 *
 * @author deim
 */
class Mnix_DriverTest extends PHPUnit_Extensions_Database_TestCase
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
}