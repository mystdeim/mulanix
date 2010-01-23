<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Mnix\Db;
/**
 * Description of Base
 *
 * @author deim
 */
abstract class Base
{
    /**
     * Драйвер базы данных
     *
     * @var Mnix\Db\Driver\Driver
     */
    protected $_driver;
    /**
     * Конструктор
     *
     * @param <type> $driver
     */
    public function __construct($driver)
    {
        $this->_driver = $driver;
    }
}