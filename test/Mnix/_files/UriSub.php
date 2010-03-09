<?php
/**
 * Mulanix Framework
 */
namespace Mnix;

require_once dirname(dirname(__DIR__)) . '/Helper.php';

require_once \Mnix\Path\LIB . '/Mnix/ActiveRecord/Common.php';
require_once \Mnix\Path\LIB . '/Mnix/ActiveRecord.php';
require_once \Mnix\Path\LIB . '/Mnix/Uri.php';

require_once \Mnix\Path\LIB . '/Mnix/Exception.php';
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
class UriSub extends Uri
{
    public function explode($data)
    {
        return $this->_explode($data);
    }
    protected function _getNext($parent_id, $string)
    {
        $obj = new Uri();
        switch ($parent_id) {
            case 0:
                $obj->set(array('id'=>1, 'name'=>'/', 'parent'=>0));
                break;
            case 1:
                $obj->set(array('id'=>2, 'parent'=>1, 'name'=>'help'));
                break;
        }
        return $obj;
    }
    protected function _getLang($str)
    {

    }
    public function parse($data)
    {
        return $this->_parse($data);
    }
}