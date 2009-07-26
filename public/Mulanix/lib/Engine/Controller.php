<?php
/**
 * Mulanix Framework
 *
 * @package Mnix_Engine
 * @author deim
 * @copyright 2009
 */
/**
 * @package Mnix_Engine_Controller
 */
class Mnix_Engine_Controller extends Mnix_ORM_Prototype
{
    protected $_table = 'mnix_controller';
    protected $_rights;
    protected $_params;
    public function putRights($rights)
    {
        $this->_rights = $rights;
    }
    public function putParams($params)
    {
        $this->_params = $params;
    }
}