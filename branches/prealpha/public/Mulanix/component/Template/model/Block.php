<?php

class Template_model_Block extends lib_ORM_Prototype {
	protected $_table = 'block';
	protected $_has_many = array(
		'templates' => array(
				'class' => 'Template_model_Template',
				'key' 	=> 'block_id',
				'fk' 	=> 'template_id',
				'table' => 'block2template'),
		'groups' => array(
				'class' => 'Role_model_Group',
				'key' 	=> 'block_id',
				'fk' 	=> 'group_id',
				'table' => 'group2block'));
}