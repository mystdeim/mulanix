<?php
 /**
 * Mulanix Framework
 *
 * @category Mulanix
 * @since 2009-04-14
 * @version 2009-08-07
 */
define('MNIX_STARTTIME', microtime(true));
define('MNIX_DIR', realpath('../').'/');
define('MNIX_LIB', MNIX_DIR . 'lib/');
define('MNIX_TEST', MNIX_DIR . 'test/');
define('MNIX_BOOT', MNIX_DIR . 'boot/');
define('MNIX_TMP', MNIX_DIR . 'tmp/');
define('MNIX_CACHE', MNIX_DIR . 'tmp/cache/');
define('MNIX_PUBLIC', MNIX_DIR . 'public/');
define('MNIX_THEME', MNIX_PUBLIC . 'theme/');
define('MNIX_CONFIG', MNIX_BOOT . 'config.xml');
if (!defined('PATH_SEPARATOR')) define('PATH_SEPARATOR', getenv('COMSPEC')? ';' : ':');
ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.MNIX_DIR);
require_once MNIX_LIB . 'Core.php';
$app = new Mnix_Core();
$app->run();