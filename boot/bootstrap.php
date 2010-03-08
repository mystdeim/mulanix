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
define('Mnix\Core\STARTTIME', microtime(true));
/**
 * Пути
 */
define('Mnix\Path\DIR', dirname(__DIR__) . '/');
define('Mnix\Path\LIB', \Mnix\Path\DIR . 'lib');
define('Mnix\Path\TEST', \Mnix\Path\DIR . 'test');
define('Mnix\Path\BOOT', \Mnix\Path\DIR . 'boot');
define('Mnix\Path\TMP', \Mnix\Path\DIR . 'tmp');
define('Mnix\Path\CACHE', \Mnix\Path\DIR . 'tmp/cache');
define('Mnix\Path\DB', \Mnix\Path\DIR . 'var/db');
define('Mnix\Path\PUBLIC', \Mnix\Path\DIR . 'public');
define('Mnix\Path\THEME', \Mnix\Path\DIR . 'theme');
define('Mnix\Path\CONFIG', \Mnix\Path\BOOT . '/config.xml');