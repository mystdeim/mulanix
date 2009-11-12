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
    public function testConstructor()
    {
        $obj = new Mnix\CacheSub();
        $this->assertEquals('Mnix\CacheSub', get_class($obj));
        $this->assertEquals(\Mnix\Path\CACHE . '/Mnix/CacheTest', $obj->_dir);
        unset($obj);
        $obj = new Mnix\CacheSub('/test');
        $this->assertEquals(\Mnix\Path\CACHE . '/test', $obj->_dir);
        unset($obj);
        $obj = new Mnix\CacheSub('test');
        $this->assertEquals(\Mnix\Path\CACHE . '/Mnix/CacheTest/test', $obj->_dir);
    }
    public function testDir()
    {
        $obj = new Mnix\CacheSub();
        $this->assertEquals(\Mnix\Path\CACHE . '/Mnix/CacheTest', $obj->_dir);
        unset($obj);
        $obj = new Mnix\CacheSub();
        $obj->dir('/test');
        $this->assertEquals('/test', $obj->dir());
        unset($obj);
        $obj = new Mnix\CacheSub();
        $obj->dir('test');
        $this->assertEquals('/Mnix/CacheTest/test', $obj->dir());
        $this->assertEquals('Mnix\CacheSub', get_class($obj->dir('/dsd')));
        try {
            $obj->dir(1);
        } catch(\Mnix\Exception $e) {
            $this->assertEquals('Mnix\Exception', get_class($e));
        }
        unset($obj);
        $obj = new Mnix\CacheSub();
        $obj->dir('/');
        $this->assertEquals(\Mnix\Path\CACHE . '/', $obj->_dir);
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
        $this->assertFileExists(\Mnix\Path\CACHE . '/Mnix/CacheTest');
    }
    public function testClear()
    {
        $obj = new \Mnix\CacheSub();
        $obj->mkdir();
        $this->assertFileExists(\Mnix\Path\CACHE . '/Mnix/CacheTest');
        $this->assertEquals('Mnix\CacheSub', get_class($obj->clear()));
        $this->assertFileExists(\Mnix\Path\CACHE . '/Mnix');
        $this->assertFileNotExists(\Mnix\Path\CACHE . '/Mnix/CacheTest');
        $obj->dir('/');
        $obj->clear();
        $this->assertFileNotExists(\Mnix\Path\CACHE);
    }
    public function testSave()
    {
        $obj = new \Mnix\CacheSub();
        $obj->name('name1')
            ->data('data');
        $this->assertEquals('Mnix\CacheSub', get_class($obj->save()));
        $this->assertFileExists(\Mnix\Path\CACHE . '/Mnix/CacheTest/name1');

        $obj->dir('sub')
            ->name('name2')
            ->data(true);
        $this->assertEquals('Mnix\CacheSub', get_class($obj->save()));
        $this->assertFileExists(\Mnix\Path\CACHE . '/Mnix/CacheTest/sub/name2');

        try {
            unset($obj);
            $obj = new \Mnix\CacheSub();
            $obj->save();
        } catch(\Mnix\Exception $e) {
            $this->assertEquals('Mnix\Exception', get_class($e));
        }
    }
    public function testLoad()
    {
        $obj = new \Mnix\CacheSub();
        $obj->name('name');
        $this->assertEquals(false, $obj->load());

        $obj->name('name1');
        $this->assertEquals(true, $obj->load());
        $this->assertEquals('data', $obj->data());

        $obj->name('sub/name2');
        $this->assertEquals(true, $obj->load());
        $this->assertEquals(true, $obj->data());

        $obj->dir('/Mnix/CacheTest')
            ->clear();
        $this->assertFileNotExists(\Mnix\Path\CACHE . '/Mnix/CacheTest');
        $obj->dir('/')
            ->clear();
        $this->assertFileNotExists(\Mnix\Path\CACHE);

        try {
            unset($obj);
            $obj = new \Mnix\CacheSub();
            $obj->load();
        } catch(\Mnix\Exception $e) {
            $this->assertEquals('Mnix\Exception', get_class($e));
        }
    }
}