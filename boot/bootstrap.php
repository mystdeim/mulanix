<?php
/**
 * Mulanix Framework
 *
 * Первоначальная инициализация, определение путей
 *
 * @category Mulanix
 * @package Mnix
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */
/**
 * Время начала работы
 */
define('Mnix\Core\STARTTIME', microtime(true), false);
/**
 * Пути
 */
define('Mnix\Path\DIR', dirname(__DIR__) . '/', false);
define('Mnix\Path\LIB', \Mnix\Path\DIR . 'lib', false);
define('Mnix\Path\TEST', \Mnix\Path\DIR . 'test', false);
define('Mnix\Path\BOOT', \Mnix\Path\DIR . 'boot', false);
define('Mnix\Path\TMP', \Mnix\Path\DIR . 'tmp', false);
define('Mnix\Path\CACHE', \Mnix\Path\DIR . 'tmp/cache', false);
define('Mnix\Path\PUBLIC', \Mnix\Path\DIR . 'public', false);
define('Mnix\Path\THEME', \Mnix\Path\DIR . 'theme', false);
define('Mnix\Path\CONFIG', \Mnix\Path\BOOT . '/config.xml', false);