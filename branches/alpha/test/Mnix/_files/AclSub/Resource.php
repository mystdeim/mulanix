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
class Resource implements \Mnix\Acl\IResource
{
    protected $_role = null;
    public function __construct($role = null)
    {
        $this->_role = $role;
    }
    public function getResourceId()
    {
        return $this->_role;
    }
    public function getPrivileges($role)
    {
        $editOwner = function() use ($role) {
            if ($role->getRoleId() === 'lucky-user') return true;
            else return false;
        };
        
        return array(
            'view'      => null,
            'edit'      => null,
            'editOwner' => $editOwner
        );
    }
}