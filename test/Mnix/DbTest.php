<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Db
 * @subpackage Test
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */

require_once dirname(__FILE__) . '/Helper.php';

/**
 * @category Mulanix
 * @package Mnix_Db
 * @subpackage Test
 */
class Mnix_DbTest extends PHPUnit_Framework_TestCase
{
    public function testConnect()
    {
        define('MNIX_DB_BASE_TYPE','MySql',true);
        define('MNIX_DB_BASE_LOGIN','testuser',true);
        define('MNIX_DB_BASE_PASS','12345',true);
        define('MNIX_DB_BASE_HOST','localhost',true);
        define('MNIX_DB_BASE_BASE','cmf1',true);

        $db = Mnix_DbSub::connect();

        $this->assertEquals($db->getParam(), array(
                'type' => MNIX_DB_BASE_TYPE,
                'login' => MNIX_DB_BASE_LOGIN,
                'pass' => MNIX_DB_BASE_PASS,
                'host' => MNIX_DB_BASE_HOST,
                'base' => MNIX_DB_BASE_BASE
            ));
        $this->assertEquals($db->getCon(), null);
    }
}