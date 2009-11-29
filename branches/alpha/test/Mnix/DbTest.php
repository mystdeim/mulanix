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
    public function testConstructor()
    {
        $obj = new \Mnix\DbSub('test');
        $this->assertEquals('Mnix\DbSub', get_class($obj));
        $this->assertEquals('test', $obj->_param);
    }
    public function testConnect()
    {
        $this->assertNull(Mnix\DbSub::instance());
        $param = array( 'type'  => 'MySql',
                        'login' => 'user',
                        'pass'  => 'pass',
                        'host'  => 'localhost',
                        'base'  => 'database');
        $obj = Mnix\DbSub::connect($param);
        $this->assertEquals('Mnix\Db', get_class($obj));
        $instance_arr = array('MySql' => array($obj));
        $this->assertEquals($instance_arr, Mnix\DbSub::instance());
    }
}