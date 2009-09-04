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
/**
 * @see Helper
 */
require_once dirname(dirname(__FILE__)) . '/Helper.php';
/**
 * @see Mnix_CoreTest
 */
require_once 'CoreTest.php';
/**
 * @see Mnix_DbTest
 */
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