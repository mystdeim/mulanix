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
    protected $_path;
    public function __construct($path)
    {
        $this->_path = $path;
    }
    public function load()
    {
        $cache = new Cache();
        $cache->name(md5_file($this->_path))
              ->serialize(false)
              ->hash(false);
        if (!$cache->load()) {
            $cache->clear();
            $content = $this->_write();
            $cache->data($content)->save();
        }
        require_once $cache->file();
    }
    protected function _write()
    {
        $xml = simplexml_load_file($this->_path);
        $content = '<?php';

        $walk = function($xml, $prefix) use(&$content, &$walk) {

            foreach((array)$xml as $attr => $value) {
                if ($value instanceof \SimpleXMLElement) {
                    $attr[0] = strtoupper($attr[0]);
                    $walk($value, $prefix . $attr . '\\');
                } else {
                    if ($value !== 'true' && $value !== 'false') {
                        $int = (int)$value;
                        $float = (float)$value;
                        if ($value !== (string)$int) {
                            if ($value !== (string)$float) $value = '\'' . $value . '\'';
                        }
                    }
                    $content .= "\ndefine('".$prefix . strtoupper($attr) . "'," . $value . ');';
                }
            }
        };
        $walk($xml, null);

        return $content;
    }
}