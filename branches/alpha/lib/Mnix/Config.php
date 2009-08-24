<?php
/**
 * Mulanix Framework
 *
 * @package Mnix_Config
 * @author deim
 * @copyright 2009
 */
/**
 * @package Mnix_Config
 */
class Mnix_Config
{
    public static function load()
    {
        $cache = new Mnix_Cache(__FILE__);
        $cache->file(MNIX_CONFIG)
                ->hash('f')
                ->load();
        if (!$cache->get()) {
            $writer = new Mnix_Config_Writer(&$cache);
        }
        require_once $cache->path();
    }
}