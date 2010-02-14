<?php
/**
 * Mulanix Framework
 */
namespace Mnix;

require_once dirname(dirname(dirname(__DIR__))) . '/boot/bootstrap.php';
require_once \Mnix\Path\LIB . '/Mnix/Exception.php';
require_once \Mnix\Path\LIB . '/Mnix/ActiveRecord.php';
require_once \Mnix\Path\LIB . '/Mnix/Db.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Driver.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Driver/Statement.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Criteria.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Select.php';
/**
 * Mulanix Framework
 *
 * @author deim
 */
class ActiveRecordSub extends ActiveRecord
{
    public function _get($name)
    {
        return $this->$name;
    }
    public function _set($name, $value)
    {
        $this->$name = $value;
    }
    public function setAttribute($arr)
    {
        $this->_setAttribute($arr);
    }
    public function getAttribute($attr)
    {
        return $this->_getAttribute($attr);
    }
    public function select()
    {
        $this->_select();
    }
}