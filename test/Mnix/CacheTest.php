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
        /*$obj = new Mnix_CacheSub();
        $obj->removeDir(MNIX_PATH_CACHE);*/
    }
    public function testConstructor()
    {
        $obj = new Mnix\CacheSub();
        $this->assertEquals(get_class($obj), 'Mnix\CacheSub');
        $this->assertEquals(\Mnix\Path\CACHE . 'Mnix/CacheTest', $obj->_dir);
        unset($obj);
        $obj = new Mnix\CacheSub('/test');
        $this->assertEquals(\Mnix\Path\CACHE . 'test', $obj->_dir);
        unset($obj);
        $obj = new Mnix\CacheSub('test');
        $this->assertEquals(\Mnix\Path\CACHE . 'Mnix/CacheTest/test', $obj->_dir);
    }
    public function testDir()
    {
        $obj = new Mnix\CacheSub();
        $this->assertEquals(\Mnix\Path\CACHE . 'Mnix/CacheTest', $obj->_dir);
        unset($obj);
        $obj = new Mnix\CacheSub();
        $obj->dir('/test');
        $this->assertEquals('test', $obj->dir());
        unset($obj);
        $obj = new Mnix\CacheSub();
        $obj->dir('test');
        $this->assertEquals('Mnix/CacheTest/test', $obj->dir());
        $this->assertEquals(get_class($obj->dir('/dsd')), 'Mnix\CacheSub');
        try {
            $obj->dir(1);
        } catch(\Mnix\Exception $e) {
            $this->assertEquals('Mnix\Exception', get_class($e));
        }
        unset($obj);
        $obj = new Mnix\CacheSub();
        $obj->dir('/');
        $this->assertEquals(\Mnix\Path\CACHE, $obj->_dir);
    }
    public function testName()
    {
        $obj = new Mnix\CacheSub();
        $this->assertEquals($obj->_name, null);
        $obj->name('name1');
        $this->assertEquals('Mnix\CacheSub', get_class($obj->name('name')));
        $this->assertEquals($obj->_name, 'name');
        $this->assertEquals($obj->name(), 'name');
        try {
            $obj->name(1);
        } catch(\Mnix\Exception $e) {
            $this->assertEquals('Mnix\Exception', get_class($e));
        }
    }
    public function testHash()
    {
        $obj = new Mnix\CacheSub();
        $this->assertEquals($obj->_hash, false);
        $this->assertEquals($obj->_hash, false);
        $obj->hash(true);
        $this->assertEquals($obj->hash(), true);
        try {
            $obj->hash('sdsd');
        } catch(\Mnix\Exception $e) {
            $this->assertEquals('Mnix\Exception', get_class($e));
        }
        $this->assertEquals('Mnix\CacheSub', get_class($obj->hash(false)));
    }
    public function testData()
    {
        $obj = new Mnix\CacheSub();
        $this->assertEquals($obj->_data, null);
        $this->assertEquals('Mnix\CacheSub', get_class($obj->data('string')));
        $this->assertEquals(serialize('string'), $obj->_data);
        $this->assertEquals('string', $obj->data());
    }
    public function testMkdir()
    {
        $obj = new \Mnix\CacheSub();
        $obj->mkdir();
        $this->assertFileExists(\Mnix\Path\CACHE);
        $this->assertFileExists(\Mnix\Path\CACHE . 'Mnix/CacheTest');
    }
    public function testClear()
    {
        $obj = new \Mnix\CacheSub();
        $obj->mkdir();
        $obj->clear();
    }
}