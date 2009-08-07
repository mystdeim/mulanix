<?php

class Role_model_User extends lib_ORM_Prototype {
	protected $_table = 'user';
	protected $_has_one = array('group' => array(
											'class' => 'Role_model_Group',
											'fk' => 'group_id'),
								'theme' => array(
											'class' => 'Template_model_Theme',
											'fk' => 'theme_id'));
	static public function Factory() {
		return new Role_model_User(1);
	}
}