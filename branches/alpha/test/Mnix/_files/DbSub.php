<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */
namespace Mnix;

require_once dirname(dirname(__DIR__)) . '/Helper.php';
require_once \Mnix\Path\LIB . '/Mnix/Exception.php';
require_once \Mnix\Path\LIB . '/Mnix/Db.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Driver.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Driver/Statement.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Criteria.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Select.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Update.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Insert.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Delete.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Base.php';

/*require_once dirname(dirname(dirname(__DIR__))) . '/boot/bootstrap.php';
require_once dirname(dirname(dirname(__DIR__))) . '/lib/Mnix/Exception.php';
require_once dirname(dirname(dirname(__DIR__))) . '/lib/Mnix/Core.php';

require_once dirname(dirname(dirname(__DIR__))) . '/lib/Mnix/Db.php';
require_once dirname(dirname(dirname(__DIR__))) . '/lib/Mnix/Db/Driver/Xml.php';
require_once dirname(dirname(dirname(__DIR__))) . '/lib/Mnix/Db/Driver/MySql.php';
require_once dirname(dirname(dirname(__DIR__))) . '/lib/Mnix/Db/Common.php';

require_once dirname(dirname(dirname(__DIR__))) . '/lib/Mnix/Db/ISelect.php';

require_once dirname(dirname(dirname(__DIR__))) . '/lib/Mnix/Db/Sql/Criteria.php';
require_once dirname(dirname(dirname(__DIR__))) . '/lib/Mnix/Db/Sql/Select.php';

require_once dirname(dirname(dirname(__DIR__))) . '/lib/Mnix/Db/Xml/Common.php';
require_once dirname(dirname(dirname(__DIR__))) . '/lib/Mnix/Db/Xml/Select.php';

define('Mnix\Core\Log\SYSTEM', true);
define('Mnix\Core\Log\WARNING', true);
define('Mnix\Core\Log\ERROR', true);
define('Mnix\Core\Log\DEBUG', false);*/

define('Mnix\Db\base3\DBMS', 'faultbase', false);
define('Mnix\Db\base3\BASE', 'base.db', false);


/**
 * Mulanix
 */
class DbSub extends Db
{
    public static function instances()
    {
        return self::$_instances;
    }
    public static function clearInstance()
    {
        parent::$_instances = null;
    }
    public function __construct($dsn, $user, $pass)
    {
        $this->_driver = $dsn . $user . $pass;
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