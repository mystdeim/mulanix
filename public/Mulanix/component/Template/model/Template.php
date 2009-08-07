<?php

class Template_model_Template extends lib_ORM_Prototype {
	protected $_table = 'template';
	protected $_has_one = array(
		'component' => array(
				'class'  => 'Template_model_component',
				'fk'	 => 'component_id'),
		'controller' => array(
				'class'  => 'Template_model_controller',
				'fk'	 => 'controller_id'));
	protected $_has_many = array(
		'groups' => array(
				'class'  => 'Template_model_Block',
				'key' 	 => 'template_id',
				'fk' 	 => 'group_id',
				'jtable' => 'group2template'),
		'pages' => array(
				'class'  => 'Template_model_Page',
				'key' 	 => 'template_id',
				'fk' 	 => 'page_id',
				'jtable' => 'page2template'));
}