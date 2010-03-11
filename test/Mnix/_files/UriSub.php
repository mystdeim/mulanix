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
require_once \Mnix\Path\LIB . '/Mnix/Core/Lang.php';

class PartMok
{
    public $regexp;
    public function __construct($regexp)
    {
        $this->regexp = $regexp;
    }
}

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

    protected function _checkUri($parent, $uri)
    {
        $flag = false;
        if ($parent === 0) $flag = true;
        if ($parent === 1) {
            if ($uri === 'help' || $uri === '404') $flag = true;
        }

        return $flag;
    }
    protected function _getUri($request)
    {
        $obj = new UriSub();

        if ($parent === 1) {
            switch ($request) {
                case 'help':

                    break;

                default:
                    break;
            }
        }
    }
    protected function _getParts()
    {
        switch ($this->_cortege['id']) {
            case 2:
                $arr = array(new PartMok('help'));
                break;
            case 3:
                $arr = array(new PartMok('page'), new PartMok('int'));
                break;
            }
        return $arr;
    }
}