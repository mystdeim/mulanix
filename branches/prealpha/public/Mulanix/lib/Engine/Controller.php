<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Engine
 * @since 2008-10-01
 * @version 2009-08-06
 */
/**
 * Контроллер
 *
 * @category Mulanix
 * @package Mnix_Engine
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