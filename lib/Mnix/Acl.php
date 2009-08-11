<?php
/**
 * Mulanix Framework
 *
 * @package Mnix_Acl
 * @author deim
 * @copyright 2009
 */
/**
 * @package Mnix_Acl
 */
class Mnix_Acl
{
    protected $_role;
    protected $_resource;
    protected $_action;
    public function  __construct($role = null)
    {
        $this->_role = $role;
    }
    public function role($role)
    {
        $this->_role = $role;
    }
    public function resource($resource)
    {
        $this->_resource = $resource;
    }
    public function isAllowed($action, $resource = null)
    {
        $this->_action = $action;
        if (isset($resource)) $this->_resource = $resource;
        $res = $this->_load();
        if (isset($res) && ($res['resorce_id'] === $this->_resource->getId() || $res['resorce_id'] === null)) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Возвращаем массив с разрешенными правами
     * @param Object $resource
     * @return array
     */
    public function allowed($resource = null)
    {
        $db = Mnix_Db::connect();
        $res = $db->query(
                'SELECT
                    ?t AS ?s, ?t AS ?s,
                    ?t AS ?s, ?t AS ?s,
                    ?t AS ?s
                FROM ?t AS ?t
                LEFT JOIN ?t AS ?t
                    ON ?t = ?t
                LEFT JOIN ?t AS ?t
                    ON ?t = ?t
                LEFT JOIN ?t AS ?t
                    ON ?t = ?t
                WHERE
                    ?t = ?s AND ?t = ?i AND
                    ?t = ?s AND ?t = ?i',
            array(
                    'a0.name', 'role', 'r.role_id', 'role_id',
                    'a1.name', 'resource', 'r.resource_id', 'resource_id',
                    'a2.name', 'action',
                'mnix_right', 'r',
                'mnix_alias', 'a0',
                    'a0.id', 'r.role',
                'mnix_alias', 'a1',
                    'a1.id', 'r.resource',
                'mnix_alias', 'a2',
                    'a2.id', 'r.action',
                    'a0.name', get_class($this->_role), 'r.role_id', $this->_role->getId(),
                    'a1.name', get_class($this->_resource), 'r.resource_id', $this->_resource->getId()
            )
        );
        if (isset($res)) foreach ($res as $temp) $arr[] = $temp['action'];
        else $arr = null;
        return $arr;

    }
    protected function _load()
    {
        $db = Mnix_Db::connect();
        $res = $db->query(
                'SELECT
                    ?t AS ?s, ?t AS ?s, 
                    ?t AS ?s, ?t AS ?s,
                    ?t AS ?s
                FROM ?t AS ?t
                LEFT JOIN ?t AS ?t
                    ON ?t = ?t
                LEFT JOIN ?t AS ?t
                    ON ?t = ?t
                LEFT JOIN ?t AS ?t
                    ON ?t = ?t
                WHERE
                    ?t = ?s AND ?t = ?i AND
                    ?t = ?s AND ?t = ?s',
            array(
                    'a0.name', 'role', 'r.role_id', 'role_id',
                    'a1.name', 'resource', 'r.resource_id', 'resource_id',
                    'a2.name', 'action',
                'mnix_right', 'r',
                'mnix_alias', 'a0',
                    'a0.id', 'r.role',
                'mnix_alias', 'a1',
                    'a1.id', 'r.resource',
                'mnix_alias', 'a2',
                    'a2.id', 'r.action',
                    'a0.name', get_class($this->_role), 'r.role_id', $this->_role->getId(),
                    'a1.name', get_class($this->_resource), 'a2.name', $this->_action
            )
        );
        return $res[0];
    }
}