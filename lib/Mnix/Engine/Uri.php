<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Uri
 * @version $Id$
 */
/**
 * Абстракция относительныйх путей
 *
 * @category Mulanix
 * @package Mnix_Uri
 */
class Mnix_Engine_Uri extends Mnix_ORM_Prototype
{
    protected $_table = 'mnix_uri';
    protected $_has_one = array(
        'page' => array(
            'class' => 'Mnix_Engine_Page',
            'fk'    => 'page_id')
        );
}