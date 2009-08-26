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
 * Начальные параметры запуска
 */
require_once dirname(dirname(dirname(__FILE__))) . '/boot/bootstrap.php';
/**
 * Регистрируем автозагрузку
 */
spl_autoload_register('autoload');

/**
 * Автозагрузка классов
 *
 * @param string $class
 */
function autoload($class)
{
    require_once autoloadPath($class);
}
/**
 * Вычисление пути для подгружаемого класса
 *
 * @param string $class
 * @return string
 */
function autoloadPath($class)
{
    if (preg_match('/.*(Sub|Test)$/', $class)) $path = MNIX_PATH_TEST . str_replace('_', '/', $class).'.php';
    else $path = MNIX_PATH_LIB . str_replace('_', '/', $class).'.php';
    return $path;
}