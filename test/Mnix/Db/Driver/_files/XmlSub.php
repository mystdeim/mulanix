<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */
namespace Mnix\Db\Driver;

require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/boot/bootstrap.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Exception.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Core.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Db.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Db/Driver/Xml.php';

class XmlSub extends Xml
{
    public function load()
    {
        return $this->_load();
    }
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
}