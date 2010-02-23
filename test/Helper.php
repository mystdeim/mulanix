<?php
/**
 * Mulanix Framework
 *
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */

require_once dirname(__DIR__) . '/boot/bootstrap.php';

define('Mnix\Core\BASE', 'base0', false);

define('Mnix\Db\base0\DBMS', 'sqlite', false);
define('Mnix\Db\base0\BASE', ':memory:', false);

define('Mnix\Db\base1\DBMS', 'mysql', false);
define('Mnix\Db\base1\USER', 'user', false);
define('Mnix\Db\base1\PASS', 'password', false);
define('Mnix\Db\base1\HOST', 'localhost', false);
define('Mnix\Db\base1\BASE', 'basename', false);

define('Mnix\Db\base2\DBMS', 'sqlite', false);
define('Mnix\Db\base2\BASE', 'base.db', false);

/**
 * Helping class
 */
class Helper
{
    
}