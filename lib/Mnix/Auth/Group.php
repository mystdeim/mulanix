<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Auth
 * @version $Id$
 */
/**
 * Группа пользователей
 *
 * @category Mulanix
 * @package Mnix_Auth
 */
class Mnix_Auth_Group extends Mnix_ORM_Prototype
{
    protected $_table = 'mnix_group';
    protected $_has_many = array(
		'users' => array(
			'class'  => 'Mnix_Auth_User',
			'fk'	 => 'group_id'),
        'theme' => array(
            'class'  => 'Mnix_Engine_Theme',
            'jtable' => 'mnix_group2page2theme',
            'id'     => 'group_id',
            'fk'     => 'theme_id')
    );
}