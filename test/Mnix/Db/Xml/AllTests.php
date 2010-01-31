<?php
/*
 * Mulanix Framework
 */
require_once 'SelectTest.php';
require_once 'DeleteTest.php';
/*
 * Mulanix Framework
 */
class Package_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Mulanix - Db - Xml');

        $suite->addTestSuite('Mnix_Db_Xml_SelectTest');
        $suite->addTestSuite('Mnix_Db_Xml_DeleteTest');

        return $suite;
    }
}