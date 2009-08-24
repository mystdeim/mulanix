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
 * Авторизация пользователей
 *
 * @category Mulanix
 * @package Mnix_Auth
 */
class Mnix_Auth_User extends Mnix_ORM_Prototype
{
    protected $_table = 'mnix_user';
    protected $_has_one = array(
		'group' => array(
				'class'  => 'Mnix_Auth_Group',
				'id'	 => 'group_id'),
		'theme' => array(
				'class'  => 'Mnix_Engine_Theme',
				'id'	 => 'theme_id'));
    public static function current()
    {
        return new Mnix_Auth_User(1);
    }
}