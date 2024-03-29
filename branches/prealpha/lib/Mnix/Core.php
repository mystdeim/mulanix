<?php
 /**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Core
 * @version $Id$
 */
/**
 * Ядро системы
 *
 * Управляет очередностью загрузки, ведёт лог
 *
 * @category Mulanix
 * @package Mnix_Core
 */
class Mnix_Core
{
    /**
     * Указывает, было ли завершение аварийным
     *
     * @var boolean
     */
    protected static $_crash;
    /**
     * Счетчики времени
     *
     * @var array
     */
    protected static $_time;
    /**
     * Содержит текущей лог
     *
     * @var string
     */
    protected static $_hot_log;
    /**
     * Различные счетчики
     *
     * @var array
     */
    protected static $_count = array('cache_l'=>0,'cache_r'=>0,'class'=>0,'cache_s'=>0,'cache_d'=>0);
    /**
     * Конструктор
     */
	public function __construct()
    {
		$this->_crash = true;
        self::$_time['db']['time'] = 0;
        self::$_count['db_q'] = 0;
        spl_autoload_register('Mnix_Core::_autoload');
	}
    /**
     * Деструктор
     */
	public function __destruct()
    {
        $this->_end();
        echo self::$_hot_log;
	}
    /**
     * Менеджер
     */
    public function run()
    {
        //try {
            //Грузим конфиг
            Mnix_Config::load();  
            //Создаём юзера
            $user = Mnix_Auth_User::current();
            //Получаем группу
            $group = $user->group;
            //Парсим урл
            $url = Mnix_Uri::current();
            //Получаем страницу
            $page = $url->page;
            //Получаем блоки
            $regions = $page->regions;
            //Получаем тему
            $theme = $group->theme;
            //Уточняем тему, использую индетификатор страницы
            $theme->find('?t = ?i', array('mnix_group2page2theme.page_id', $page->id));
            //Извлекаем тему из коллекции, должна быть только одна тема
            $theme = $theme->current();

            //Создаём дом-документ для файлв стилей
            $xsl = new domDocument('1.0', 'UTF-8');
            $xsl->preserveWhiteSpace = false;
            //Грузим мастер-шаблон
            $xsl->load(MNIX_THEME . $theme->name.'/master.xsl');
            $xslNodeRoot = $xsl->getElementsByTagNameNS('http://www.w3.org/1999/XSL/Transform', 'stylesheet');

            //Создаём файл с контентом
            $xml = new domDocument('1.0', 'UTF-8');
            $xml->preserveWhiteSpace = false;
            //Создаём корневой узел
            $xmlNodeRoot = $xml->createElement('root');
            $xml->appendChild($xmlNodeRoot);
            //Создаём узел с заголовком и телом документа
            $xmlNodeHead = $xml->createElement('head');
            $xmlNodeBody = $xml->createElement('body');
            $xmlNodeRoot->appendChild($xmlNodeHead);
            $xmlNodeRoot->appendChild($xmlNodeBody);
            //Создаём узел с заголовком
            $xmlNodeTitle = $xml->createElement('title');
            $xmlNodeTitleText = new domText($page->name);
            $xmlNodeTitle->appendChild($xmlNodeTitleText);
            $xmlNodeHead->appendChild($xmlNodeTitle);

            //Обходим блоки
            foreach ($regions as $region) {
                //var_dump($region);

                //Создаём в xml ноду с названием блока
                $xmlNoderegion = $xml->createElement($region->name);
                $xmlNodeBody->appendChild($xmlNoderegion);

                //Получаем шаблоны, соответствующие блоку
                $templates = $region->templates;
                //Уточняем поиск, выбираем шаблоны, соответствующие текущей странице
                $templates->find('?t = ?i', array('mnix_page2template2region.page_id', $page->id));
                //Обходим шаблоны
                //$templates->load();
                //exit();
                foreach ($templates as $template) {
                    //var_dump($template);

                    //Компонент шаблона
                    $component = $template->component;
                    
                    //Создаём в xml ноду с названием компонента + шаблон
                    $xmlNodeTemplate = $xml->createElement($component->name.'_'.$template->name);
                    $xmlNoderegion->appendChild($xmlNodeTemplate);

                    //Создаём домдокумент из шаблона
                    $file = MNIX_LIB.str_replace('_','/',$component->name).'/template/'.$template->name.'.xsl';
                    $xslTemplate = new domDocument('1.0', 'UTF-8');
                    $xslTemplate->preserveWhiteSpace = false;
                    $xslTemplate->load($file);

                    //Импорт нод в мастер-шаблон
                    for ($i=0; $xslTemplate->getElementsByTagName("template")->item($i); $i++) {
                        //Нода, которая, будет импортированна
                        $xslTemplateRoot = $xslTemplate->getElementsByTagName("template")->item($i);
                        //Импортируем в файл стилей
                        $xslTemplateRoot = $xsl->importNode($xslTemplateRoot, true);
                        $xsl->documentElement->appendChild($xslTemplateRoot);
                    }

                    //Контроллер
                    $controller = $template->controller;
                    $controller->load();
                    $class = $component->name . '_controller_' . $controller->name;

                    //Передаём в контроллер xml-документ(по ссылке!), параметры запроса, корневую ноду шаблона
                    $controller = new $class(&$xml, $url->getParam(), $xmlNodeTemplate);
                    //var_dump($controller);
                    //Выполняем
                    $controller->run();

                    //$class = $component->getTtile().'_controller'.
                }
            }


            //Создаём XSLT-процессор
            $xslt = new XSLTProcessor();
            //Импортируем стиль
            $xslt->importStyleSheet($xsl);
            //Трансформируем в html
            $xhtml = $xslt->transformToXML($xml);
            //Выводим
            echo $xhtml;

            //TEST!!!!
            $xsl->formatOutput = true;
            var_dump($xsl->saveXML());

            $xml->formatOutput = true;
            var_dump($xml->saveXML());

            var_dump($xhtml);
            ///TEST!!!!!
            

      /*} catch(Exception $e) {
            //var_dump($e);
            $trace = $e->getTrace();
            Mnix_Core::putMessage($trace[0]['class'], 'err', $e->getMessage(), $trace);
        }*/

        //Выполнение не было прервано
        $this->_crash =false;
    }
    /**
     * Записывает сообщение в лог
     * 
     * @param string $class_name
     * @param string $mode
     * @param string $note
     * @param array $traces
     */
    public static function putMessage($class_name, $mode, $note, $traces = null)
    {
		$class_name = '<font color="blue">'.$class_name.'</font>';
        if (isset($traces)) {
            $note .= '~Trace:';
            foreach ($traces as $trace) $note .= ' '.$trace['class'].$trace['type'].$trace['function'];
        }
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
    /**
     * Засекаем время
     *
     * @param string $thing
     * @param boolean $end
     * @return string
     */
    public static function putTime($thing, $end = false)
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
    public static function putCount($thing, $number = 1)
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
		return date('Y.m.d / H.i', $t).' | '.number_format($t - MNIX_STARTTIME, 5);
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
            Mnix_Core::putMessage(__CLASS__, 'err', 'Not found class: ' . $class);
        }
    }
    /**
     * Определяем путь до класса
     *
     * @param string $class
     * @return string
     */
    protected static function _getPath($class)
    {
        return MNIX_LIB . str_replace('_', '/', $class).'.php';
    }
}