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
 /**
 * @see Helper
 */
require_once dirname(dirname(dirname(__FILE__))) . '/Helper.php';
 /**
 * @see Mnix_SelectSub
 */
require_once 'SelectSub.php';
/**
 * @category Mulanix
 * @package Mnix_Db
 * @subpackage Test
 */
class Mnix_Db_SelectTest extends PHPUnit_Framework_TestCase
{
    public function testConnect()
    {
        $this->assertEquals(null, null);
    }
}