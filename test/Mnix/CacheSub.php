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