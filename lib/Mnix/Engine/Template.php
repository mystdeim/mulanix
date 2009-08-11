<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Engine
 * @since 2008-10-01
 * @version 2009-08-06
 */
/**
 * Шаблон
 *
 * @category Mulanix
 * @package Mnix_Engine
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