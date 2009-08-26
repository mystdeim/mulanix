<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix
 * @subpackage Test
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */

require_once 'Helper.php';

/**
 * @category Mulanix
 * @package Mnix_Db
 * @subpackage Test
 */
class Mnix_HelperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerAutoloadPath
     */
    public function testAutoloadPath($class, $result)
    {
        $this->assertEquals(autoloadPath($class), $result);
    }
    public function providerAutoloadPath()
    {
        return array(
            array('Mnix_Core', MNIX_PATH_LIB . 'Mnix/Core.php'),
            array('Mnix_CoreTest', MNIX_PATH_TEST . 'Mnix/CoreTest.php'),
            array('Mnix_CoreSub', MNIX_PATH_TEST . 'Mnix/CoreSub.php')
        );
    }
}