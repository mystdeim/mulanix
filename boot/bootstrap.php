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
define('MNIX_CORE_STARTTIME', microtime(true));
//TODO: при переходе на 5.3 можно покорече записать через __DIR__
define('MNIX_PATH_DIR', dirname(dirname(__FILE__)).'/');
define('MNIX_PATH_LIB', MNIX_PATH_DIR . 'lib/');
define('MNIX_PATH_TEST', MNIX_PATH_DIR . 'test/');
define('MNIX_PATH_BOOT', MNIX_PATH_DIR . 'boot/');
define('MNIX_PATH_TMP', MNIX_PATH_DIR . 'tmp/');
define('MNIX_PATH_CACHE', MNIX_PATH_DIR . 'tmp/cache/');
define('MNIX_PATH_PUBLIC', MNIX_PATH_DIR . 'public/');
define('MNIX_PATH_THEME', MNIX_PATH_PUBLIC . 'theme/');
define('MNIX_PATH_CONFIG', MNIX_PATH_BOOT . 'config.xml');