<?php
/**
 * Mulanix Framework
 */
namespace Mnix\Db\Driver;

require_once 'PHPUnit/Extensions/Database/TestCase.php';
require_once 'PHPUnit/Framework.php';

require_once '_files/StatementSub.php';
/**
 * Mulanix Framework
 *
 * @author deim
 */
class StatementTest extends \PHPUnit_Extensions_Database_TestCase
{
    //protected $_statement;
    public function __construct()
    {
        $this->connection = new \Mnix\Db\DriverSub('sqlite::memory:');
        $this->connection->query("
            CREATE TABLE person (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255),
                surname VARCHAR(255),
                age INTEGER,
                sex BOOL
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
    public function testGetSQL1()
    {
        $res = $this->connection->prepare('SELECT * FROM person WHERE id = :id AND sex = :sex');
        $res->bindValue(':id', 1, \PDO::PARAM_INT);
        $res->bindValue(':sex', true, \PDO::PARAM_BOOL);

        $this->assertEquals('SELECT * FROM person WHERE id = 1 AND sex = 1', $res->getSQL());

        $res->execute();
        $res = $res->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals(1, count($res));
        $this->assertEquals(array(
            'id'      => 1,
            'name'    => 'Peter',
            'surname' => 'Mider',
            'age'     => 20,
            'sex'     => 1),
                $res[0]);
    }
    public function testGetSQL2()
    {
        $res = $this->connection->prepare('SELECT * FROM person WHERE name = :name AND sex= :sex AND surname = :surname');
        $res->bindValue(':name', 'N`ageneril', \PDO::PARAM_STR);
        $res->bindValue(':surname', "Mudra'pragram", \PDO::PARAM_STR);
        $res->bindValue(':sex', false, \PDO::PARAM_BOOL);
        $this->assertEquals("SELECT * FROM person WHERE name = 'N`ageneril' AND sex=  AND surname = 'Mudra''pragram'", $res->getSQL());

        $res->execute();
        $res = $res->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals(1, count($res));
        $this->assertEquals(array(
            'id'      => 2,
            'name'    => 'N`ageneril',
            'surname' => "Mudra'pragram",
            'age'     => 20,
            'sex'     => 0),
                $res[0]);
    }
}