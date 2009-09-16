<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Exception
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */
/**
 * Исключение
 *
 * @category Mulanix
 * @package Mnix_Exception
 */
class Mnix_Exception extends Exception
{
    /**
     *
     * @param string $message
     * @param int $code 
     */
    public function __construct($message = false, $code = false)
    {
        parent::__construct($message, $code);
    }
}