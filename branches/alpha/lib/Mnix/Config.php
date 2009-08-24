<?php
 /**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Config
 * @version $Id$
 */
/**
 * Конфиг
 *
 * @category Mulanix
 * @package Mnix_Config
 */
class Mnix_Config
{
    public static function load()
    {
        $cache = new Mnix_Cache(__FILE__);
        $cache->file(MNIX_PATH_CONFIG)
                ->hash('f')
                ->load();
        if (!$cache->get()) {
            $writer = new Mnix_Config_Writer(&$cache);
        }
        require_once $cache->path();
    }
}