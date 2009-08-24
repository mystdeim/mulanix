<?php
 /**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id$
 */
define('MNIX_CORE_STARTTIME', microtime(true));
define('MNIX_PATH_DIR', realpath('../').'/');
define('MNIX_PATH_LIB', MNIX_PATH_DIR . 'lib/');
define('MNIX_PATH_TEST', MNIX_PATH_DIR . 'test/');
define('MNIX_PATH_BOOT', MNIX_PATH_DIR . 'boot/');
define('MNIX_PATH_TMP', MNIX_PATH_DIR . 'tmp/');
define('MNIX_PATH_CACHE', MNIX_PATH_DIR . 'tmp/cache/');
define('MNIX_PATH_PUBLIC', MNIX_PATH_DIR . 'public/');
define('MNIX_PATH_THEME', MNIX_PATH_PUBLIC . 'theme/');
define('MNIX_PATH_CONFIG', MNIX_PATH_BOOT . 'config.xml');
if (!defined('PATH_SEPARATOR')) define('PATH_SEPARATOR', getenv('COMSPEC')? ';' : ':');
ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.MNIX_PATH_DIR);
require_once MNIX_PATH_LIB . 'Mnix/Core.php';
$app = new MNIX_Core();
$app->run();