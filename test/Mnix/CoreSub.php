<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id: CoreSub.php 98 2009-09-16 18:33:07Z mystdeim $
 * @author mystdeim <mysteim@gmail.com>
 */

namespace Mnix;

require_once dirname(dirname(__DIR__)) . '/boot/bootstrap.php';
require_once dirname(dirname(__DIR__)) . '/lib/Mnix/Exception.php';
require_once dirname(dirname(__DIR__)) . '/lib/Mnix/Exception/Fatal.php';
require_once dirname(dirname(__DIR__)) . '/lib/Mnix/Core.php';

/**
 * Запись системных сообщений в лог-файл
 */
define('Mnix\Core\Log\SYSTEM', true);
/**
 * Запись предупреждений в лог-файл
 */
define('Mnix\Core\Log\WARNING', true);
/**
 * Запись ошибок сообщений в лог-файл
 */
define('Mnix\Core\Log\ERROR', true);
/**
 * Вывод отладочной информации после завершении работы приложения
 */
define('Mnix\Core\Log\DEBUG', true);

/**
 * @category Mulanix
 */
class CoreSub extends Core
{
    /**
     * Удаляем статический объект и не даём ему насрать
     */
    public static function clearInstance()
    {
        parent::$_instance = null;
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
        return $this->_getPath($class);
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
    /**
     * Переопределяем Деструктор
     */
    public function __destruct() {
        ob_start();
        parent::__destruct();
        ob_end_clean();
    }
}