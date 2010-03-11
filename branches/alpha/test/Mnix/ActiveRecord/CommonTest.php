<?php
/**
 * Mulanix Framework
 */
namespace Mnix\ActiveRecord;
/**
 *
 * @see Mnix\CoreSub
 */
require_once '_files/CommonSub.php';

/**
 * @category Mulanix
 */
class CommonTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDriver()
    {
        $obj = new CommonSub();
        //$this->assertEquals('Mnix\Db\Driver', get_class($obj->getDriver()));
    }
     /**
     * @dataProvider providerShielding
     */
    public function testShielding($value, $mode, $bind, $expected)
    {
        $obj = new CommonSub();
        //$bindLocal = $bind;
        $actual = $obj->shielding($value, $mode, $bind);
        $this->assertEquals($expected['string'], $actual);
        $this->assertEquals($expected['bind'], $bind);
    }
    public function providerShielding()
    {
        return array(
            array('a', 'a', array(),
                array('string' => 'table.a', 'bind' => array())),
            array('10', 'i', array(),
                array('string' => ':b0', 'bind' => array(
                    array(':b0', '10', \PDO::PARAM_INT)))),
            array('a', 's', array(),
                array('string' => ':b0', 'bind' => array(
                    array(':b0', 'a', \PDO::PARAM_STR)))),
            array('a', 's', array(array(':b0', 'a', \PDO::PARAM_STR)),
                array('string' => ':b1', 'bind' => array(
                    array(':b0', 'a', \PDO::PARAM_STR),
                    array(':b1', 'a', \PDO::PARAM_STR))))
        );
    }
    /**
     * @dataProvider providerPlaceHolder
     */
    public function testPlaceHolder($condition, $data, $expected)
    {
        $obj = new CommonSub();
        $actual = $obj->placeHolder($condition, $data);
        $this->assertEquals($expected, $actual);
    }
    public function providerPlaceHolder()
    {
        return array(
            array('id = 1', NULL,
                array('query'=>'id = 1', 'bind'=>array())),
            array('?a = 1', array('id'),
                array('query'=>'table.id = 1', 'bind'=>array())),
            array('id = ?i', array('5'),
                array('query'=>'id = :b0', 'bind'=>array(
                    array(':b0', '5', \PDO::PARAM_INT)))),
            array('?a = ?i', array('id', 5),
                array('query'=>'table.id = :b0', 'bind'=>array(
                    array(':b0', 5, \PDO::PARAM_INT)))),
            array('?a = ?s', array('name', 'Ivan'),
                array('query'=>'table.name = :b0', 'bind'=>array(
                    array(':b0', 'Ivan', \PDO::PARAM_STR)))),
            array('?a = ?s AND ?a > ?i', array('name', 'Ivan', 'id', 10),
                array('query'=>'table.name = :b0 AND table.id > :b1', 'bind'=>array(
                    array(':b0', 'Ivan', \PDO::PARAM_STR),
                    array(':b1', 10, \PDO::PARAM_INT))))
        );
    }
}