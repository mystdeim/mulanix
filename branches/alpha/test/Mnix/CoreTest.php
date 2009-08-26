<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Core
 * @subpackage Test
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */
require_once 'SubCore.php';
/**
 * @category Mulanix
 * @package Mnix_Core
 * @subpackage Test
 */
class Mnix_CoreTest extends PHPUnit_Framework_TestCase
{
    protected $_fixture;

    protected function setUp()
    {
        $this->_fixture = new Mnix_SubCore;
    }

    protected function tearDown()
    {
        unset($this->_fixture);
    }

    public function testTTT()
    {
        $this->assertEquals(1, 1);
    }

    /**
     * @dataProvider provider_getPath
     */
    public function test_getPath($class, $result)
    {
        $this->assertEquals($this->_fixture->getPath($class), $result);
    }
    public function provider_getPath()
    {
        return array(
            array('Mnix', MNIX_PATH_LIB . 'Mnix.php'),
            array('Mnix_Core', MNIX_PATH_LIB . 'Mnix/Core.php'),
            array('Mnix_Core_Controller', MNIX_PATH_LIB . 'Mnix/Core/Controller.php')
        );
    }
}