<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */
namespace Mnix;
/**
 * Исключение
 *
 * @category Mulanix
 */
class Exception extends \Exception
{
    /**
     * Конструктор
     *
     * @param string $message
     * @param int $code 
     */
    public function __construct($message = false, $code = false)
    {
        parent::__construct($message, $code);
        //TODO: сюда надо дописать логирование ошибки
    }
}