<?php
/**
 * Mulanix Framework
 */
namespace Mnix\Db;
/**
 * Mulanix Framework
 *
 * @author deim
 */
class Driver extends \PDO
{
    public function __construct($dsn, $username = null, $password = null, $driver_options = array())
    {
        parent::__construct($dsn, $username, $password, $driver_options);
        $this->setAttribute(\PDO::ATTR_STATEMENT_CLASS, array('Mnix\Db\Driver\Statement', array($this)));
    }

}