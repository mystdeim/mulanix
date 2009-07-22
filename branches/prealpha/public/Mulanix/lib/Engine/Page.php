<?php
/**
 * Mulanix Framework
 *
 * @package Mnix_Engine
 * @author deim
 * @copyright 2009
 */
/**
 * @package Mnix_Engine_Page
 */
class Mnix_Engine_Page extends Mnix_ORM_Prototype
{
    protected $_table = 'mnix_page';
    protected $_has_many = array(
		'template' => array(
				'class'  => 'Mnix_Engine_Template',
				'key' 	 => 'page_id',
				'fk' 	 => 'template_id',
				'jtable' => 'mnix_page2template'));
}