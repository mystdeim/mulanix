<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */

require_once 'DbSub.php';

/**
 * @category Mulanix
 */
class Mnix_DbTest extends PHPUnit_Framework_TestCase
{
    public function testConnect()
    {
        $this->assertNull(Mnix\DbSub::instances());
        $obj = Mnix\Db::connect('base1');
        $this->assertEquals('Mnix\Db', get_class($obj));
        $obj = Mnix\DbSub::connect('base0');
        $this->assertEquals('Mnix\DbSub', get_class($obj));
        $this->assertEquals('Mnix\Db\Driver\Xml', get_class($obj->_driver));
        $this->assertEquals(2, count(\Mnix\DbSub::instances()));
    }
    public function testXmlQuery()
    {
        $obj = Mnix\DbSub::connect('base0');

    }
}