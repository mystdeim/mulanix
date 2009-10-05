<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Cache
 * @subpackage Test
 * @version $Id: CoreTest.php 102 2009-09-29 12:56:35Z mystdeim $
 * @author mystdeim <mysteim@gmail.com>
 */

/**
 * @see Mnix_CacheSub
 */
require_once 'CacheSub.php';

/**
 * @category Mulanix
 * @package Mnix_Cache
 * @subpackage Test
 */
class Mnix_CacheTest extends PHPUnit_Framework_TestCase
{
    /**
     * Удаляем каждый раз кэш
     */
    protected function tearDown()
    {
        $obj = new Mnix_CacheSub();
        $obj->removeDir(MNIX_PATH_CACHE);
    }
    public function testConstructor()
    {
        $obj = new Mnix_CacheSub();
        $this->assertEquals(MNIX_PATH_CACHE . 'Mnix/CacheTest', $obj->_path);
    }
    /*public function testMkdir()
    {
        $obj = new Mnix_CacheSub();
        $this->assertFileExists(MNIX_PATH_CACHE);
    }*/
}