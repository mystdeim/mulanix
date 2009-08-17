<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Engine
 * @version $Id$
 */
/**
 * Блок, содержащий шаблоны
 *
 * @category Mulanix
 * @package Mnix_Engine
 */
class Mnix_Engine_Block extends Mnix_ORM_Prototype
{
    protected $_table = 'mnix_region';
    protected $_has_many = array(
        'templates' => array(
				    'class'  => 'Mnix_Engine_Template',
				    'fk'     => 'block_id',
                    'id'     => 'template_id',
                    'jtable' => 'mnix_page2template2block'));
}