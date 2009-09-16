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
     * Указывает, было ли завершение аварийным
     * 
     * @var boolean
     */
    protected static $_crash = true;
    /**
     * Счетчики времени
     *
     * @var array
     */
    protected static $_time;
    /**
     * Лог, записываемый в файл
     * 
     * @var string
     */
    protected static $_log;
    /**
     * Содержит лог отладки
     *
     * @var string
     */
    protected static $_debug_log = '<pre>';
    /**
     * Различные счетчики итераций
     *
     * @var array
     */
    protected static $_count = array('cache_l'=>0,'cache_r'=>0,'core_cls'=>0,'cache_s'=>0,'cache_d'=>0);
    /**
     * Конструктор
     */
	public function __construct()
    {
        /*self::$_time['db']['time'] = 0;
        self::$_count['db_q'] = 0;*/
	}
    /**
     * Деструктор
     */
	public function __destruct()
    {
        if (defined('MNIX_CORE_LOGGING_STATUS') && MNIX_CORE_LOGGING_STATUS) {
            $this->_end();
            if (defined('MNIX_CORE_LOGGING_VIEW') && MNIX_CORE_LOGGING_VIEW) echo self::$_hot_log;
        }
	}
    /**
     * Менеджер
     */
    public function run()
    {
        spl_autoload_register('Mnix_Core::_autoload');
    }
    /**
     * Записывает сообщение в лог
     *
     * @param string $status
     * @param string $message
     * @param bool $trace
     */
    public static function log($status, $message, $trace_flag = false)
    {
        if (in_array($status, array('s', 'w', 'e', 'f'))) {
            $traces = debug_backtrace();
            //reset($traces);
            unset($traces[0]);
            var_dump($traces);
            $note = $status . '~' . self::_getTime() .
                '~' . $traces[1]['class'] . $traces[1]['type'] . $traces[1]['function'] .
                '~' . $message . "\n";
            if ($trace_flag) {
                $note .= "~trace:\n";
                foreach($traces as $key => $val) {
                    $note .= '~' . $key . '~' . $val['class'] . $val['type'] .  $val['function'] .
                        '~' . $val['file'] . ':' . $val['line'] . "\n";
                }
                $note .= "~/trace\n";
            }
            //Записываем лог отладки
            if (MNIX_CORE_LOG_DEBUG) self::$_debug_log .= $note;
            //Записываем лог
            switch ($status) {
                case 's':
                    if (MNIX_CORE_LOG_SYSTEM) self::$_log .= $note;
                    break;
                case 'w':
                    if (MNIX_CORE_LOG_WARNING) self::$_log .= $note;
                    break;
                default:
                    if (MNIX_CORE_LOG_ERROR) self::$_log .= $note;
                    break;
            }

            echo self::$_debug_log . '</pre>';
        } else {
            //TODO: кинуть исключение
            throw new Mnix_Exception;
        }
	}
    /**
     * Засекаем время
     *
     * @param string $thing
     * @param boolean $end
     * @return string
     */
    public static function time($thing, $end = false)
    {
		if ($end) {
			$time = microtime() - self::$_time[$thing]['start'];
			self::$_time[$thing]['time'] += $time;
            return $time;
		} else self::$_time[$thing]['start'] = microtime();;
    }
    /**
     * Обновляем счетчик
     *
     * @param string $thing
     * @param int $number
     */
    public static function count($thing, $number = 1)
    {
        self::$_count[$thing] += $number;
    }
    /**
     * Показывает время
     *
     * @return string
     */
    protected static function _getTime()
    {
		$t = microtime(true);
		return date('Y.m.d/H:i', $t).'|'.number_format($t - MNIX_CORE_STARTTIME, 5);
	}
    /**
     * Нормальное завершение
     */
    protected function _end()
    {
		Mnix_Core::putMessage(__CLASS__, 'sys', 'Ending...');
		Mnix_Core::putMessage(__CLASS__, 'sys', 'Request to db: '. self::$_count['db_q']);
		Mnix_Core::putMessage(__CLASS__, 'sys', 'Time of working db: '. number_format(self::$_time['db']['time'], 5));
        Mnix_Core::putMessage(__CLASS__, 'sys', 'Request to cache: '. (int)self::$_count['cache_r']);
        Mnix_Core::putMessage(__CLASS__, 'sys', 'Load from cache: '. (int)self::$_count['cache_l']);
        Mnix_Core::putMessage(__CLASS__, 'sys', 'Save to cache: '. (int)self::$_count['cache_s']);
        Mnix_Core::putMessage(__CLASS__, 'sys', 'Remove cache: '. (int)self::$_count['cache_d']);
        Mnix_Core::putMessage(__CLASS__, 'sys', 'Classes loaded: '. (int)self::$_count['class']);
		Mnix_Core::putMessage(__CLASS__, 'sys', 'Max allocated memory: '. number_format(memory_get_peak_usage() / 1024, 3) .' Kb');
		Mnix_Core::putMessage(__CLASS__, 'sys', 'Allocated memory: '. number_format(memory_get_usage() / 1024, 3) .' Kb');
		if (!$this->_crash) Mnix_Core::putMessage(__CLASS__, 'sys', 'Normal ending.');
        else Mnix_Core::putMessage(__CLASS__, 'err', 'Accident ending.');
		Mnix_Core::putMessage(__CLASS__, 'sys', 'End of processing loging.');
    }
    /**
     * Автозагрузка классов
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
            //TODO кидать исключение
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
        return MNIX_PATH_LIB . str_replace('_', '/', $class).'.php';
    }
}