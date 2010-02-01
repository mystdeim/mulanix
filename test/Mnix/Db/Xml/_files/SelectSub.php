<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */
namespace Mnix\Db\Xml;

require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/boot/bootstrap.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Exception.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Core.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Db.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Db/Base.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Db/Xml/Base.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Db/iSelect.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Db/Xml/Select.php';
require_once dirname(dirname(__DIR__)) . '/Driver/_files/XmlSub.php';

class SelectSub extends Select
{
    public function load()
    {
        return $this->_load();
    }

    public function  __get($name)
    {
        return $this->$name;
    }

    public function  __set($name,  $value)
    {
        $this->$name = $value;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this, $name), $arguments);
    }
}