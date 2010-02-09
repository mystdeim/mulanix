<?php
/**
 * Mulanix Framework
 */
namespace Mnix\Db\Driver;

require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/boot/bootstrap.php';
require_once \Mnix\Path\LIB . '/Mnix/Exception.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Driver.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Driver/Statement.php';

/**
 * Mulanix Framework
 *
 * @author deim
 */
class StatementSub extends Statement
{
    public function __construct($pdo)
    {
        parent::__construct($pdo);
    }
}