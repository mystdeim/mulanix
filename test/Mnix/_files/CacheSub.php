<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */

namespace Mnix;

require_once dirname(dirname(dirname(__DIR__))) . '/boot/bootstrap.php';
require_once \Mnix\Path\LIB . '/Mnix/Exception.php';
require_once \Mnix\Path\LIB . '/Mnix/Cache.php';

/**
 * @category Mulanix
 */
class CacheSub extends Cache
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
     * Переопределяем метод
     */
    public function mkdir()
    {
        return $this->_mkdir();
    }

}