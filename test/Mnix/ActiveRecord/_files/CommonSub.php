<?php
/**
 * Mulanix Framework
 */
namespace Mnix\ActiveRecord;

require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/boot/bootstrap.php';
require_once \Mnix\Path\LIB . '/Mnix/ActiveRecord/Common.php';
/**
 * Mulanix Framework
 *
 * @author deim
 */
class CommonSub extends Common
{
    protected $_table = 'table';
    public function placeHolder($condition, $data = null)
    {
        return $this->_placeHolder($condition, $data);
    }
    public function shielding($value, $mode, &$bind)
    {
        return $this->_shielding($value, $mode, $bind);
    }
}