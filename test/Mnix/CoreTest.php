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
 
require_once dirname(dirname(__FILE__)) . '/Helper.php';

require_once 'CoreSub.php';

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
        $this->_fixture = new Mnix_CoreSub;
    }

    protected function tearDown()
    {
        unset($this->_fixture);
    }

    /**
     * @dataProvider providerGetPath
     */
    public function testGetPath($class, $result)
    {
        $this->assertEquals($this->_fixture->getPath($class), $result);
    }
    public function providerGetPath()
    {
        return array(
            array('Mnix', MNIX_PATH_LIB . 'Mnix.php'),
            array('Mnix_Core', MNIX_PATH_LIB . 'Mnix/Core.php'),
            array('Mnix_Core_Controller', MNIX_PATH_LIB . 'Mnix/Core/Controller.php')
        );
    }
}