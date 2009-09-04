<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Core
 * @subpackage Test
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */

/**
 * @see Mnix_Core
 */
require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/lib/Mnix/Core.php';

/**
 * @category Mulanix
 * @package Mnix_Core
 * @subpackage Test
 */
class Mnix_CoreSub extends Mnix_Core
{
    /**
     * Переопределяем метод в публиный
     *
     * @param string $class
     * @return string
     */
    public function getPath($class)
    {
        return $this->_getPath($class);
    }

    /**
     * Возвращаем счетчик
     *
     * @return array
     */
    public function getCount()
    {
        return self::$_count;
    }
}