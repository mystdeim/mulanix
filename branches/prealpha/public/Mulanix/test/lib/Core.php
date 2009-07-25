<?php
/**
 * Ядро тестировщика
 *
 * @author deim
 * Created on 10.07.2009, 20:45:00
 */
class Test_Mnix_Core extends Mnix_Core
{
    protected $_suite;
    //protected $_reporter;
    public function run()
    {
        //Создаём Test Suite
        require_once 'PHPUnit/Framework/TestCase.php';
        require_once 'PHPUnit/Framework/TestSuite.php';
        require_once 'PHPUnit/TextUI/TestRunner.php';
        $this->_suite = new PHPUnit_Framework_TestSuite('Test Suite');

        $this->_test();
        $this->_crash =false;
    }
    public function  __destruct()
    {
        echo '<pre>';
        $reporter = PHPUnit_TextUI_TestRunner::run($this->_suite);
        echo '</pre>';
        //Test_Mnix_Db::dump_end();
        parent::__destruct();
    }
    /**
     * Порядок тестирования
     */
    protected function _test()
    {
        //Наиболее важные компоненты тестируются всегда
        $this->_suite->addTestSuite('Test_Mnix_CacheTest');
        //Подгрузка конфига
        Mnix_Config::load();
        //Дамп БД
        //Test_Mnix_Db::dump();
        //Если в запросе не указано конкретного класса, то тастируется всё
        if ($_SERVER['REQUEST_URI'] === '/test/' || $_SERVER['REQUEST_URI'] === '/test') {
            $this->_suite->addTestSuite('Test_Mnix_DbTest');
            $this->_suite->addTestSuite('Test_Mnix_Db_SelectTest');
            $this->_suite->addTestSuite('Test_Mnix_ORM_PrototypeTest');
            $this->_suite->addTestSuite('Test_Mnix_ORM_CollectionTest');
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
}