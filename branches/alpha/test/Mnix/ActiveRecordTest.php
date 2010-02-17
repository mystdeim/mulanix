<?php
/**
 * Mulanix Framework
 */
namespace Mnix;

require_once 'PHPUnit/Extensions/Database/TestCase.php';
require_once 'PHPUnit/Framework.php';

require_once '_files/ActiveRecordSub.php';
require_once '_files/ActiveRecordSub/Person.php';
require_once '_files/ActiveRecordSub/Car.php';
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
    public function testSet()
    {
        $obj = new ActiveRecordSub();
        $obj->set(array('id'=>1));
        $this->assertEquals(1, current($obj->_get('_cortege')));
    }
    public function test__set()
    {
        $obj = new ActiveRecordSub();
        $this->assertEquals(array(), $obj->_get('_cortege'));
        $obj->id = 1;
        $this->assertEquals(array('id' => 1), $obj->_get('_cortege'));
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
    public function testGet()
    {
        $obj = new ActiveRecordSub();
        $obj->_set('_isLoad', TRUE);

        $obj->_set('_cortege', array('id' => 1, 'name'=>'Ivan', 'surname'=>'Ivanov'));
        $expected = array(
            'id'     => 1,
            'name'   =>'Ivan',
            'surname'=>'Ivanov'
        );
        $this->assertEquals($expected['name'], $obj->get('name'));
    }
    public function test__get()
    {
        $obj = new ActiveRecordSub();
        $obj->_set('_isLoad', TRUE);
        $obj->_set('_cortege', array('id' => 1));
        $this->assertEquals(1, $obj->id);
    }
    /*public function testLoadByOne()
    {
        $person = new ActiveRecordSub\Person();
        $person->setDriver($this->connection);

        $person->id = 1;

        $expected = array(
            'id'     => 1,
            'name'   => "Ivan",
            'surname'=> "Ivanov",
            'age'    => 20,
            'car_id' => 1
        );

        $this->assertTrue($person->load());
        $this->assertEquals($expected['name'], $person->name);
        $this->assertEquals($expected['surname'], $person->get(array('surname')));
        $this->assertEquals($expected['age'], $person->get('age'));
    }*/
    public function testLoadByFew()
    {
        $person = new ActiveRecordSub\Person();
        $person->setDriver($this->connection);

        $person->id = 1;
        $person->name = 'Ivan';
        $person->surname = 'Ivanov';

        $expected = array(
            'id'     => 1,
            'name'   => "Ivan",
            'surname'=> "Ivanov",
            'age'    => 20,
            'car_id' => 1
        );

        $this->assertTrue($person->load());
        $this->assertEquals($expected['name'], $person->name);
        $this->assertEquals($expected['surname'], $person->get(array('surname')));
        $this->assertEquals($expected['age'], $person->get('age'));
    }
    /*public function testSimple0()
    {
        $person = new ActiveRecordSub\Person(1);
        $person->setDriver($this->connection);

        $expected = array(
            'id'     => 1,
            'name'   => "Ivan",
            'surname'=> "Ivanov",
            'age'    => 20,
            'car_id' => 1
        );

        $this->assertEquals($expected['name'], $person->name);
        $this->assertEquals($expected['surname'], $person->get(array('surname')));
        $this->assertEquals($expected['age'], $person->get('age'));
    }
    public function testSimple1()
    {
        $person = new ActiveRecordSub\Person();
        $person->setDriver($this->connection);

        $person->id = 1;
        $person->load();

        $expected = array(
            'id'     => 1,
            'name'   => "Ivan",
            'surname'=> "Ivanov",
            'age'    => 20,
            'car_id' => 1
        );

        $this->assertEquals($expected['name'], $person->name);
    }
    public function testPersonHasOne()
    {
        $person = new ActiveRecordSub\Person(1);
        $person->setDriver($this->connection);

        $car = $person->car;
        $car->setDriver($this->connection);
        //$car->forceLoad();

        //var_dump($car);

        $this->assertEquals('Ivan`s car', $car->name);
    }
    public function testgetParam()
    {
        $person = new ActiveRecordSub\Person(1);
        $person->setDriver($this->connection);

        //var_dump($person->getParam('car'));
    }*/
}