<?php
/**
 * Mulanix Framework
 *
 * @package Mnix_Core
 * @author deim
 * @copyright 2009
 */
/**
 * @package Mnix_Core
 */
class Mnix_Core
{
    protected static $_crash;
    protected static $_time;
    protected static $_hot_log;
    protected static $_count = array('cache_l'=>0,'cache_r'=>0,'class'=>0,'cache_s'=>0,'cache_d'=>0);
	public function __construct()
    {
		$this->_crash = true;
        self::$_time['db']['time'] = 0;
        self::$_count['db_q'] = 0;
        spl_autoload_register('Mnix_Core::_autoload');
	}
	public function __destruct()
    {
        $this->_end();
        echo self::$_hot_log;
	}
    public function run()
    {
        //Грузим конфиг
        Mnix_Config::load();

        //Создаём юзера
        $user = Mnix_Auth_User::current();

        //Получаем группу
        $group = $user->getGroup();

        //Парсим урл
        $url = Mnix_Uri::current();

        //Получаем страницу
        $page = $url->getPage();

        //Проверяем разрешение на страницу группе
        $acl = new Mnix_Acl();
        $acl->role($group);
        if ($acl->isAllowed('view', $page)) {

            //Получаем шаблоны
            $templates = $page->getTemplate();
 
            //Обходим шаблоны
            foreach ($templates as $template) {
                //var_dump($template);

                //Смотрим права
                $rights = $acl->allowed($template);
                //var_dump($rights);
                if (isset($rights)) {

                }

                //Создаём контроллер и выполняем его
            }
        } else {
            die('Pemission denied!');
        }
        //$a++;
        try {
            $a++;
            throw new Mnix_Db_Select_Exception('error');
        } catch(Exception $e) {
            var_dump($e);
            Mnix_Core::putMessage(__CLASS__, 'err', $e->getMessage());
        }

        //Выполнение не было прервано
        $this->_crash =false;
    }
    /**
     * Записывает сообщение в лог
     * 
     * @param string $class_name
     * @param string $mode
     * @param string $note
     */
    public static function putMessage($class_name, $mode, $note)
    {
		$class_name = '<font color="blue">'.$class_name.'</font>';
		switch ($mode) {
			case 'err':
				$note = '<font color="red">'.$note.'</font>';
				break;
			case 'sys':
				$note = '<font color="black">'.$note.'</font>';
				break;
		}
		self::$_hot_log = self::$_hot_log.self::_getTime().' | '.$mode.' | <b>'.$class_name.'~'.$note.'</b><br />';
	}
    public static function putTime($thing, $end = false)
    {
		if ($end) {
			$time = microtime() - self::$_time[$thing]['start'];
			self::$_time[$thing]['time'] += $time;
            return $time;
		} else self::$_time[$thing]['start'] = microtime();;
    }
    public static function putCount($thing, $number = 1)
    {
        self::$_count[$thing] += $number;
    }
    protected static function _getTime()
    {
		$t = microtime(true);
		return date('Y.m.d / H.i', $t).' | '.number_format($t - MNIX_STARTTIME, 5);
	}
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
     * @param string $class
     */
    protected static function _autoload($class)
    {
        if (file_exists(self::_getPath($class))) {
            self::putCount('class');
            require_once self::_getPath($class);
            Mnix_Core::putMessage(__CLASS__, 'sys', 'Load class: ' . $class);
        } else {
            Mnix_Core::putMessage(__CLASS__, 'err', 'Not found class: ' . $class);
        }
    }
    /**
     * Определяем путь до класса
     * @param string $class
     * @return string
     */
    protected static function _getPath($class)
    {
        $names = explode('_', $class);
        $key = array_search('Mnix', $names);
        if ($key !== false) $names[$key] = 'lib';
        $key = array_search('Test', $names);
        if ($key !== false) $names[$key] = 'test';
        $path = MNIX_DIR . implode('/', $names).'.php';
        return $path;
    }
}