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
abstract class Common
{
    /**
     * Драйвер базы данных
     *
     */
    protected $_driver;
    /**
     * Конструктор
     *
     * @param $driver Драйвер базы данных
     */
    public function __construct($driver)
    {
        $this->_driver = $driver;
    }
}