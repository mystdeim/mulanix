<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */

/**
 *
 * @see Mnix\ExceptionSub
 */
require_once 'ExceptionSub.php';

/**
 * @category Mulanix
 */
class Mnix_ExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testSome()
    {
        $e = new Mnix\Exception();
        $this->assertEquals(get_class($e), 'Mnix\Exception');
    }
}