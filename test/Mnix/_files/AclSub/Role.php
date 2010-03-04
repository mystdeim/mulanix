<?php
/**
 * Mulanix Framework
 */
namespace Mnix\AclSub;
/**
 * Mulanix Framework
 *
 * @author deim
 */
class Role implements \Mnix\Acl\IRole
{
    protected $_role = null;
    public function __construct($role = null)
    {
        $this->_role = $role;
    }
    public function getRoleId()
    {
        return $this->_role;
    }
}