<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */
namespace Mnix;
/**
 *
 * @see Mnix\ExceptionSub
 */
require_once '_files/ExceptionSub.php';

/**
 * @category Mulanix
 */
class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testSome()
    {
        $e = new Exception();
        $this->assertEquals(get_class($e), 'Mnix\Exception');
    }
}