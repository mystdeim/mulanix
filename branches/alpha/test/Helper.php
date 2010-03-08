<?php
/**
 * Mulanix Framework
 *
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */

require_once dirname(__DIR__) . '/boot/bootstrap.php';
require_once __DIR__ . '/_files/DatabaseTestCaseSub.php';

define('Mnix\Core\BASE', 'base0');

define('Mnix\Db\base0\DBMS', 'sqlite');
define('Mnix\Db\base0\BASE', ':memory:');
define('Mnix\Db\base0\XML', __DIR__ . '/_files/testDB.xml');

define('Mnix\Db\base1\DBMS', 'mysql');
define('Mnix\Db\base1\USER', 'user');
define('Mnix\Db\base1\PASS', 'password');
define('Mnix\Db\base1\HOST', 'localhost');
define('Mnix\Db\base1\BASE', 'basename');

define('Mnix\Db\base2\DBMS', 'sqlite');
define('Mnix\Db\base2\BASE', 'base.db');

define('Mnix\Polyglot\DEFAULT', 'en');

/**
 * Helping class
 */
class Helper
{
    
}