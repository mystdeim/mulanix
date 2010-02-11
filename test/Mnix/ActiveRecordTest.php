<?php
/**
 * Mulanix Framework
 */
namespace Mnix;

require_once '_files/ActiveRecordSub.php';
/**
 * Mulanix Framework
 *
 * @author deim
 */
class ActiveRecordTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $obj = new ActiveRecordSub();
        $this->assertEquals('Mnix\ActiveRecordSub', get_class($obj));
        $this->assertFalse($obj->_isLoad);
        $this->assertNull($obj->_select);
        $this->assertEquals(array(), $obj->_cortege);
    }
}