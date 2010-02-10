<?php
/**
 * Mulanix Framework
 */
namespace Mnix\Db;

require_once 'PHPUnit/Extensions/Database/TestCase.php';
require_once 'PHPUnit/Framework.php';

require_once '_files/CriteriaSub.php';
/**
 * Mulanix Framework
 *
 * @author deim
 */
class CriteriaTest extends \PHPUnit_Extensions_Database_TestCase
{
    public function __construct()
    {
        $this->connection = new \PDO('sqlite::memory:');
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
    public function testTable()
    {
        $criteria = new \Mnix\Db\CriteriaSub($this->getConnection()->getConnection());
        $criteria->table('table');
        $this->assertEquals($criteria->_table, array('table'));
    }
    public function testLimit()
    {
        $criteria = new \Mnix\Db\CriteriaSub($this->getConnection()->getConnection());
        $criteria->limit(5);
        $this->assertEquals($criteria->_limit, ' LIMIT 5');
        $criteria->limit(10, 5);
        $this->assertEquals($criteria->_limit, ' LIMIT 10, 5');
    }
    public function testBind()
    {
        $criteria = new \Mnix\Db\CriteriaSub($this->getConnection()->getConnection());
        $criteria->bindValue(':param0', 'value0', \PDO::PARAM_INT)
                        ->bindValue(':param1', 'value1', \PDO::PARAM_STR);
        $expected = array(
            ':param0' => array(
                'value' => 'value0',
                'type'  => \PDO::PARAM_INT),
            ':param1' => array(
                'value' => 'value1',
                'type'  => \PDO::PARAM_STR)
        );
        $this->assertEquals($expected, $criteria->_boundParams);
    }
    public function testExecute()
    {
        $criteria = new \Mnix\Db\CriteriaSub($this->getConnection()->getConnection());
        $result = $criteria->table('person')
                                  ->where('id = :idValue')
                                  ->bindValue(':idValue', 1, \PDO::PARAM_INT)
                                  ->execute();
        $expected = array(
            'id'      => 1,
            'name'    => 'Peter',
            'surname' => 'Mider'
        );
        $this->assertEquals(1, count($result));
        $this->assertEquals($expected, $result[0]);
    }
}