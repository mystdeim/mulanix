<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix
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
$app = new MNIX_Core();
$app->run();