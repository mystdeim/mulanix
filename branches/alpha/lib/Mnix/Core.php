<?php
 /**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Core
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */
/**
 * Ядро системы
 *
 * Управляет очередностью загрузки, ведёт лог
 *
 * @category Mulanix
 * @package Mnix_Core
 * @tutorial Mnix_Core/Core.cls
 */
class Mnix_Core
{
    /**
     * Содержит экземпляр ядра
     *
     * @var Mnix_Core
     */
    protected static $_instance = null;
    /**
     * Указывает, было ли завершение аварийным
     * 
     * @var boolean
     */
    protected $_crash = true;
    /**
     * Счетчики времени
     *
     * @var array
     */
    protected $_time;
    /**
     * Лог, записываемый в файл
     * 
     * @var string
     */
    protected $_log;
    /**
     * Содержит лог отладки
     *
     * @var string
     */
    protected $_debugLog;
    /**
     * Различные счетчики итераций
     *
     * @var array
     */
    protected $_count = array(
        'cache_r'  => 0,
        'cache_l'  => 0,
        'cache_s'  => 0,
        'cache_d'  => 0,
        'core_cls' => 0,
        'db_q'     => 0
    );
    /**
     * Функции, которые нужно игнорировать при записи логов
     *
     * @var array
     */
    protected $_ignoreFunc = array (
        'log',
        'putLog',
        '_createNote'
    );
    /**
     * Возвращает экземпляр класса Mnix_Core
     *
     * TODO: в php >= 5.3, можно переписать функцию:
     * <code>
     * if (!isset(self::$_instance)) self::$_instance = new get_called_class();
     * return self::$_instance;
     * </code>
     * Чтобы избавиться от переопределения в наследуемых классах.
     *
     * @return object Mnix_Core
     */
    public static function instance()
    {
        if (!isset(self::$_instance)) self::$_instance = new self();
        return self::$_instance;
    }
    /**
     * Защищенный конструктор
     */
    protected function __construct()
    {
        spl_autoload_register('Mnix_Core::_autoload');
    }
    /**
     * Деструктор
     */
    public function __destruct() {
        if (MNIX_CORE_LOG_DEBUG) {
            $this->_debugFinish();
            echo '<pre>' . $this->_debugLog . '</pre>';
        }
    }
    /**
     * Менеджер
     *
     * @return object Mnix_Core
     */
    public function run()
    {
        $this->putLog('s', 'Run core');
        return $this;
    }
    /**
     * Завершение работы ядра
     */
    public function finish()
    {
        $this->_crash = false;
    }
    /**
     * Записывает сообщение в лог
     *
     * Вызов Mnix_Core::putLog() без создания объекта
     * 
     * @see Mnix_Core::putLog()
     * @param string $status статус ошибки
     * @param string $message текстовое сообщение
     * @param bool $trace_flag нужно ли писать трассировку
     */
    public static function log($status, $message, $trace_flag = false)
    {
        self::instance()->putLog($status, $message, $trace_flag);
    }
    /**
     * Записывает сообщение в лог
     *
     * @param string $status статус ошибки
     * @param string $message текстовое сообщение
     * @param bool $trace_flag нужно ли писать трассировку
     * @return object Mnix_Core
     */
    public function putLog($status, $message, $trace_flag = false)
    {
        if ($note = $this->_createNote($status, $message, $trace_flag)) {
            //Записываем лог отладки
            if (MNIX_CORE_LOG_DEBUG) $this->_debugLog .= $note;
            //Записываем лог
            switch ($status) {
                case 's':
                    if (MNIX_CORE_LOG_SYSTEM) $this->_log .= $note;
                    break;
                case 'w':
                    if (MNIX_CORE_LOG_WARNING) $this->_log .= $note;
                    break;
                default:
                    if (MNIX_CORE_LOG_ERROR) $this->_log .= $note;
                    break;
            }
        } else {
            $this->putLog('w', 'Error in log type message', true);
        }
        return $this;
    }
    /**
     * Создание записи в логах
     *
     * @param string $status статус ошибки
     * @param string $message текстовое сообщение
     * @param bool $trace_flag нужно ли писать трассировку
     * @return string|false
     */
    protected function _createNote($status, $message, $trace_flag)
    {
        if (in_array($status, array('s', 'w', 'e', 'f'))) {
            $traces = debug_backtrace(false);
            //Удаляем из трассировки лишнии вызовы
            foreach ($traces as $key => $val) {
                //Сравнение get_class($this) нужно при тестировании, чтобы не было конфликта с наследником класса
                if (($val['class'] === get_class($this) || $val['class'] === __CLASS__)
                    && in_array($val['function'], $this->_ignoreFunc)) {
                    unset($traces[$key]);
                }
            }
            reset($traces);
            $trace = current($traces);
            $note = $status . '~' . $this->_getTime() .
                '~' . $trace['class'] . $trace['type'] . $trace['function'] .
                '~' . $message . "\n";
            if ($trace_flag) {
                $i = 0;
                foreach($traces as $val) {
                    $note .= '~' . $i++ . '~' . $val['class'] . $val['type'] .  $val['function'] .
                        '~' . $val['file'] . ':' . $val['line'] . "\n";
                }
            }
            return $note;
        } else {
            return false;
        }
    }
    /**
     * Засекаем время
     *
     * Вызов Mnix_Core::putTime() без создания объекта
     * 
     * @param string $thing таймер, который нужно запустить
     * @param boolean $end нужно ли закончить отчет времени
     */
    public static function time($thing, $end = false)
    {
        self::instance()->putTime($thing, $end);
    }
    /**
     * Засекаем время
     *
     * @param string $thing таймер, который нужно запустить
     * @param boolean $end нужно ли закончить отчет времени
     * @return object Mnix_Core
     */
    public function putTime($thing, $end = false)
    {
        if ($end) {
            if (isset($this->_time[$thing]['start'])) {
                $time = microtime(true) - $this->_time[$thing]['start'];
                $this->_time[$thing]['time'] += $time;
            } else {
                $this->putLog('w', 'Wrong second parametr, must be false', true);
            }
        } else $this->_time[$thing]['start'] = microtime(true);
        return $this;
    }
    /**
     * Обновляем счетчик
     *
     * Вызов Mnix_Core::logCount() без создания объекта
     *
     * @param string $thing счетчик, который нужно увеличить
     * @param int $number насколько увеличть счетчик
     */
    public static function logCount($thing, $number = 1)
    {
        self::instance()->putLogCount($thing, $number);
    }
    /**
     * Обновляем счетчик
     *
     * @param string $thing счетчик, который нужно увеличить
     * @param int $number насколько увеличть счетчик
     * @return object Mnix_Core
     */
    public function putLogCount($thing, $number = 1)
    {
        if (!is_int($number)) {
            $this->putLog('w', 'Wrong second parametr, must be int', true);
            $number = 0;
        }
        $this->_count[$thing] += $number;
        return $this;
    }
    /**
     * Показывает время
     *
     * @return string
     */
    protected function _getTime()
    {
        $t = microtime(true);
        return date('Y.m.d/H:i u', $t) . '|' . number_format($t - MNIX_CORE_STARTTIME, 5);
    }
    /**
     * Запись отладочных сообщений при завершении работы
     */
    protected function _debugFinish()
    {
        $this->putLog('s', 'Finishing...')
             ->putLog('s', 'Request to db: '. $this->_count['db_q'])
             ->putLog('s', 'Time of working db: '. number_format($this->_time['db']['time'], 5))
             ->putLog('s', 'Request to cache: '. $this->_count['cache_r'])
             ->putLog('s', 'Load from cache: '. $this->_count['cache_l'])
             ->putLog('s', 'Save to cache: '. $this->_count['cache_s'])
             ->putLog('s', 'Remove cache: '. $this->_count['cache_d'])
             ->putLog('s', 'Classes loaded: '. $this->_count['core_cls'])
             ->putLog('s', 'Max allocated memory: '. number_format(memory_get_peak_usage() / 1024, 3) .' Kb')
             ->putLog('s', 'Allocated memory: '. number_format(memory_get_usage() / 1024, 3) .' Kb');
        if (!$this->_crash) $this->putLog('s', 'Normal finishing');
        else $this->putLog('w', 'Accident finishing', false);
    }
    /**
     * Автозагрузка классов
     *
     * @param string $class имя подключаемого класса
     */
    protected static function _autoload($class)
    {
        if (file_exists(self::_getPath($class))) {
            self::logCount('core_cls');
            require_once self::_getPath($class);
            self::log('s', 'Load class: ' . $class);
        } else {
            throw new Mnix_Exception_Fatal("Class '$class' isn`t exists", 1);
        }
    }
    /**
     * Составление абсолютного пути для подгрузки класса
     *
     * @param string $class имя подключаемого класс
     * @return string полный путь
     */
    protected static function _getPath($class)
    {
        return MNIX_PATH_LIB . str_replace('_', '/', $class).'.php';
    }
}