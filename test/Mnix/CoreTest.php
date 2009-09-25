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
     * Проверяем инстанс у обоих классов
     */
    public function testInstance()
    {
        $this->assertType('Mnix_CoreSub', Mnix_CoreSub::instance());
        Mnix_CoreSub::clearInstance();
        $this->assertType('Mnix_Core', Mnix_Core::instance());
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
     * Тестируем запись сообщений в лог
     *
     * @dataProvider providerCreateNote
     */
    public function testCreateNote($status, $note, $trace, $result)
    {
        $temp = Mnix_CoreSub::instance()->createNote($status, $note, $trace);
        if (is_string($temp)) {
            $data = explode('~', $temp);
            $note = array($data[0], $data[2], $data[3]);
            if (isset($data[4])) $note[] = array();
            else $note[] = null;
        } else {
            $note = $temp;
        }
        $this->assertEquals($note, $result);
    }
    /**
     * Провайдер для testCreateNote
     *
     * @return array
     */
    public function providerCreateNote()
    {
        return array(
            array('s', 'Test message', false,
                array('s', 'Mnix_CoreTest->testCreateNote', "Test message\n", null)
            ),
            array('w', 'Warning message', false,
                array('w', 'Mnix_CoreTest->testCreateNote', "Warning message\n", null)
            ),
            array('e', 'Error message', false,
                array('e', 'Mnix_CoreTest->testCreateNote', "Error message\n", null)
            ),
            array('f', 'Fatal error', false,
                array('f', 'Mnix_CoreTest->testCreateNote', "Fatal error\n", null)
            ),
            array('f', 'Fatal error', true,
                array('f', 'Mnix_CoreTest->testCreateNote', "Fatal error\n", array())
            ),
            array('a', 'Wrong status', false,
                false
            )
        );
    }
    /**
     * Тестируем запись лога
     */
    public function testLPutLog()
    {
        $obj = Mnix_CoreSub::instance();
        $this->assertEquals($obj->_log, null);
        $obj->putLog('s', 'Test message');
        $this->assertStringStartsWith('s~', $obj->_log);
        Mnix_CoreSub::clearInstance();
        $obj = Mnix_CoreSub::instance();
        $this->assertEquals($obj->_log, null);
        $obj->putLog('s', 'Test message', false);
        $this->assertStringStartsWith('s~', $obj->_log);
        Mnix_CoreSub::clearInstance();
        $obj = Mnix_CoreSub::instance();
        $this->assertEquals($obj->_log, null);
        $obj->putLog('a', 'Test message', false);
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
     * Тестируем запись времени
     */
    public function testPutTime()
    {
        $obj = Mnix_CoreSub::instance();
        $this->assertEquals($obj->_time, null);
        $obj->putTime('test');

        $this->assertArrayHasKey('start', $obj->_time['test']);
        $this->assertType('string', $obj->_time['test']['start']);
        //$this->assertLessThanOrEqual((float)$obj->_time['test']['start'], microtime());
        $this->assertEquals($obj->_log, null);

        $obj->putTime('test', true);
        $this->assertArrayHasKey('start', $obj->_time['test']);
        $this->assertType('string', $obj->_time['test']['start']);
        $this->assertEquals($obj->_log, null);

        $obj->putTime('test2');
        $this->assertType('float', $obj->putTime('test2', true));

        //Запуск с неправильным параметром
        $obj->putTime('test3', true);
        $this->assertStringStartsWith('w~', $obj->_log);
    }
    /**
     * Тестируем запись времени
     */
    public function testTime()
    {
        $obj = Mnix_CoreSub::instance();
        Mnix_CoreSub::time('test');
        $this->assertArrayHasKey('start', Mnix_CoreSub::instance()->_time['test']);
        $this->assertType('string', Mnix_CoreSub::instance()->_time['test']['start']);
        $this->assertEquals(Mnix_CoreSub::instance()->_log, null);
        Mnix_CoreSub::time('test', true);
        $this->assertArrayHasKey('start', Mnix_CoreSub::instance()->_time['test']);
        $this->assertType('string', Mnix_CoreSub::instance()->_time['test']['start']);
        $this->assertEquals(Mnix_CoreSub::instance()->_log, null);
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
     * Тестируем ран
     */
    public function testRun()
    {
        $fixture = Mnix_Core::instance();
        $fixture->run();
        $this->assertEquals(get_class(Mnix_Core::instance()), 'Mnix_Core');
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
}