<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Acl
 * @version $Id$
 */
/**
 * Реализация списка прав доступа
 *
 * @category Mulanix
 * @package Mnix_Acl
 */
class Mnix_Acl
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
    public function  __construct($role = null)
    {
        $this->_role = $role;
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
    public function isAllowed($action, $resource = null)
    {
        $this->_action = $action;
        if (isset($resource)) $this->_resource = $resource;
        return $this->_load();
    }
    /**
     * Возвращаем массив с разрешенными правами
     *
     * TODO: старый запрос, нуждается в обновлении
     * 
     * @param object $resource
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
                    'a1.name', 'resource', 'r.criterion_id', 'criterion_id',
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
    /**
     * Загрузка результатов
     *
     * @return boolean
     */
    protected function _load()
    {
        $db = Mnix_Db::connect();
        $res = $db->query(
                'SELECT
                    ?t AS ?s, ?t AS ?s, 
                    ?t AS ?s,
                    ?t AS ?s, ?t AS ?s, ?t AS ?s
                FROM ?t AS ?t
                LEFT JOIN ?t AS ?t
                    ON ?t = ?t
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
                    'a1.name', 'resource',
                    'c.field', 'field', 'c.predicate', 'predicate', 'c.value', 'value',
                'mnix_right', 'r',
                'mnix_alias', 'a0',
                    'a0.id', 'r.role',
                'mnix_alias', 'a1',
                    'a1.id', 'r.resource',
                'mnix_alias', 'a2',
                    'a2.id', 'r.action',
                'mnix_criterion', 'c',
                    'r.criterion_id', 'c.id',
                    'a0.name', get_class($this->_role), 'r.role_id', $this->_role->id,
                    'a1.name', get_class($this->_resource), 'a2.name', $this->_action
            )
        );
        //Если запрос вернул результат, парсим, иначе фолз
        if (isset($res[0])) return $this->_parse($res);
        else return false;
    }
    /**
     * Обработка результата запроса
     *
     * @param array $res
     * @return boolean
     */
    protected function _parse($res)
    {
        foreach ($res as $temp) {
            //Смотрим предикат
            switch ($temp['predicate']) {
                case '=':
                    if ($this->_resource->$temp['field'] === $temp['value']) return true;
                    break;
                default:
                    break;
            }
        }
        return false;
    }
}