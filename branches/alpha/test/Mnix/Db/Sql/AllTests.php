<?php
/**
 * Mulanix Framework
 */
require_once 'SqlTest.php';
require_once 'MySqlTest.php';
/**
 * Mulanix Framework
 *
 * @author deim
 */
class Mnix_Db_Driver_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Mulanix - Db - Driver');

        $suite->addTestSuite('Mnix_Db_Driver_SqlTest');
        $suite->addTestSuite('Mnix_Db_Driver_MySqlTest');

        return $suite;
    }
}