<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */
namespace Mnix;

require_once dirname(dirname(__DIR__)) . '/Helper.php';
require_once \Mnix\Path\LIB . '/Mnix/Acl.php';
require_once \Mnix\Path\LIB . '/Mnix/Acl/IRole.php';
require_once \Mnix\Path\LIB . '/Mnix/Acl/IResource.php';

/**
 * Mulanix
 */
class AclSub extends Acl
{
    protected function _getFromDb()
    {
        switch ($this->_action) {
            case 'view':
                $permission = true;
                break;
            case 'edit':
                if ($this->_role->getRoleId() === 'group-admin') $permission = true;
                else $permission = false;
                break;
            case 'editOwner':
                if (in_array($this->_role->getRoleId(), 
                        array('group-admin', 'group-member', 'lucky-user'))) $permission = true;
                else $permission = false;
                break;

            default:
                $permission = false;
                break;
        }
        return $permission;
    }
}