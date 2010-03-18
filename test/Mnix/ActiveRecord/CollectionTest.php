<?php
/**
 * Mulanix Framework
 */
namespace Mnix\ActiveRecord;

require_once '_files/CollectionSub.php';

require_once dirname(__DIR__) . '/_files/ActiveRecordSub.php';
require_once dirname(__DIR__) . '/_files/ActiveRecordSub/Person.php';
require_once dirname(__DIR__) . '/_files/ActiveRecordSub/Car.php';
require_once dirname(__DIR__) . '/_files/ActiveRecordSub/Comp.php';
/**
 * Mulanix Framework
 *
 * @author deim
 */
class CollectionTest extends \PHPUnit_Extensions_Database_TestCase
{
    protected function getConnection()
    {
        $this->connection = new \PDO('sqlite::memory:');
        $this->connection->query("
            CREATE TABLE person (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255),
                surname VARCHAR(255),
                age INTEGER
            );
        ");
        $this->connection->query("
            CREATE TABLE car (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255),
                person_id INTEGER
            );
        ");
        $this->connection->query("
            CREATE TABLE comp (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255),
                person_id INTEGER
            );
        ");
        $this->connection->query("
            CREATE TABLE person2house (
                person_id INTEGER,
                house_id INTEGER,
                PRIMARY KEY (person_id, house_id)
            );
        ");
        $this->connection->query("
            CREATE TABLE house (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255)
            );
        ");
        return $this->createDefaultDBConnection($this->connection, 'sqlite');
    }
    protected function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/_files/db.xml');
    }
    public function test1()
    {
        \Mnix\ActiveRecord::setDb($this->connection);
    }
    public function testSimpleLoad()
    {
        $select = new \Mnix\Db\Select($this->connection);
        $select->table('person', '*');

        $collection = new CollectionSub('Mnix\ActiveRecordSub\Person');
        $collection->select($select);

        $collection->load();

        $this->assertEquals(2, count($collection));
        
    }
}