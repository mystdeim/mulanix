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
//require_once dirname(dirname(__DIR__)) . '/lib/Mnix/Exception/Fatal.php';
require_once dirname(dirname(__DIR__)) . '/lib/Mnix/Db.php';

define('Mnix\Db\Base\TYPE', 'MySql', false);

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
    public static function instance()
    {
        return parent::$_instance;
    }
    /**
     * Чистим реестр
     */
    public static function clearInstance()
    {
        ob_start();
        parent::$_instance = null;
        ob_end_clean();
    }
    /**
     * Переопределяем защищенный коструктор
     *
     * @param mixed $param 
     */
    public function __construct($param)
    {
        parent::__construct($param);
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