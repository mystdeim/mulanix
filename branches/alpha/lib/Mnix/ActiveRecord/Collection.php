<?php
/**
 * Mulanix Framework
 */
namespace Mnix\ActiveRecord;
/**
 * Mulanix Framework
 *
 * @author deim
 */
class Collection extends \ArrayObject
{
    protected $_select;
    public function __construct($class)
    {
        /*$this->_param = Mnix_ORM_Prototype::takeParam($class);
        $this->_param['class'] = $class;*/
    }
    public function putSelect($obj)
    {
        $this->_select = $obj;
    }
}