<?php
/**
 * Mulanix Framework
 */
namespace Mnix;

require_once '_files/AclSub.php';

require_once '_files/AclSub/Role.php';
require_once '_files/AclSub/Resource.php';

/**
 * Mulanix Framework
 *
 * @author deim
 */
class AclTest extends \PHPUnit_Framework_TestCase
{
    public function testResource()
    {
        $resource = new AclSub\Resource();
        $actions = $resource->getPrivileges('testrole');
        $this->assertEquals(array('view','edit','editOwner'), array_keys($actions));
    }
    /**
     * @dataProvider providerIsAllowed
     */
    public function test1($permision, $action, $role, $resource)
    {
        $acl = new AclSub();

        $this->assertEquals($permision, $acl->isAllowed($action,new AclSub\Role($role),new AclSub\Resource($resource)));
    }
    public function providerIsAllowed()
    {
        return array(
            array(true , 'view'     , 'group-guest' , 'note'),
            array(false, 'edit'     , 'group-guest' , 'note'),
            array(false, 'editOwner', 'group-member', 'note'),
            array(true , 'editOwner', 'lucky-user'  , 'note')
        );
    }
}