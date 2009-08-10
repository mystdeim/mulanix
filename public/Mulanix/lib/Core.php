<?php
 /**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Core
 * @since 2008-10-01
 * @version 2009-07-30
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
        //Проверяем разрешение на страницу группе
        //$acl = new Mnix_Acl();
        //$acl->role($group);
        //if ($acl->isAllowed('view', $page)) {

            //Получаем шаблоны
            //$templates = $page->getTemplate();
 
            //Обходим шаблоны
            //foreach ($templates as $template) {
                //var_dump($template);

                //Смотрим права
                //$rights = $acl->allowed($template);
                //var_dump($rights);
                //if (isset($rights)) {

                //}

                //Создаём контроллер и выполняем его
            //}
        //} else {
            //die('Pemission denied!');
        //}
        //$a++;
        try {
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
            //Получаем блоки
            $blocks = $page->getBlocks();
            //Получаем тему
            $theme = $user->getTheme();

            //Создаём дом-документ для файлв стилей
            $xsl = new domDocument('1.0', 'UTF-8');
            $xsl->preserveWhiteSpace = false;
            //Грузим мастер-шаблон
            $xsl->load(MNIX_DIR.'theme/'.$theme->getName().'/master.xsl');
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
            $xmlNodeTitleText = new domText($page->getName());
            $xmlNodeTitle->appendChild($xmlNodeTitleText);
            $xmlNodeHead->appendChild($xmlNodeTitle);

            //Обходим блоки
            foreach ($blocks as $block) {
                //var_dump($block);

                //Создаём в xml ноду с названием блока
                $xmlNodeBlock = $xml->createElement($block->getName());
                $xmlNodeBody->appendChild($xmlNodeBlock);

                //Получаем шаблоны, соответствующие блоку
                $templates = $block->getTemplates();
                //Обходим шаблоны
                foreach ($templates as $template) {
                    //var_dump($template);

                    //Создаём в xml ноду с названием шаблона
                    $xmlNodeTemplate = $xml->createElement($template->getName());
                    $xmlNodeBlock->appendChild($xmlNodeTemplate);

                    //Компонент шаблона
                    $component = $template->getComponent();
                    //Создаём домдокумент из шаблона
                    $file = MNIX_DIR.'lib/'.$component->getName().'/template/'.$template->getName().'.xsl';
                    $xslTemplate = new domDocument('1.0', 'UTF-8');
                    $xslTemplate->preserveWhiteSpace = false;
                    $xslTemplate->load($file);
                    //Нода, которая, будет импортированна
                    $xslTemplateRoot = $xslTemplate->getElementsByTagName("template")->item(0);
                    //Импортируем в файл стилей
                    $xslTemplateRoot = $xsl->importNode($xslTemplateRoot, true);
                    $xsl->documentElement->appendChild($xslTemplateRoot);

                    //Контроллер
                    $controller = $template->getController();
                    //$controller->load();
                    //var_dump($controller);
                }
            }
            
            //TEST!!!!
            $xsl->formatOutput = true;
            var_dump($xsl->saveXML());
            
            $xml->formatOutput = true;
            var_dump($xml->saveXML());
            ///TEST!!!!!

            //Создаём XSLT-процессор
            $xslt = new XSLTProcessor();
            //Импортируем стиль
            $xslt->importStyleSheet($xsl);
            //Трансформируем в html
            $xhtml = $xslt->transformToXML($xml);
            //echo $xhtml;

            var_dump($xhtml);

            } catch(Exception $e) {
            //var_dump($e);
            $trace = $e->getTrace();
            Mnix_Core::putMessage($trace[0]['class'], 'err', $e->getMessage(), $trace);
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