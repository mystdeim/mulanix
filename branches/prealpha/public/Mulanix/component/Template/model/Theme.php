<?php

class Template_model_Theme extends lib_ORM_Prototype {
	protected $_table = 'theme';
	protected $_has_many = array(
		'users' => array(
				'class' => 'Role_model_User',
				'key' 	=> 'theme_id'));
}