<?php
 /**
 * Mulanix Framework
 *
 * @version $Id$
 */
namespace Mnix;
/**
 * Конфиг
 */
class Config
{
    public static function load()
    {
        $cache = new Cache();
        $cache->file(Path\CONFIG)
                ->hash('f')
                ->load();
        if (!$cache->get()) {
            $writer = new Mnix_Config_Writer(&$cache);
        }
        require_once $cache->path();
    }
}