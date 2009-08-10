<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Db
 * @version 2009-07-23
 * @since 2009-07-23
 */
/**
 * @category Mulanix
 * @package Mnix_Db
 */
class Mnix_Db_Select_Exception extends Exception
{
    public function  __construct($message, $code = false)
    {
        $this->message = $message;
    }
}