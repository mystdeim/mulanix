<?php

class Template_model_Component extends lib_ORM_Prototype {
	protected $_table = 'component';
	protected $_has_many = array(
		'templates' => array(
				'class' => 'Template_model_Template',
				'key' 	=> 'component_id'));
}