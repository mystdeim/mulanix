<?php
/**
 * Тестирование
 *
 * Для проведения всех тестов: запустить http://localhost/test
 * Для конкретного теста: http://localhost/test/__CLASS__
 * где __CLASS__ названия определённого классна, например: Test_Mnix_DbTest
 * 
 * @author deim
 * Created on 24.04.2009, 11:01:50
 */
define('MNIX_CORE_STARTTIME', microtime(true));
define('MNIX_PATH_DIR', realpath('../').'/');
define('MNIX_PATH_LIB', MNIX_PATH_DIR . 'lib/');
define('MNIX_PATH_TEST', MNIX_PATH_DIR . 'test/');
define('MNIX_PATH_BOOT', MNIX_PATH_DIR . 'boot/');
define('MNIX_PATH_TMP', MNIX_PATH_DIR . 'tmp/');
define('MNIX_PATH_VAR', MNIX_PATH_DIR . 'var/');
define('MNIX_PATH_CACHE', MNIX_PATH_DIR . 'tmp/cache/');
define('MNIX_PATH_PUBLIC', MNIX_PATH_DIR . 'htdocs/');
define('MNIX_PATH_THEME', MNIX_PATH_PUBLIC . 'theme/');
define('MNIX_PATH_CONFIG', MNIX_PATH_BOOT . 'config.xml');
if (!defined('PATH_SEPARATOR')) define('PATH_SEPARATOR', getenv('COMSPEC')? ';' : ':');
ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.MNIX_PATH_DIR);
require_once MNIX_PATH_LIB . 'Mnix/Core.php';
require_once MNIX_PATH_TEST . 'lib/Mnix/Core.php';
$app = new Test_Mnix_Core();
$app->run();