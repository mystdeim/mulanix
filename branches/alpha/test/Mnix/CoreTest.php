<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */
namespace Mnix;
/**
 *
 * @see Mnix\CoreSub
 */
require_once '_files/CoreSub.php';

/**
 * @category Mulanix
 */
class CoreTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Стираем статический объект после каждого теста
     */
    protected function tearDown()
    {
        CoreSub::clearInstance();
    }
    public function testGetSet()
    {
        $this->assertEquals(CoreSub::instance()->_log, null);
        CoreSub::instance()->_log = 'test';
        $this->assertEquals(CoreSub::instance()->_log, 'test');
        CoreSub::instance()->_log = null;
    }
    public function testConstructor()
    {
        $obj = new CoreSub();
        $this->assertEquals('Mnix\CoreSub', get_class($obj));

        $this->assertEquals('Mnix\CoreSub', get_class(CoreSub::instance()));
        $this->assertEquals('Mnix\CoreSub', get_class(Core::instance()));

        CoreSub::clearInstance();

    }
    public function testTime()
    {
        $obj = CoreSub::instance();
        $t = explode('|', $obj->getTime());
        
        $this->assertGreaterThanOrEqual(0.0, (float)$t);
        $this->assertRegExp("/^\d.*\d$/", $obj->getTime());
        $this->assertLessThanOrEqual(microtime(true), Core\STARTTIME);
    }
    public function testPutLogTime()
    {
        $obj = CoreSub::instance();
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
        $obj = CoreSub::instance();
        $this->assertEquals(count($obj->_time), 1);
        CoreSub::logTime('test');
        $this->assertEquals(count($obj->_time), 2);
    }
    public function testPutLogCount()
    {
        $obj = CoreSub::instance();
        $obj->putLogCount('test');
        $this->assertEquals($obj->_count['test'], 1);
        $this->assertEquals(count($obj->_count), 7);
        $obj->putLogCount('test');
        $this->assertEquals($obj->_count['test'], 2);
        $obj->putLogCount('test', 10);
        $this->assertEquals($obj->_count['test'], 12);

        //Неправильный второй параметр
        $this->assertNull($obj->_log);
        $obj->putLogCount('core_cls', 's');
        $this->assertRegExp("/^w.*\n$/s", $obj->_log);

        //Счетчик может содержать только целые значения
        $this->assertContainsOnly('int', $obj->_count);
    }
    public function testLogCount()
    {
        $obj = CoreSub::instance();
        CoreSub::logCount('test');
        $this->assertEquals($obj->_count['test'], 1);
    }
    /**
     *  providerCreateNote
     */
    /*public function testCreateNote($status, $note, $trace, $start, $end)
    {
        $this->assertRegExp("/^$start.*$end\n$/s", CoreSub::instance()->createNote($status, $note, $trace));
    }
    public function providerCreateNote()
    {
        return array(
            array('s', 'Test message', false, 's', 'Mnix_CoreTest->testCreateNote~Test message'),
            array('w', 'Warning message', false, 'w', 'Mnix_CoreTest->testCreateNote~Warning message'),
            array('e', 'Error message', false, 'e', 'Mnix_CoreTest->testCreateNote~Error message')
        );
    }*/
    public function testCreateNoteOther()
    {
        $this->assertFalse(CoreSub::instance()->createNote('a', 'dfdf', false));
        $this->assertRegExp("{^s~.*PHPUnit_Framework.*}s", CoreSub::instance()->createNote('s', 'Test message', true));
    }
    /*public function testPutLog()
    {
        $obj = CoreSub::instance();
        $this->assertEquals($obj->_log, null);
        $obj->putLog('s', 'Test message');
        $this->assertStringEndsWith("Mnix_CoreTest->testPutLog~Test message\n", $obj->_log);
        $this->assertStringStartsWith('s~', $obj->_log);

        CoreSub::clearInstance();
        $obj = CoreSub::instance();
        $this->assertEquals($obj->_log, null);
        $obj->putLog('w', 'Test message');
        $this->assertStringStartsWith('w~', $obj->_log);

        CoreSub::clearInstance();
        $obj = CoreSub::instance();
        $this->assertEquals($obj->_log, null);
        $obj->putLog('e', 'Test message');
        $this->assertStringStartsWith('e~', $obj->_log);

        CoreSub::clearInstance();
        $obj = CoreSub::instance();
        $this->assertEquals($obj->_log, null);
        $obj->putLog('a', 'Test message', true);
        $this->assertStringStartsWith('w~', $obj->_log);
    }*/
    public function testLog()
    {
        $this->assertEquals(CoreSub::instance()->_log, null);
        CoreSub::log('s', 'Test message', false);
        $this->assertStringStartsWith('s~', CoreSub::instance()->_log);
    }
    /**
     * @dataProvider providerGetPath
     */
    public function testGetPath($class, $result)
    {
        $this->assertEquals(CoreSub::instance()->getPath($class), $result);
    }
    public function providerGetPath()
    {
        return array(
            array('Mnix', Path\LIB . '/Mnix.php'),
            array('Mnix\Core', Path\LIB . '/Mnix/Core.php'),
            array('Mnix\Core\Controller', Path\LIB . '/Mnix/Core/Controller.php')
        );
    }
    /*public function testAutoloadRegister()
    {
        $obj = CoreSub::instance();
        $this->assertEquals(0, $obj->_count['core_cls']);
        $b = new Uri();
        $this->assertStringEndsWith("Mnix_CoreTest->testAutoloadRegister~Load class: Mnix\Uri\n", $obj->_log);
        $this->assertStringStartsWith('s~', $obj->_log);
        $this->assertEquals(1, $obj->_count['core_cls']);
        $e = false;
        try {
            $c = new FUCKING_CLASS();
        } catch (Exception $e) {
        }
        $this->assertType('Mnix\Exception', $e);
    }*/
    public function testRunFinish()
    {
        $obj = CoreSub::instance();
        $obj->run();
        $this->assertTrue($obj->_crash);
        $this->assertEquals(get_class(CoreSub::instance()), 'Mnix\CoreSub');
        $obj->__destruct();
        $obj = CoreSub::instance();
        $obj->run();
        $obj->finish();
        $this->assertFalse($obj->_crash);
    }
    public function testDebugFinish()
    {

        $obj = CoreSub::instance();
        $obj->run();
        $obj->debugFinish();
        $this->assertTrue($obj->_crash);
        $this->assertStringEndsWith("Accident finishing\n", $obj->_log);
        $obj->_crash = false;
        CoreSub::clearInstance();
        $obj = CoreSub::instance();
        $obj->debugFinish();
        $this->assertStringEndsWith("Normal finishing\n", $obj->_log);
    }
}