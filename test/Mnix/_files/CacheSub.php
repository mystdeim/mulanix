<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */

namespace Mnix;

require_once dirname(dirname(__DIR__)) . '/boot/bootstrap.php';
require_once dirname(dirname(__DIR__)) . '/lib/Mnix/Exception.php';
//require_once dirname(dirname(__DIR__)) . '/lib/Mnix/Exception/Fatal.php';
require_once dirname(dirname(__DIR__)) . '/lib/Mnix/Cache.php';

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