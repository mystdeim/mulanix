<?php
/**
 * Mulanix Framework
 */
namespace Mnix\ActiveRecord;

require_once dirname(dirname(dirname(__DIR__))) . '/Helper.php';

require_once \Mnix\Path\LIB . '/Mnix/ActiveRecord/Common.php';

require_once \Mnix\Path\LIB . '/Mnix/Exception.php';
require_once \Mnix\Path\LIB . '/Mnix/Db.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Driver.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Driver/Statement.php';
/**
 * Mulanix Framework
 *
 * @author deim
 */
class CommonSub extends Common
{
    protected $_table = 'table';
    public function getDriver()
    {
        return $this->_getDriver();
    }
    public function placeHolder($condition, $data = null)
    {
        return $this->_placeHolder($condition, $data);
    }
    public function shielding($value, $mode, &$bind)
    {
        return $this->_shielding($value, $mode, $bind);
    }
}