<?php
/**
 * Mulanix Framework
 *
 * @package Mnix_Auth
 * @author deim
 * @copyright 2009
 */
/**
 * @package Mnix_Auth_User
 */
class Mnix_Auth_User extends Mnix_ORM_Prototype
{
    protected $_table = 'mnix_user';
    protected $_has_one = array(
		'group' => array(
				'class'  => 'Mnix_Auth_Group',
				'fk'	 => 'group_id'));
    public static function current()
    {
        return new Mnix_Auth_User(1);
    }
}