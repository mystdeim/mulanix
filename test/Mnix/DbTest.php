<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */

require_once '_files/DbSub.php';

/**
 * @category Mulanix
 */
class Mnix_DbTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mnix\DbSub::clearInstance();
    }
    public function testConnect()
    {
        $this->assertNull(Mnix\DbSub::instances());
        
        $obj = Mnix\Db::connect('base1');
        $this->assertEquals('Mnix\Db', get_class($obj));

        $obj = Mnix\DbSub::connect('base0');
        $this->assertEquals('Mnix\DbSub', get_class($obj));
        $this->assertEquals('Mnix\Db\Driver\Xml', get_class($obj->_driver));
        $this->assertEquals(2, count(\Mnix\DbSub::instances()));

        //Подключаемся к базе, у которой ошибка в типе
        try {
            $obj = Mnix\DbSub::connect('base2');
            $this->fail();
        } catch (\Mnix\Exception $e) {
            $this->assertEquals('Mnix\Exception', get_class($e));
        }

        //Пишем базу, которой не существует в конфиге
        try {
            $obj = Mnix\DbSub::connect('unExistBase');
            $this->fail();
        } catch (\Mnix\Exception $e) {
            $this->assertEquals('Mnix\Exception', get_class($e));
        }

        unset($obj);
        
        $obj = Mnix\DbSub::connect('base1');
        $this->assertEquals('Mnix\Db', get_class($obj));
        $this->assertEquals(2, count(\Mnix\DbSub::instances()));
    }
    public function testQueryObjects()
    {
       $db = Mnix\Db::connect('base1');
       $this->assertEquals('Mnix\Db\Sql\Select', get_class($db->select()));

       $db = Mnix\Db::connect('base0');
       $this->assertEquals('Mnix\Db\Xml\Select', get_class($db->select()));
    }
}