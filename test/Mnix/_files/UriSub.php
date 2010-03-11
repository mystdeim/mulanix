<?php
/**
 * Mulanix Framework
 */
namespace Mnix;

require_once dirname(dirname(__DIR__)) . '/Helper.php';

require_once \Mnix\Path\LIB . '/Mnix/ActiveRecord/Common.php';
require_once \Mnix\Path\LIB . '/Mnix/ActiveRecord.php';
require_once \Mnix\Path\LIB . '/Mnix/Uri.php';

require_once \Mnix\Path\LIB . '/Mnix/Exception.php';
require_once \Mnix\Path\LIB . '/Mnix/Db.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Driver.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Driver/Statement.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Criteria.php';
require_once \Mnix\Path\LIB . '/Mnix/Db/Select.php';
require_once \Mnix\Path\LIB . '/Mnix/Core/Lang.php';

/**
 * Mulanix Framework
 *
 * @author deim
 */
class UriSub extends Uri
{
    public function explode($data)
    {
        return $this->_explode($data);
    }
    protected function _getUries($parent)
    {
        $all = array(
            array('id'=>1, 'parent'=>0),
            array('id'=>2, 'parent'=>1),
            array('id'=>3, 'parent'=>1)
        );

        $res = array();
        foreach ($all as $one) {
            if ($one['parent'] == $parent) $res[] = $one;
        }

        return $res;
    }
    /**
     * type = false => static url
     * type = true  => param
     *
     * @param <type> $parent
     * @return <type>
     */
    protected function _getParts($parent)
    {
        $all = array(
            array('id'=>1, 'name'=>''      , 'regexp'=>'help', 'parent'=>2, 'type'=>false, 'weight'=>1),
            array('id'=>2, 'name'=>''      , 'regexp'=>'page', 'parent'=>3, 'type'=>false, 'weight'=>1),
            array('id'=>3, 'name'=>'number', 'regexp'=>'\d*' , 'parent'=>3, 'type'=>true , 'weight'=>2)
        );

        $res = array();
        foreach ($all as $one) {
            if ($one['parent'] == $parent) $res[] = $one;
        }

        return $res;
    }
    protected function _getGetParams($parent)
    {
        $all = array(
            array('id'=>1, 'parent'=>2, 'name'=>'id')
        );

        $res = array();
        foreach ($all as $one) {
            if ($one['parent'] == $parent) $res[] = $one;
        }

        return $res;
    }
}