<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Core
 * @subpackage Test
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */

 /**
 * @see Helper
 */
require_once dirname(dirname(__FILE__)) . '/Helper.php';

/**
 * @see Mnix_CoreSub
 */
require_once 'CoreSub.php';

/**
 * @category Mulanix
 * @package Mnix_Core
 * @subpackage Test
 */
class Mnix_CoreTest extends PHPUnit_Framework_TestCase
{
    /**
     * Стираем статический объект после каждого теста
     */
    protected function tearDown()
    {
        Mnix_CoreSub::clearInstance();
    }
    /**
     * Set & Get
     */
    public function testGetSet()
    {
        $this->assertEquals(Mnix_CoreSub::instance()->_log, null);
        Mnix_CoreSub::instance()->_log = 'test';
        $this->assertEquals(Mnix_CoreSub::instance()->_log, 'test');
        Mnix_CoreSub::instance()->_log = null;
    }
    /**
     * Тестируем автозагрузку
     */
    public function testAutoload()
    {
        $obj = Mnix_CoreSub::instance();
        $obj->autoload('Mnix_Core');
        try {
            $obj->autoload('Class');
        } catch (Mnix_Exception_Fatal $e) {
            $this->assertType('Mnix_Exception_Fatal', $e);
        }
    }
    /**
     * Тестируем запись сообщений в лог
     *
     * @dataProvider providerCreateNote
     */
    public function testCreateNote($status, $note, $trace, $start, $end)
    {
        $this->assertRegExp("/^$start.*$end\n$/s", Mnix_CoreSub::instance()->createNote($status, $note, $trace));
    }
    /**
     * Провайдер для testCreateNote
     *
     * @return array
     */
    public function providerCreateNote()
    {
        return array(
            array('s', 'Test message', false, 's', 'Mnix_CoreTest->testCreateNote~Test message'),
            array('w', 'Warning message', false, 'w', 'Mnix_CoreTest->testCreateNote~Warning message'),
            array('e', 'Error message', false, 'e', 'Mnix_CoreTest->testCreateNote~Error message'),
            array('f', 'Fatal error', false, 'f', 'Mnix_CoreTest->testCreateNote~Fatal error')
        );
    }
    /**
     * Тестируем запись сообщений в лог
     *
     * Другие ситуации
     */
    public function testCreateNoteOther()
    {
        $this->assertFalse(Mnix_CoreSub::instance()->createNote('a', 'dfdf', false));
        $this->assertRegExp("/^s~.*PHPUnit_Framework.*/s", Mnix_CoreSub::instance()->createNote('s', 'Test message', true));
    }
    /**
     * Тестируем запись сообщений при завершении работы
     */
    public function testDebugFinish()
    {
        $obj = Mnix_CoreSub::instance();
        $obj->debugFinish();
        $this->assertStringEndsWith("Accident finishing\n", $obj->_log);
        Mnix_CoreSub::clearInstance();
        $obj = Mnix_CoreSub::instance();
        $obj->_crash = false;
        $obj->debugFinish();
        $this->assertStringEndsWith("Normal finishing\n", $obj->_log);
    }
    /**
     * Тестируем получение пути для автоподгрузки
     *
     * @dataProvider providerGetPath
     */
    public function testGetPath($class, $result)
    {
        $this->assertEquals(Mnix_CoreSub::instance()->getPath($class), $result);
    }
    /**
     * Провайдер для testGetPath
     *
     * @return array
     */
    public function providerGetPath()
    {
        return array(
            array('Mnix', MNIX_PATH_LIB . 'Mnix.php'),
            array('Mnix_Core', MNIX_PATH_LIB . 'Mnix/Core.php'),
            array('Mnix_Core_Controller', MNIX_PATH_LIB . 'Mnix/Core/Controller.php')
        );
    }
    /**
     * Тестируем строку со временем
     */
    public function testGetTime()
    {
        $this->assertRegExp("/^\d.*\d$/", Mnix_CoreSub::instance()->getTime());
    }
    /**
     * Тестируем финиш
     */
    public function testFinish()
    {
        $fixture = Mnix_CoreSub::instance();
        $this->assertEquals($fixture->_crash, true);
        $fixture->finish();
        $this->assertEquals($fixture->_crash, false);
    }
    /**
    * Проверяем инстанс у обоих классов
    */
    public function testInstance()
    {
        $this->assertType('Mnix_CoreSub', Mnix_CoreSub::instance());
        Mnix_CoreSub::clearInstance();
        $this->assertType('Mnix_Core', Mnix_Core::instance());
    }
    /**
     * Тестируем запись лога
     */
    public function testPutLog()
    {
        $obj = Mnix_CoreSub::instance();
        $this->assertEquals($obj->_log, null);
        $obj->putLog('s', 'Test message');
        $this->assertStringStartsWith('s~', $obj->_log);

        Mnix_CoreSub::clearInstance();
        $obj = Mnix_CoreSub::instance();
        $this->assertEquals($obj->_log, null);
        $obj->putLog('w', 'Test message');
        $this->assertStringStartsWith('w~', $obj->_log);

        Mnix_CoreSub::clearInstance();
        $obj = Mnix_CoreSub::instance();
        $this->assertEquals($obj->_log, null);
        $obj->putLog('e', 'Test message');
        $this->assertStringStartsWith('e~', $obj->_log);

        Mnix_CoreSub::clearInstance();
        $obj = Mnix_CoreSub::instance();
        $this->assertEquals($obj->_log, null);
        $obj->putLog('a', 'Test message', true);
        $this->assertStringStartsWith('w~', $obj->_log);
    }
    /**
     * Тестируем запись лога
     */
    public function testLog()
    {
        $this->assertEquals(Mnix_CoreSub::instance()->_log, null);
        Mnix_CoreSub::log('s', 'Test message', false);
        $this->assertStringStartsWith('s~', Mnix_CoreSub::instance()->_log);
    }
    /**
     * Тестируем счетчик
     */
    public function testPutLogCount()
    {
        $obj = Mnix_CoreSub::instance();
        //Счетчик может содержать только целые значения
        $this->assertContainsOnly('int', $obj->_count);

        //Проверяем начальные значения
        foreach ($obj->_count as $temp) {
            $this->assertEquals(0, $temp);
        }

        //Инкремент
        $obj->putLogCount('core_cls');
        $this->assertEquals(1, $obj->_count['core_cls']);

        //Увеличение на определенное число
        $obj->putLogCount('core_cls', 9);
        $this->assertEquals(10, $obj->_count['core_cls']);

        //Запуск без параметра
        try {
            $obj->putLogCount();
        } catch (Exception $e) {
            $this->assertEquals(2, $e->getCode());
        }

        //Неправильный второй параметр
        $obj->putLogCount('core_cls', 's');
        $this->assertEquals(10, $obj->_count['core_cls']);
    }
    /**
     * Тестируем счетчик
     */
    public function testLogCount()
    {
        $count = Mnix_CoreSub::instance()->_count;
        //Счетчик может содержать только целые значения
        $this->assertContainsOnly('int', $count);

        //Проверяем начальные значения
        foreach ($count as $temp) {
            $this->assertEquals(0, $temp);
        }

        //Инкремент
        Mnix_CoreSub::instance()->logCount('core_cls');
        $this->assertEquals(1, Mnix_CoreSub::instance()->_count['core_cls']);

        //Увеличение на определенное число
        Mnix_CoreSub::instance()->logCount('core_cls', 9);
        $this->assertEquals(10, Mnix_CoreSub::instance()->_count['core_cls']);

        //Запуск без параметра
        try {
            Mnix_CoreSub::instance()->logCount();
        } catch (Exception $e) {
            $this->assertEquals(2, $e->getCode());
        }

        //Неправильный второй параметр
        Mnix_CoreSub::instance()->logCount('core_cls', 's');
        $this->assertEquals(10, Mnix_CoreSub::instance()->_count['core_cls']);
    }
    /**
     * Тестируем запись времени
     */
    public function testPutTime()
    {
        $obj = Mnix_CoreSub::instance();
        $obj->putLogTime('test');
        $this->assertArrayHasKey('start', $obj->_time['test']);
        $this->assertType('float', $obj->_time['test']['start']);
        $this->assertType('float', $obj->_time['test']['time']);
        $this->assertEquals(0, $obj->_time['test']['time']);
        $this->assertLessThanOrEqual(microtime(true), $obj->_time['test']['start']);
        $this->assertNull($obj->_log);
        $obj->putLogTime('test', true);
        $this->assertArrayHasKey('start', $obj->_time['test']);
        $this->assertType('float', $obj->_time['test']['start']);
        $this->assertType('float', $obj->_time['test']['time']);
        $this->assertLessThanOrEqual(1.0, $obj->_time['test']['time']);
        $this->assertNull($obj->_log);

        $obj->putLogTime('test');
        $this->assertLessThanOrEqual(1.0, $obj->_time['test']['time']);
        $this->assertLessThanOrEqual(microtime(true), $obj->_time['test']['start']);
        $this->assertNull($obj->_log);
        sleep(1.0);
        $obj->putLogTime('test', true);
        $this->assertGreaterThan(1.0, $obj->_time['test']['time']);
        $this->assertLessThanOrEqual(2.0, $obj->_time['test']['time']);
        $this->assertNull($obj->_log);

        $obj->putLogTime('test1', true);
        $this->assertNotNull($obj->_log);
    }
    /**
     * Тестируем запись времени
     */
    public function testTime()
    {
        $obj = Mnix_CoreSub::instance();
        Mnix_CoreSub::LogTime('test');
        $this->assertLessThanOrEqual(microtime(true), $obj->_time['test']['start']);
        $this->assertNull($obj->_log);
        Mnix_CoreSub::LogTime('test', true);
        $this->assertLessThanOrEqual(1.0, $obj->_time['test']['time']);
        $this->assertNull($obj->_log);
    }
    /**
     * Тестируем ран
     */
    public function testRun()
    {
        $fixture = Mnix_Core::instance();
        $fixture->run();
        $this->assertEquals(get_class(Mnix_Core::instance()), 'Mnix_Core');
    }

}