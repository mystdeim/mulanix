<?php
/**
 * Mulanix Framework
 *
 * @version $Id$
 */
namespace Mnix;
 /**
  * Подключаем файл начальной загрузки
  */
require_once '../boot/bootstrap.php';
/**
 * Подключаем ядро
 */
require_once Path\LIB . '/Mnix/Core.php';
$app = Core::instance();
$app->run();