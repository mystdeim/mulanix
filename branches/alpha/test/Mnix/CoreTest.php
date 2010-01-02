<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */

/**
 *
 * @see Mnix_CoreSub
 */
require_once 'CoreSub.php';

/**
 * @category Mulanix
 */
class Mnix_CoreTest extends PHPUnit_Framework_TestCase
{
    /**
     * Стираем статический объект после каждого теста
     */
    protected function tearDown()
    {
        \Mnix\CoreSub::clearInstance();
    }
    public function testGetSet()
    {
        $this->assertEquals(Mnix\CoreSub::instance()->_log, null);
        Mnix\CoreSub::instance()->_log = 'test';
        $this->assertEquals(Mnix\CoreSub::instance()->_log, 'test');
        Mnix\CoreSub::instance()->_log = null;
    }
    public function testConstructor()
    {
        $obj = new Mnix\CoreSub();
        $this->assertEquals('Mnix\CoreSub', get_class($obj));

        $this->assertEquals('Mnix\CoreSub', get_class(Mnix\CoreSub::instance()));
        $this->assertEquals('Mnix\CoreSub', get_class(Mnix\Core::instance()));

        \Mnix\CoreSub::clearInstance();

    }
    public function testTime()
    {
        $obj = Mnix\CoreSub::instance();
        $t = explode('|', $obj->getTime());
        
        $this->assertGreaterThanOrEqual(0.0, (float)$t);
        $this->assertRegExp("/^\d.*\d$/", $obj->getTime());
        $this->assertLessThanOrEqual(microtime(true), \Mnix\Core\STARTTIME);
    }
    public function testPutLogTime()
    {
        $obj = Mnix\CoreSub::instance();
        $this->assertEquals(0.0, $obj->_time['db']);
        $this->assertEquals(count($obj->_time), 1);
        $obj->putLogTime('test');
        $this->assertEquals(count($obj->_time), 2);
        $this->assertLessThanOrEqual(microtime(true), $obj->_time['test']['start']);
        $this->assertNull($obj->_log);
        $obj->putLogTime('test', true);
        $this->assertLessThanOrEqual(1.0, $obj->_time['test']['time']);
        $this->assertNull($obj->_log);
        $obj->putLogTime('test1', true);
        $this->assertEquals(count($obj->_time), 2);
        $this->assertRegExp("/^w.*\n$/s", $obj->_log);
        $obj->putLogTime('test1');
        $this->assertEquals(count($obj->_time), 3);
    }
    public function testLogTime()
    {
        $obj = Mnix\CoreSub::instance();
        $this->assertEquals(count($obj->_time), 1);
        Mnix\CoreSub::logTime('test');
        $this->assertEquals(count($obj->_time), 2);
    }
    public function testPutLogCount()
    {
        $obj = Mnix\CoreSub::instance();
        $obj->putLogCount('test');
        $this->assertEquals($obj->_count['test'], 1);
        $this->assertEquals(count($obj->_count), 7);
        $obj->putLogCount('test');
        $this->assertEquals($obj->_count['test'], 2);
        $obj->putLogCount('test', 10);
        $this->assertEquals($obj->_count['test'], 12);

        //Запуск без параметра
        try {
            $obj->putLogCount();
        } catch (Exception $e) {
            $this->assertEquals(2, $e->getCode());
        }

        //Неправильный второй параметр
        $this->assertNull($obj->_log);
        $obj->putLogCount('core_cls', 's');
        $this->assertRegExp("/^w.*\n$/s", $obj->_log);

        //Счетчик может содержать только целые значения
        $this->assertContainsOnly('int', $obj->_count);
    }
    public function testLogCount()
    {
        $obj = Mnix\CoreSub::instance();
        Mnix\CoreSub::logCount('test');
        $this->assertEquals($obj->_count['test'], 1);
    }
    /**
     * @dataProvider providerCreateNote
     */
    public function testCreateNote($status, $note, $trace, $start, $end)
    {
        $this->assertRegExp("/^$start.*$end\n$/s", Mnix\CoreSub::instance()->createNote($status, $note, $trace));
    }
    public function providerCreateNote()
    {
        return array(
            array('s', 'Test message', false, 's', 'Mnix_CoreTest->testCreateNote~Test message'),
            array('w', 'Warning message', false, 'w', 'Mnix_CoreTest->testCreateNote~Warning message'),
            array('e', 'Error message', false, 'e', 'Mnix_CoreTest->testCreateNote~Error message')
        );
    }
    public function testCreateNoteOther()
    {
        $this->assertFalse(Mnix\CoreSub::instance()->createNote('a', 'dfdf', false));
        $this->assertRegExp("{^s~.*PHPUnit_Framework.*}s", Mnix\CoreSub::instance()->createNote('s', 'Test message', true));
    }
    public function testPutLog()
    {
        $obj = Mnix\CoreSub::instance();
        $this->assertEquals($obj->_log, null);
        $obj->putLog('s', 'Test message');
        $this->assertStringEndsWith("Mnix_CoreTest->testPutLog~Test message\n", $obj->_log);
        $this->assertStringStartsWith('s~', $obj->_log);

        Mnix\CoreSub::clearInstance();
        $obj = Mnix\CoreSub::instance();
        $this->assertEquals($obj->_log, null);
        $obj->putLog('w', 'Test message');
        $this->assertStringStartsWith('w~', $obj->_log);

        Mnix\CoreSub::clearInstance();
        $obj = Mnix\CoreSub::instance();
        $this->assertEquals($obj->_log, null);
        $obj->putLog('e', 'Test message');
        $this->assertStringStartsWith('e~', $obj->_log);

        Mnix\CoreSub::clearInstance();
        $obj = Mnix\CoreSub::instance();
        $this->assertEquals($obj->_log, null);
        $obj->putLog('a', 'Test message', true);
        $this->assertStringStartsWith('w~', $obj->_log);
    }
    public function testLog()
    {
        $this->assertEquals(Mnix\CoreSub::instance()->_log, null);
        Mnix\CoreSub::log('s', 'Test message', false);
        $this->assertStringStartsWith('s~', Mnix\CoreSub::instance()->_log);
    }
    /**
     * @dataProvider providerGetPath
     */
    public function testGetPath($class, $result)
    {
        $this->assertEquals(Mnix\CoreSub::instance()->getPath($class), $result);
    }
    public function providerGetPath()
    {
        return array(
            array('Mnix', Mnix\Path\LIB . '/Mnix.php'),
            array('Mnix\Core', Mnix\Path\LIB . '/Mnix/Core.php'),
            array('Mnix\Core\Controller', Mnix\Path\LIB . '/Mnix/Core/Controller.php')
        );
    }
    public function testAutoloadRegister()
    {
        $obj = Mnix\CoreSub::instance();
        $this->assertEquals(0, $obj->_count['core_cls']);
        $b = new Mnix\Uri();
        $this->assertStringEndsWith("Mnix_CoreTest->testAutoloadRegister~Load class: Mnix\Uri\n", $obj->_log);
        $this->assertStringStartsWith('s~', $obj->_log);
        $this->assertEquals(1, $obj->_count['core_cls']);
        $e = false;
        try {
            $c = new FUCKING_CLASS();
        } catch (Mnix\Exception $e) {
        }
        $this->assertType('Mnix\Exception', $e);
    }
    public function testRunFinish()
    {
        $obj = Mnix\CoreSub::instance();
        $obj->run();
        $this->assertTrue($obj->_crash);
        $this->assertEquals(get_class(Mnix\CoreSub::instance()), 'Mnix\CoreSub');
        $obj->__destruct();
        $obj = Mnix\CoreSub::instance();
        $obj->run();
        $obj->finish();
        $this->assertFalse($obj->_crash);
    }
    public function testDebugFinish()
    {

        $obj = Mnix\CoreSub::instance();
        $obj->run();
        $obj->debugFinish();
        $this->assertTrue($obj->_crash);
        $this->assertStringEndsWith("Accident finishing\n", $obj->_log);
        $obj->_crash = false;
        Mnix\CoreSub::clearInstance();
        $obj = Mnix\CoreSub::instance();
        $obj->debugFinish();
        $this->assertStringEndsWith("Normal finishing\n", $obj->_log);
    }
}