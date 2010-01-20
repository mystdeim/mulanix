<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */
namespace Mnix;

require_once dirname(dirname(__DIR__)) . '/boot/bootstrap.php';
require_once dirname(dirname(__DIR__)) . '/lib/Mnix/Exception.php';
require_once dirname(dirname(__DIR__)) . '/lib/Mnix/Core.php';
require_once dirname(dirname(__DIR__)) . '/lib/Mnix/Db.php';
require_once dirname(dirname(__DIR__)) . '/lib/Mnix/Db/Driver/Xml.php';
require_once dirname(dirname(__DIR__)) . '/lib/Mnix/Db/Driver/MySql.php';

define('Mnix\Core\Log\SYSTEM', true);
define('Mnix\Core\Log\WARNING', true);
define('Mnix\Core\Log\ERROR', true);
define('Mnix\Core\Log\DEBUG', false);

define('Mnix\Core\BASE', 'base0', false);
define('Mnix\Db\base0\TYPE', 'xml', false);
define('Mnix\Db\base0\FILE', 'file.xml', false);
define('Mnix\Db\base1\TYPE', 'mysql', false);
define('Mnix\Db\base1\LOGIN', 'user', false);
define('Mnix\Db\base1\PASS', 'password', false);
define('Mnix\Db\base1\HOST', 'localhost', false);
define('Mnix\Db\base1\BASE', 'basename', false);

/**
 * @category Mulanix
 */
class DbSub extends Db
{
    /**
     * Возвращаем реестр
     *
     * @return array
     */
    public static function instances()
    {
        return self::$_instances;
    }
    /**
     * Чистим реестр
     */
    public static function clearInstance()
    {
        ob_start();
        parent::$_instances = null;
        ob_end_clean();
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
}