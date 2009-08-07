<?php
/**
 * Mulanix Framework
 *
 * @package Mnix_Engine
 * @author deim
 * @copyright 2009
 */
/**
 * @package Mnix_Engine_Template
 */
class Mnix_Engine_Template extends Mnix_ORM_Prototype
{
    protected $_table = 'mnix_template';
    protected $_has_one = array(
		'controller' => array(
				'class'  => 'Mnix_Engine_Controller',
				'id'	 => 'controller_id'),
		'component' => array(
				'class'  => 'Mnix_Engine_Component',
				'id'	 => 'component_id'));
}