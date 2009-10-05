<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Cache
 * @subpackage Test
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */

require_once dirname(dirname(dirname(__FILE__))) . '/boot/bootstrap.php';
/**
 * @see Mnix_Cache
 */
require_once dirname(dirname(dirname(__FILE__))) . '/lib/Mnix/Core.php';
require_once dirname(dirname(dirname(__FILE__))) . '/lib/Mnix/Exception.php';
require_once dirname(dirname(dirname(__FILE__))) . '/lib/Mnix/Exception/Fatal.php';

require_once dirname(dirname(dirname(__FILE__))) . '/lib/Mnix/Cache.php';


/**
 * @category Mulanix
 * @package Mnix_Cache
 * @subpackage Test
 */
class Mnix_CacheSub extends Mnix_Cache
{
    /**
     * __get
     *
     * @param string $name
     * @return mixed
     */
    public function  __get($name)
    {
        return $this->$name;
    }
    /**
     * __set
     *
     * @param string $name
     * @param mixed $value
     */
    public function  __set($name,  $value)
    {
        $this->$name = $value;
    }
    /**
     * Переопределяем метод
     */
    public function mkdir()
    {
        return $this->_mkdir();
    }
    /**
     * Переопределяем метод
     */
    public function removeDir($dir)
    {
        $this->_removeDir($dir);
    }

}