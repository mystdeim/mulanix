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
require_once 'Core/_files/CoreSub.php';

/**
 * @category Mulanix
 * @package Mnix_Core
 * @subpackage Test
 */
class Mnix_CoreTest extends PHPUnit_Framework_TestCase
{
    /**
     * Фикстура
     *
     * @see Mnix_CoreSub
     * @var object Mnix_CoreSub
     */
    protected $_fixture;

    /**
     * Инициализируем объект Mnix_CoreSub
     */
    protected function setUp()
    {
        $this->_fixture = new Mnix_CoreSub;
    }

    /**
     * Удаляем фикстуру
     */
    protected function tearDown()
    {
        unset($this->_fixture);
    }

    /**
     * Тестируем счетчик
     */
    public function testCount()
    {
        $count = $this->_fixture->getCount();

        //Счетчик может содержать только целые значения
        $this->assertContainsOnly('int', $count);

        //Проверяем начальные значения
        foreach ($count as $temp) {
            $this->assertEquals(0, $temp);
        }

        //Инкремент
        $this->_fixture->count('core_cls');
        $count = $this->_fixture->getCount();
        $this->assertEquals(1, $count['core_cls']);

        //Увеличение на определенное число
        $this->_fixture->count('core_cls', 9);
        $count = $this->_fixture->getCount();
        $this->assertEquals(10, $count['core_cls']);

        //Проверка статичности
        $this->tearDown();
        $this->setUp();
        $this->assertEquals(10, $count['core_cls']);

        //Запуск без параметра
        try {
            $this->_fixture->count();
        } catch (Exception $e) {
            $this->assertEquals(2, $e->getCode());
        }
        
        //Не

        //Неправильный второй параметр
        try {
            $this->_fixture->count('core_cls', 's');
        } catch (Exception $e) {
            $this->assertEquals(1, $e->getCode());
        }

    }
    
    /**
     * Тестируем получение пути для автоподгрузки
     *
     * @dataProvider providerGetPath
     */
    public function testGetPath($class, $result)
    {
        $this->assertEquals($this->_fixture->getPath($class), $result);
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
}