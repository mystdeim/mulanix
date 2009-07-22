<?php
/**
 * Description of CacheTest
 *
 * @author deim
 * Created on 14.04.2009, 22:05:36
 */
class Test_Mnix_CacheTest extends PHPUnit_Framework_TestCase
{
    /**
     * Вариант кэширование 1
     *
     * имя     - строка
     * контент - строка
     *
     */
    public function testSituation1()
    {
        $content = 'TESTCONTENT';
        $name = 'testname';
        $cache1 = new Mnix_Cache(__FILE__);
        $cache1->name($name)
                ->put($content)
                ->save();
        $this->assertEquals(true, file_exists(MNIX_CACHE.'test/lib/CacheTest/testname'));
        $cache2 = new Mnix_Cache(__FILE__);
        $cache2->name($name)
                ->load();
        $this->assertEquals($content, $cache2->get());
        $cache2->remove();
        $this->assertEquals(false, file_exists(MNIX_CACHE.'test/lib/CacheTest/testname'));
    }
    /**
     * Вариант кэширование 2
     * 
     * имя     - хэшированно от строки имени
     * контент - строка
     * 
     */
    public function testSituation2()
    {
        $content = 'TESTCONTENT';
        $name = 'testname';
        $cache1 = new Mnix_Cache(__FILE__);
        $cache1->name($name)
                ->hash('n')
                ->put($content)
                ->save();
        $this->assertEquals(true, file_exists(MNIX_CACHE.'test/lib/CacheTest/'.md5('testname')));
        $cache2 = new Mnix_Cache(__FILE__);
        $cache2->name($name)
                ->hash('n')
                ->load();
        $this->assertEquals($content, $cache2->get());
        $cache2->remove();
        $this->assertEquals(false, file_exists(MNIX_CACHE.'test/lib/CacheTest/'.md5('testname')));
    }
    /**
     * Вариант кэширование 3
     *
     * имя     - хэшированно от содержимого файла
     * контент - файл(хэширован)
     *
     */
    public function testSituation3()
    {
        $content = 'TESTCONTENT2';
        $name = 'testname2';
        $cache1 = new Mnix_Cache(__FILE__);
        $cache1->file(MNIX_BOOT . 'config.xml')
                ->put($content)
                ->hash('f')
                ->save();
        $this->assertEquals(true, file_exists(MNIX_CACHE.'test/lib/CacheTest/'.md5_file(MNIX_BOOT . 'config.xml')));
        $cache2 = new Mnix_Cache(__FILE__);
        $cache2->file(MNIX_BOOT . 'config.xml')
                ->hash('f')
                ->load();
        $this->assertEquals($content, $cache2->get());
        $cache2->remove();
        $this->assertEquals(false, file_exists(MNIX_CACHE.'test/lib/CacheTest/'.md5_file(MNIX_BOOT . 'config.xml')));
    }
}