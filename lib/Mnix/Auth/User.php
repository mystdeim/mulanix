<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Auth
 * @since 2008-10-01
 * @version 2009-07-30
 */
namespace Mnix\Auth;
/**
 * Авторизация пользователей
 *
 * @category Mulanix
 * @package Mnix_Auth
 */
class User extends \Mnix\ActiveRecord
{
    protected $_table = 'mnix_user';
    protected $_hasOne = array(
		'group' => array(
				'class'  => 'Mnix_Auth_Group',
				'id'	 => 'group_id'));
    public static function current()
    {
        return new self(1);
    }
}