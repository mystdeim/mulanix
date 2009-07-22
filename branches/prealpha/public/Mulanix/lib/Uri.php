<?php
/**
 * Mulanix Framework
 *
 * @package Mnix_Uri
 * @author deim
 * @copyright 2009
 */
/**
 * @package Mnix_Uri
 */
class Mnix_Uri extends Mnix_ORM_Prototype
{
    protected $_table = 'mnix_uri';
    protected $_has_one = array(
		'page' => array(
				'class'  => 'Mnix_Engine_Page',
				'fk'	 => 'page_id'));
    protected $_url;
    protected static function _parse()
    {
        
    }
    public static function current()
    {
        self::_parse();
        return new Mnix_Uri(1);
    }
}