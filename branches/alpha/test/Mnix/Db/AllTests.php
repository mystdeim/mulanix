<?php
/**
 * Mulanix Framework
 */
namespace Mnix\Db;

require_once 'Driver/AllTests.php';
require_once 'DriverTest.php';
require_once 'CriteriaTest.php';
require_once 'SelectTest.php';
/**
 * Mulanix Framework
 *
 * @author deim
 */
class AllTests
{
    public static function suite()
    {
        $suite = new \PHPUnit_Framework_TestSuite('Mulanix\Db');

        $suite->addTest(\Mnix\Db\Driver\AllTests::suite());
        $suite->addTestSuite('\Mnix\Db\DriverTest');
        $suite->addTestSuite('\Mnix\Db\CriteriaTest');
        $suite->addTestSuite('\Mnix\Db\SelectTest');

        return $suite;
    }
}