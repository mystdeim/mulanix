<?php
/**
 * Mulanix Framework
 */
namespace Mnix\Db\Driver;

require_once 'StatementTest.php';

/**
 * Mulanix Framework
 *
 * @author deim
 */
class AllTests
{
    public static function suite()
    {
        $suite = new \PHPUnit_Framework_TestSuite('Mulanix\Db\Driver');

        $suite->addTestSuite('\Mnix\Db\Driver\StatementTest');

        return $suite;
    }
}