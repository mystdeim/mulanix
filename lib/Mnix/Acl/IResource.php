<?php
/**
 * Mulanix Framework
 */
namespace Mnix\Acl;
/**
 * Mulanix Framework
 *
 * @author deim
 */
interface IResource
{
    public function getResourceId();
    public function getPrivileges($role);
}