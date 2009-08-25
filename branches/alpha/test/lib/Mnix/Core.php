<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Core
 * @subpackage Test
 * @version $Id: Db.php 80 2009-08-25 12:26:43Z mystdeim $
 * @author mystdeim <mysteim@gmail.com>
 */
/**
 * Ядро тестировщика
 *
 * @category Mulanix
 * @package Mnix_Core
 * @subpackage Test
 */
class Test_Mnix_Core extends Mnix_Core
{
    /**
     * Набор тестов
     *
     * @var PHPUnit_Framework_TestSuite
     */
    protected $_suite;
    /**
     * Запуск тестирования
     */
    public function run()
    {
        spl_autoload_register('Test_Mnix_Core::_autoload');
        
        require_once 'PHPUnit/Framework/TestCase.php';
        require_once 'PHPUnit/Framework/TestSuite.php';
        require_once 'PHPUnit/TextUI/TestRunner.php';
        require_once 'PHPUnit/Extensions/OutputTestCase.php';
        require_once 'PHPUnit/Extensions/PerformanceTestCase.php';
        $this->_suite = new PHPUnit_Framework_TestSuite('Test Suite');
        $this->_test();
        $this->_crash =false;
    }
    /**
     * Деструктор
     */
    public function  __destruct()
    {
        echo '<pre>';
        $reporter = PHPUnit_TextUI_TestRunner::run($this->_suite);
        echo '</pre>';
        parent::__destruct();
    }
    /**
     * Порядок тестирования
     */
    protected function _test()
    {
        //Подгрузка конфига
        Mnix_Config::load();

        //Если в запросе не указано конкретного класса, то тастируется всё
        if ($_SERVER['REQUEST_URI'] === '/test/' || $_SERVER['REQUEST_URI'] === '/test') {
            $this->_suite->addTestSuite('Test_Mnix_CacheTest');
            $this->_suite->addTestSuite('Test_Mnix_DbTest');
            $this->_suite->addTestSuite('Test_Mnix_Db_SelectTest');
            $this->_suite->addTestSuite('Test_Mnix_ORM_PrototypeTest');
            $this->_suite->addTestSuite('Test_Mnix_ORM_CollectionTest');
            $this->_suite->addTestSuite('Test_Mnix_UriTest');
            $this->_suite->addTestSuite('Test_Mnix_AclTest');
        } else $this->_selectTest();
    }
    /**
     * Указания тестирвоания только одного класса
     */
    protected function _selectTest()
    {
        $class = str_replace(array('test', '/'), '', $_SERVER['REQUEST_URI']);
        echo 'Testing: ' . $class;
        $this->_suite->addTestSuite($class);
    }
    /**
     * Переопределение функции автозагрузки
     *
     * @param string $class
     */
    protected static function _autoload($class)
    {
        if (file_exists(self::_getPath($class))) {
            self::putCount('class');
            require_once self::_getPath($class);
            Mnix_Core::putMessage(__CLASS__, 'sys', 'Load class: ' . $class);
        } else {
            //TODO кидать иключение
        }
    }
    /**
     * Составление абсолютного пути для подгрузки класса
     *
     * @param string $class
     * @return string
     */
    protected static function _getPath($class)
    {
        $names = explode('_', $class);
        $key = array_search('Mnix', $names);
        if ($key !== false) $names[$key] = 'lib/Mnix';
        $key = array_search('Test', $names);
        if ($key !== false) $names[$key] = 'test';
        $path = MNIX_PATH_DIR . implode('/', $names).'.php';
        return $path;
    }
}