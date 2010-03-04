<?php
/**
 * Mulanix Framework

 * @version $Id$
 */
namespace Mnix;
/**
 * Реализация списка прав доступа
 */
class Acl
{
    /**
     * Роль - объект, который может запрашивать доступ к ресурсу
     *
     * @var object
     */
    protected $_role;
    /**
     * Ресурс - объект, доступ к которому контролируется
     *
     * @var object
     */
    protected $_resource;
    /**
     * Действие
     *
     * @var string
     */
    protected $_action;
    /**
     * Конструктор
     *
     * @param object $role
     */
    public function  __construct()
    {
        //$this->_role = $role;
    }
    /**
     * Устанавливаем объект, запрашивающий доступ
     *
     * @param object $role
     * @return this
     */
    public function role($role)
    {
        $this->_role = $role;
        return $this;
    }
    /**
     * Устанавливаем объект, доступ к которому контролируется
     *
     * @param object $resource
     * @return this
     */
    public function resource($resource)
    {
        $this->_resource = $resource;
        return $this;
    }
    /**
     * Проверить, разрешено ли действие
     *
     * @param string $action
     * @param object $resource
     * @return boolean
     */
    public function isAllowed($action, $role = null, $resource = null)
    {
        $this->_action = $action;
        if (isset($role)) $this->_role = $role;
        if (isset($resource)) $this->_resource = $resource;

        /*$actions = $this->_resource->getPrivileges($this->_role);
        if (in_array($this->_action, array_keys($actions))) {
            $permission = $this->_getFromDb();
            if (isset($actions[$this->_action]) && $permission) $permission = $actions[$this->_action]();
        } else {
            //TODO: no this rule
            $permission = false;
        }*/
        $actions = $this->_resource->getPrivileges($this->_role);
        $permission = $this->_getFromDb();
        if (isset($actions[$this->_action]) && $permission) $permission = $actions[$this->_action]();

        return $permission;
    }
    protected function _getFromDb()
    {
        throw new Exception('TODO!');
    }
}