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
    //const FILE = Path\CONFIG;
    protected $_path;
    /*public static function load()
    {
        $cache = new Cache();
        $cache->file(Path\CONFIG)
                ->hash('f')
                ->load();
        if (!$cache->get()) {
            $writer = new Mnix_Config_Writer(&$cache);
        }
        require_once $cache->path();
    }*/
    public function __construct($path)
    {
        $this->_path = $path;
    }
    public function load()
    {
        $cache = new Cache();
        $cache->name(md5_file($this->_path))
              ->hash(false);
        if (!$cache->load()) {
            var_dump($cache);
            $this->_write($cache);
        }
    }
    protected function _write($cache)
    {
        $cache->data('test')
              ->save();
    }
}