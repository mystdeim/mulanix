<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Engine
 * @since 2008-10-01
 * @version 2009-07-30
 */
/**
 * Страница
 *
 * @category Mulanix
 * @package Mnix_Engine
 */
class Mnix_Engine_Page extends Mnix_ORM_Prototype
{
    protected $_table = 'mnix_page';
    protected $_has_many = array(
		'regions' => array(
				'class'  => 'Mnix_Engine_Region',
				'id' 	 => 'region_id',
				'fk' 	 => 'page_id',
				'jtable' => 'mnix_page2template2region'));
}