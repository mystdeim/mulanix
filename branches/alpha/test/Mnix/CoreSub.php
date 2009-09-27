<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Core
 * @subpackage Test
 * @version $Id: CoreSub.php 98 2009-09-16 18:33:07Z mystdeim $
 * @author mystdeim <mysteim@gmail.com>
 */

/**
 * @see Mnix_Core
 */
require_once dirname(dirname(dirname(__FILE__))) . '/lib/Mnix/Core.php';
require_once dirname(dirname(dirname(__FILE__))) . '/lib/Mnix/Exception.php';
require_once dirname(dirname(dirname(__FILE__))) . '/lib/Mnix/Exception/Fatal.php';

define('MNIX_CORE_LOG_SYSTEM', true);
define('MNIX_CORE_LOG_WARNING', true);
define('MNIX_CORE_LOG_ERROR', true);
define('MNIX_CORE_LOG_DEBUG', true);

/**
 * @category Mulanix
 * @package Mnix_Core
 * @subpackage Test
 */
class Mnix_CoreSub extends Mnix_Core
{
    /**
     * Возвращает экземпляр класса Mnix_CoreSub
     *
     * @return Mnix_CoreSub
     */
    public static function instance()
    {
        if (!isset(parent::$_instance)) parent::$_instance = new Mnix_CoreSub;
        return parent::$_instance;
    }
    /**
     * Удаляем статический объект и не даём ему насрать
     */
    public static function clearInstance()
    {
        ob_start();
        parent::$_instance = null;
        ob_end_clean();
    }
    /**
     * Переопределяем конструктор
     *
     * Пишем функцию, которую надо игнорировать
     */
    public function  __construct()
    {
        $this->_ignoreFunc[] = 'createNote';
        parent::__construct();
    }
    /**
     * Переопределяем деструктор
     */
    public function __destruct()
    {
        
    }
    /**
     * __get
     *
     * @param string $name
     * @return mixed
     */
    public function  __get($name)
    {
        return $this->$name;
    }
    /**
     * __set
     *
     * @param string $name
     * @param mixed $value
     */
    public function  __set($name,  $value)
    {
        $this->$name = $value;
    }
    /**
     * Переопределяем метод в публиный
     *
     * @param string $class
     * @return string
     */
    public function getPath($class)
    {
        return self::_getPath($class);
    }
    /**
     * Переопределяем метод в публиный
     *
     * @param string $status
     * @param string $message
     * @param bool $trace_flag
     * @return string
     */
    public function createNote($status, $message, $trace_flag)
    {
        return $this->_createNote($status, $message, $trace_flag);
    }
    /**
     * Переопределяем метод в публиный
     *
     * @param string $class
     * @return string
     */
    public function autoload($class)
    {
        return self::_autoload($class);
    }
    /**
     * Переопределяем метод в публиный
     */
    public function debugFinish()
    {
        $this->_debugFinish();
    }
    /**
     * Переопределяем метод в публиный
     *
     * @return string
     */
    public function getTime()
    {
       return $this->_getTime();
    }
}