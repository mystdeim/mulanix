<?php
/**
 * Mulanix Framework
 */
namespace Mnix;

require_once 'PHPUnit/Extensions/Database/TestCase.php';
require_once 'PHPUnit/Framework.php';

require_once '_files/ActiveRecordSub.php';
require_once 'ActiveRecord/_files/CollectionSub.php';
require_once '_files/ActiveRecordSub/Person.php';
require_once '_files/ActiveRecordSub/Car.php';
require_once '_files/ActiveRecordSub/Comp.php';
/**
 * Mulanix Framework
 *
 * @author deim
 */
class ActiveRecordTest extends \DatabaseTestCaseSub
{
    public function testConstruct()
    {
        ActiveRecordSub::setDriverToSub($this->connection);
        ActiveRecord\CollectionSub::setDriverToSub($this->connection);
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
    public function testLoadByOne()
    {
        $person = new ActiveRecordSub\Person();

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
    }
    public function testLoadByFew()
    {
        $person = new ActiveRecordSub\Person();

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
    public function testSimple0()
    {
        $person = new ActiveRecordSub\Person(1);

        $expected = array(
            'id'     => 1,
            'name'   => "Ivan",
            'surname'=> "Ivanov",
            'age'    => 20
        );

        $this->assertTrue($person->load());
        $this->assertEquals($expected['name'], $person->name);
        $this->assertEquals($expected['surname'], $person->get(array('surname')));
        $this->assertEquals($expected['age'], $person->get('age'));
    }
    public function testSimple1()
    {
        $person = new ActiveRecordSub\Person();

        $person->id = 1;
        $person->load();

        $expected = array(
            'id'     => 1,
            'name'   => "Ivan",
            'surname'=> "Ivanov",
            'age'    => 20
        );

        $this->assertEquals($expected['name'], $person->name);
    }
    public function testPersonHasOne()
    {
        $person = new ActiveRecordSub\Person();
        $person->id = 1;
        $this->assertTrue($person->load());

        $car = $person->car;
        $this->assertEquals('Ivan`s car', $car->name);
    }
    public function testCarBelongsTo()
    {
        $car = new ActiveRecordSub\Car();
        $car->id = 1;
        $this->assertTrue($car->load());
        //var_dump($car);

        $person = $car->person;
        $this->assertEquals('Ivan', $person->name);
    }
    public function test_getParam()
    {
        $person = new ActiveRecordSub\Person();
        $person->id = 1;
        $actual = $person->publicGetParam();

        $expected = array(
            'table' => 'person',
            'field' => array(
                'id',
                'name',
                'surname',
                'age'
            )
        );
        $this->assertEquals($expected, $actual);
    }
    public function testGetParam()
    {
        $actual = ActiveRecordSub\Person::GetParam();
        $expected = array(
            'table' => 'person',
            'field' => array(
                'id',
                'name',
                'surname',
                'age'
            )
        );
        $this->assertEquals($expected, $actual);
        $actual = ActiveRecordSub\Car::GetParam();

        $expected = array(
            'table' => 'car',
            'field' => array(
                'id',
                'name',
                'person_id'
            )
        );
        $this->assertEquals($expected, $actual);
    }
    public function testPersonHasOneJoin()
    {
        $person = new ActiveRecordSub\Person();
        $person->id = 1;
        $person->join('car');
        $this->assertTrue($person->load());

        $car = $person->car;
        $this->assertEquals('Ivan`s car', $car->name);
    }
    public function testPersonBelongsToJoin()
    {
        $car = new ActiveRecordSub\Car();
        $car->id = 1;
        $car->join('person');
        $car->load();

        $person = $car->person;
        $this->assertEquals('Ivan', $person->name);
    }
    public function testComp()
    {
        $comp = new ActiveRecordSub\Comp();
        $comp->id = 1;
        $this->assertTrue($comp->load());
        $this->assertEquals('Ivan`s comp 1', $comp->name);
    }
    public function testPersonHasMany()
    {
        $person = new ActiveRecordSub\Person();
        $person->id = 1;
        $person->load();

        $comps = $person->comps;
        var_dump($comps);
        $this->assertEquals(2, count($comps));

        //var_dump($collection);

        //foreach ($comps as $temp) var_dump($temp);

    }
}