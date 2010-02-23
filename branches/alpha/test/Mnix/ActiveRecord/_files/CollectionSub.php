<?php
/**
 * Mulanix Framework
 */
namespace Mnix\ActiveRecord;

require_once dirname(dirname(dirname(__DIR__))) . '/Helper.php';

require_once \Mnix\Path\LIB . '/Mnix/ActiveRecord/Common.php';
require_once \Mnix\Path\LIB . '/Mnix/ActiveRecord/Collection.php';

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
class CollectionSub extends Collection
{
    protected static $_driverSub = NULL;
    public function __get($name)
    {
        return $this->$name;
    }
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    protected function _getDriver()
    {
        $this->_driver = self::$_driverSub;
        return $this->_driver;
    }
    public static function setDriverToSub($driver)
    {
        self::$_driverSub = $driver;
    }
}