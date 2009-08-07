<?php

class Role_model_Group extends lib_ORM_Prototype {
	protected $_table = 'group';
	protected $_has_many = array(
		'users' => array(
				'class' => 'Role_model_User',
				'key' 	=> 'group_id'),
		'templates' => array(
				'class' => 'Template_model_Template',
				'key' 	=> 'group_id',
				'fk' 	=> 'template_id',
				'jtable' => 'group2template'));
}