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
require_once 'CoreTest.php';
require_once 'DbTest.php';
/**
 * @category Mulanix
 * @package Mnix
 * @subpackage Test
 */
class AllTest
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Mnix');

        $suite->addTestSuite('Mnix_CoreTest');
        $suite->addTestSuite('Mnix_DbTest');

        return $suite;
    }
}