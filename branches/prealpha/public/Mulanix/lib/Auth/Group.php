<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Auth
 * @since 2008-10-01
 * @version 2009-07-30
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
				'fk'	 => 'group_id'));
}