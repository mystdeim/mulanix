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
    /*public function __construct($dsn, $username = null, $password = null, $driver_options = array(), $logger_callback = NULL)
    {
        parent::__construct($dsn, $username, $password, $driver_options);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if (!$this->getAttribute(PDO::ATTR_PERSISTENT)) {
            $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('DebugPDOStatement', array($this)));
        }
        $this->logger_callback = $logger_callback;
    }*/

}