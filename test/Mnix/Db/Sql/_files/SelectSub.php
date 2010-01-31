<?php
/**
 * Mulanix Framework
 *
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */
namespace Mnix\Db\Sql;

require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/boot/bootstrap.php';
//require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Exception.php';
//require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Core.php';
//require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Db.php';
//require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Db/Base.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Db/Sql/Criterion.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Db/iSelect.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Db/Sql/Select.php';
//require_once dirname(dirname(__DIR__)) . '/Driver/_files/XmlSub.php';
/**
 *
 */
class SelectSub extends Select
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