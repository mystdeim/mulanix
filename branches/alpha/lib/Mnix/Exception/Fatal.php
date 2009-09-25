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
 * Фатальное Исключение
 *
 * @category Mulanix
 * @package Mnix_Exception
 */
class Mnix_Exception_Fatal extends Mnix_Exception
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