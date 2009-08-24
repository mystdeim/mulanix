<?php
 /**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Config
 * @version $Id$
 */
/**
 * Преобразование xml в константы
 *
 * @category Mulanix
 * @package Mnix_Config
 */
class Mnix_Config_Writer
{
    public function  __construct($cache)
    {
        $config = simplexml_load_file(MNIX_CONFIG);
        $arr = $this->SimpleXmlToArray($config);
        $arrDefine = $this->ArrayToDefine($arr, $parent, $deep);
        $content = '<?php';
        foreach ($arrDefine as $key => $value) $content .= "\n define('".$key."','".$value."',true);";
        $cache->clear();
        $cache->file(MNIX_CONFIG)
                ->hash('f')
                ->put($content)
                ->save();    
    }
    protected function SimpleXmlToArray($xml)
    {
		if ($xml instanceof SimpleXMLElement) {
			$xml = (array)$xml;
			foreach ($xml as &$node) $this->SimpleXmlToArray(&$node);
		}
		return $xml;
	}
    protected function ArrayToDefine($arr, $parent = '', $deep = 0, $arrDefine = array())
    {
		$deep++;
		foreach ($arr as $key => $value) {
			if (is_array($value)) $arrDefine = $this->ArrayToDefine($value, $parent.$key.'_', $deep, $arrDefine);
            else $arrDefine[strtoupper($parent.$key)] = $value;
		}
        return $arrDefine;
	}
}