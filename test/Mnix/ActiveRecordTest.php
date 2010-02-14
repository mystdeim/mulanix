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
        $this->assertFalse($obj->_get('_isLoad'));
        $this->assertNull($obj->_get('_select'));
        $this->assertEquals(array(), $obj->_get('_cortege'));

        $obj = new ActiveRecordSub(1);
        $this->assertEquals(array('id' => 1), $obj->_get('_cortege'));
    }
    public function test_setAttribute()
    {
        $obj = new ActiveRecordSub();
        $this->assertEquals(array(), $obj->_get('_cortege'));
        $obj->setAttribute(array('id' => 1));
        $this->assertEquals(array('id' => 1), $obj->_get('_cortege'));
    }
    public function test__set()
    {
        $obj = new ActiveRecordSub();
        $this->assertEquals(array(), $obj->_get('_cortege'));
        $obj->id = 1;
        $this->assertEquals(array('id' => 1), $obj->_get('_cortege'));
    }
    public function test__callSet()
    {
        $obj = new ActiveRecordSub();
        $this->assertEquals(array(), $obj->_get('_cortege'));
        $obj->setId(1);
        $this->assertEquals(array('id' => 1), $obj->_get('_cortege'));

        $obj->set(array('age'=>21,'car_id'=>1));
        $expected = array(
            'id'      => 1,
            'age'     => 21,
            'car_id'  => 1
        );
        $this->assertEquals($expected, $obj->_get('_cortege'));
    }
    public function test_getAttribute()
    {
        $obj = new ActiveRecordSub();
        $obj->_set('_isLoad', TRUE);
        $obj->_set('_cortege', array('id' => 1));
        $this->assertEquals(1, $obj->getAttribute(array('id')));

        $obj->_set('_cortege', array('id' => 1, 'name'=>'Ivan', 'surname'=>'Ivanov'));
        $expected = array(
            'id'     => 1,
            'name'   =>'Ivan',
            'surname'=>'Ivanov'
        );
        $this->assertEquals($expected, $obj->getAttribute(array('id', 'name', 'surname')));
    }
    /*public function testSet()
    {
        $person = new ActiveRecord\Person(1);
        //$person->_set('_isLoad', TRUE);
        $person->name = 'Ivan';
        //var_dump($person);
        $person->setSurname('Ivanov');
        $person->set(array('age'=>21,'car_id'=>1));
        $expected = array(
            'id'      => 1,
            'name'    => 'Ivan',
            'surname' => 'Ivanov',
            'age'     => 21,
            'car_id'  => 1
        );
        $this->assertEquals($expected, $expected->_get('cortege'));
    }*/
    /*public function testSimple()
    {
        $person = new ActiveRecord\Person(1);
        $person->setDriver($this->connection);
        $person->load();
        var_dump($person);
    }*/
}