<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix
 * @subpackage Test
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */
 /**
  * Подключаем файл начальной загрузки
  */
require_once '../boot/bootstrap.php';
/**
 * Подключаем ядро
 */
require_once MNIX_PATH_LIB . 'Mnix/Core.php';
require_once MNIX_PATH_TEST . 'lib/Mnix/Core.php';
$app = new Test_Mnix_Core();
$app->run();