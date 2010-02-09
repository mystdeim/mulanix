<?php
/**
 * Mulanix Framework
 */
require_once 'PHPUnit/Extensions/Database/TestCase.php';
require_once 'PHPUnit/Framework.php';

require_once '_files/StatementSub.php';
/**
 * Mulanix Framework
 *
 * @author deim
 */
class Mnix_Db_Driver_StatementTest extends PHPUnit_Extensions_Database_TestCase
{
    protected $_statement;
    public function __construct()
    {
        $this->connection = new Mnix\Db\Driver('sqlite::memory:');
        $this->connection->query("
            CREATE TABLE person (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255),
                surname VARCHAR(255),
                age INTEGER
            );
        ");
    }
    protected function getConnection()
    {
        $this->_statement = new Mnix\Db\Driver\StatementSub($this->connection);
        return $this->createDefaultDBConnection($this->connection, 'sqlite');
    }
    protected function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/_files/person.xml');
    }
    public function testGetSQL1()
    {
        $res = $this->connection->prepare('SELECT * FROM person WHERE id = :param');
        $res->bindValue(':param', 1, PDO::PARAM_INT);
        $this->assertEquals('SELECT * FROM person WHERE id = 1', $res->getSQL());

        $res->execute();
        $res = $res->fetchAll(PDO::FETCH_ASSOC);
        $this->assertEquals(1, count($res));
        $this->assertEquals(array(
            'id'      => 1,
            'name'    => 'Peter',
            'surname' => 'Mider',
            'age'     => 20),
                $res[0]);
    }
    public function testGetSQL2()
    {
        $res = $this->connection->prepare('SELECT * FROM person WHERE name = :param');
        $res->bindValue(':param', 'Peter', PDO::PARAM_STR);
        $this->assertEquals('SELECT * FROM person WHERE name = \'Peter\'', $res->getSQL());

        $res->execute();
        $res = $res->fetchAll(PDO::FETCH_ASSOC);
        $this->assertEquals(1, count($res));
        $this->assertEquals(array(
            'id'      => 1,
            'name'    => 'Peter',
            'surname' => 'Mider',
            'age'     => 20),
                $res[0]);
    }
}